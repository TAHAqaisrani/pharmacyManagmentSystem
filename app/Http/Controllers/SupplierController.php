<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::paginate(20);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);
        Supplier::create($data);
        return redirect()->route('admin.suppliers.index');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);
        $supplier->update($data);
        return redirect()->route('admin.suppliers.index');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('admin.suppliers.index');
    }

    public function emailForm(Supplier $supplier)
    {
        return view('admin.suppliers.email', compact('supplier'));
    }

    public function sendEmail(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if (!$supplier->email) {
            return redirect()->back()->with('error', 'This supplier does not have an email address.');
        }

        try {
            \Mail::to($supplier->email)->send(new \App\Mail\SupplierRequestMail($data['subject'], $data['message']));
            return redirect()->route('admin.suppliers.index')->with('success', 'Email sent successfully to ' . $supplier->name);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}

