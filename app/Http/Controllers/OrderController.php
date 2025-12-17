<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Admin: List all orders
    public function index()
    {
        $orders = Order::with('user', 'items.medicine')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    // Customer: Checkout
    public function store(Request $request)
    {
        $cart = session()->get('cart');

        if(!$cart) {
            return redirect()->back()->with('error', 'Cart is empty!');
        }

        DB::beginTransaction();

        try {
            $total = 0;
            foreach($cart as $id => $details) {
                $total += $details['price'] * $details['quantity'];
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_no' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $total,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'shipping_address' => 'Default Address (Update Profile)' // Simplified
            ]);

            foreach($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'medicine_id' => $id,
                    'quantity' => $details['quantity'],
                    'unit_price' => $details['price'],
                    'subtotal' => $details['price'] * $details['quantity']
                ]);
            }

            DB::commit();
            session()->forget('cart');

            DB::commit();
            session()->forget('cart');

            // Redirect to Payment Simulation instead of Home
            return redirect()->route('payment.checkout', ['order' => $order->id]);

        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // Admin: Update Status (Approve/Reject)
    public function update(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);
        $status = $request->input('status');

        if ($status == 'approved' && $order->status != 'approved') {
            // Deduct Stock
            foreach ($order->items as $item) {
                $quantityNeeded = $item->quantity;
                
                // FIFO: Get batches expiring soonest with stock
                $batches = Batch::where('medicine_id', $item->medicine_id)
                    ->where('quantity', '>', 0)
                    ->where('expiry_date', '>', now())
                    ->orderBy('expiry_date', 'asc')
                    ->get();

                foreach ($batches as $batch) {
                    if ($quantityNeeded <= 0) break;

                    if ($batch->quantity >= $quantityNeeded) {
                        $batch->quantity -= $quantityNeeded;
                        $batch->save();
                        $quantityNeeded = 0;
                    } else {
                        $quantityNeeded -= $batch->quantity;
                        $batch->quantity = 0;
                        $batch->save();
                    }
                }

                if ($quantityNeeded > 0) {
                    return redirect()->back()->with('error', 'Insufficient stock for medicine: ' . $item->medicine->name);
                }
            }

            // Generate Invoice
            $invoice = \App\Models\Invoice::create([
                'invoice_no' => 'INV-' . strtoupper(uniqid()),
                'invoice_date' => now(),
                'discount_percent' => 0,
                'discount_amount' => 0, // Discounts not yet implemented for web orders
                'subtotal' => $order->total_amount,
                'total' => $order->total_amount,
                'created_by' => Auth::guard('admin')->id() ?? 1, // Default to admin 1 if not strictly logged in as admin during approval (though middleware enforces it)
            ]);

            foreach ($order->items as $item) {
                \App\Models\InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'medicine_id' => $item->medicine_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal
                ]);
            }
        }

        $order->update(['status' => $status]);

        return redirect()->back()->with('success', 'Order status updated to ' . $status . ' and Invoice generated.');
    }
}
