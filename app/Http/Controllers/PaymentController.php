<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // Step 1: Show Payment Options
    public function checkout(Order $order)
    {
        if ($order->payment_status == 'paid') {
            return redirect()->route('payment.receipt', $order->id)->with('info', 'Order is already paid.');
        }
        return view('payment.checkout', compact('order'));
    }

    // Step 2: "Fake API" Simulation
    public function simulate(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
            'method' => 'required|string'
        ]);

        // SIMULATION LOGIC
        // 90% Success Rate for demo purposes
        $isSuccess = rand(1, 100) <= 90; 
        
        if ($isSuccess) {
            return response()->json([
                'status' => 'SUCCESS',
                'transaction_id' => 'SIM-' . mt_rand(100000, 999999)
            ]);
        } else {
            return response()->json([
                'status' => 'FAILED',
                'message' => 'Transaction declined by issuer.'
            ], 400);
        }
    }

    // Step 3: Handle Success & Create Record
    public function confirm(Request $request) 
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'transaction_id' => 'required|string',
            'method' => 'required|string',
            'amount' => 'required|numeric'
        ]);

        $order = Order::findOrFail($request->order_id);

        if($order->payment_status == 'paid') {
             return redirect()->route('payment.receipt', $order->id);
        }

        DB::transaction(function () use ($order, $request) {
            // Create Payment Record
            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => $request->transaction_id,
                'payment_method' => $request->method,
                'amount' => $request->amount,
                'status' => 'SUCCESS'
            ]);

            // 1. Update Order Status
            $order->update([
                'payment_status' => 'paid'
                // Status remains 'pending' for admin approval
            ]);
        });

        return redirect()->route('payment.receipt', $order->id)->with('success', 'Payment Successful!');
    }

    // Step 4: Show Receipt
    public function receipt(Order $order)
    {
        $order->load('payment', 'items.medicine');
        return view('payment.receipt', compact('order'));
    }
}
