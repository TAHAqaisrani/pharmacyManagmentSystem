@extends('layouts.admin')

@section('header', 'Edit Batch')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <form method="POST" action="{{ route('admin.batches.update', $batch->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Medicine: <span class="font-bold text-gray-900">{{ $batch->medicine->name }}</span> ({{ $batch->medicine->sku }})</label>
                    <input type="hidden" name="medicine_id" value="{{ $batch->medicine_id }}">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Supplier</label>
                    <select name="supplier_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        <option value="">-- None / Unknown --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }} {{ $batch->supplier_id == $s->id ? 'selected' : '' }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Batch Number</label>
                    <input type="text" name="batch_no" value="{{ old('batch_no', $batch->batch_no) }}" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date', $batch->expiry_date) }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Stock Quantity</label>
                    <input type="number" name="quantity" value="{{ old('quantity', $batch->quantity) }}" required min="0" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                
                <div>
                    <!-- Spacer -->
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cost Price</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                        <input type="number" name="cost_price" value="{{ old('cost_price', $batch->cost_price) }}" step="0.01" class="w-full pl-7 px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selling Price</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                        <input type="number" name="selling_price" value="{{ old('selling_price', $batch->selling_price) }}" step="0.01" class="w-full pl-7 px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('admin.batches.index') }}" class="px-5 py-2.5 text-gray-600 hover:text-gray-800 font-medium rounded-lg">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg shadow-sm hover:shadow transition-all">Update Batch</button>
            </div>
        </form>
    </div>
</div>
@endsection
