<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::withCount('batches');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $medicines = $query->paginate(20);
        return view('admin.medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('admin.medicines.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:medicines,sku',
            'category' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'default_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'reorder_level' => 'nullable|integer|min:0',
        ]);
        Medicine::create($data);
        return redirect()->route('admin.medicines.index');
    }

    public function edit(Medicine $medicine)
    {
        return view('admin.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:medicines,sku,'.$medicine->id,
            'category' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'default_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'reorder_level' => 'nullable|integer|min:0',
        ]);
        $medicine->update($data);
        return redirect()->route('admin.medicines.index');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('admin.medicines.index');
    }
}

