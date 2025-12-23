<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Medicine;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        $query = Batch::with(['medicine', 'supplier']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('batch_no', 'like', "%{$search}%")
                  ->orWhereHas('medicine', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $batches = $query->paginate(20);
        return view('admin.batches.index', compact('batches'));
    }

    public function create()
    {
        $medicines = Medicine::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('admin.batches.create', compact('medicines', 'suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'batch_no' => 'required|string|max:255',
            'expiry_date' => 'nullable|date',
            'quantity' => 'required|integer|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
        ]);
        Batch::create($data);
        return redirect()->route('admin.batches.index');
    }

    public function edit(Batch $batch)
    {
        $medicines = Medicine::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('admin.batches.edit', compact('batch', 'medicines', 'suppliers'));
    }

    public function update(Request $request, Batch $batch)
    {
        $data = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'batch_no' => 'required|string|max:255',
            'expiry_date' => 'nullable|date',
            'quantity' => 'required|integer|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
        ]);
        $batch->update($data);
        return redirect()->route('admin.batches.index');
    }

    public function destroy(Batch $batch)
    {
        $batch->delete();
        return redirect()->route('admin.batches.index');
    }

    public function alerts()
    {
        $expiringSoon = Batch::expiringSoon()->with('medicine')->get();
        $expired = Batch::expired()->with('medicine')->get();
        return view('admin.batches.alerts', compact('expiringSoon', 'expired'));
    }

    public function trash()
    {
        $batches = Batch::onlyTrashed()->with(['medicine', 'supplier'])->paginate(20);
        return view('admin.batches.trash', compact('batches'));
    }

    public function restore($id)
    {
        $batch = Batch::withTrashed()->findOrFail($id);
        $batch->restore();
        return redirect()->route('admin.batches.trash')->with('success', 'Batch restored successfully.');
    }

    public function forceDelete($id)
    {
        $batch = Batch::withTrashed()->findOrFail($id);
        $batch->forceDelete();
        return redirect()->route('admin.batches.trash')->with('success', 'Batch permanently deleted.');
    }
}

