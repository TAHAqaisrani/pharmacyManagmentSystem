<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::query();

        if ($request->filled('search')) {
            $query->where('invoice_no', 'like', '%' . $request->search . '%');
        }

        $invoices = $query->orderByDesc('invoice_date')->paginate(20);
        return view('sales.index', compact('invoices'));
    }

    public function create()
    {
        $medicines = Medicine::with('batches')->orderBy('name')->get();
        return view('sales.create', compact('medicines'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_no' => 'required|string|max:255|unique:invoices,invoice_no',
            'invoice_date' => 'required|date',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.batch_id' => 'nullable|exists:batches,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($data) {
            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $lineTotal = ($item['unit_price'] * $item['quantity']);
                $lineTotal -= ($item['discount_amount'] ?? 0);
                if (!empty($item['discount_percent'])) {
                    $lineTotal -= ($lineTotal * ($item['discount_percent'] / 100));
                }
                $subtotal += $lineTotal;
            }

            $invoice = Invoice::create([
                'invoice_no' => $data['invoice_no'],
                'invoice_date' => $data['invoice_date'],
                'discount_amount' => $data['discount_amount'] ?? 0,
                'discount_percent' => $data['discount_percent'] ?? 0,
                'subtotal' => $subtotal,
                'total' => max(0, $subtotal - ($data['discount_amount'] ?? 0) - ($subtotal * (($data['discount_percent'] ?? 0) / 100))),
                'created_by' => null,
            ]);

            foreach ($data['items'] as $item) {
                $lineTotal = ($item['unit_price'] * $item['quantity']);
                $lineTotal -= ($item['discount_amount'] ?? 0);
                if (!empty($item['discount_percent'])) {
                    $lineTotal -= ($lineTotal * ($item['discount_percent'] / 100));
                }

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'medicine_id' => $item['medicine_id'],
                    'batch_id' => $item['batch_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'discount_percent' => $item['discount_percent'] ?? 0,
                    'subtotal' => $lineTotal,
                ]);

                if (!empty($item['batch_id'])) {
                    $batch = Batch::find($item['batch_id']);
                    if ($batch) {
                        $batch->decrement('quantity', $item['quantity']);
                    }
                } else {
                    $batch = Batch::where('medicine_id', $item['medicine_id'])
                        ->where('quantity', '>', 0)
                        ->orderBy('expiry_date')
                        ->first();
                    if ($batch) {
                        $batch->decrement('quantity', $item['quantity']);
                    }
                }
            }

            return redirect()->route('sales.index');
        });
    }
    public function show($id)
    {
        $invoice = Invoice::with(['items.medicine'])->findOrFail($id);
        return view('sales.show', compact('invoice'));
    }
}
