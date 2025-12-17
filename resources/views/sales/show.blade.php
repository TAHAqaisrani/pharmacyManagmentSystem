@extends('layouts.admin')

@section('header', 'Invoice Details')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex justify-between items-center print:hidden">
        <a href="{{ route('sales.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Sales
        </a>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print Invoice
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 print:shadow-none print:border-none" id="invoice">
        <!-- Header -->
        <div class="flex justify-between items-start border-b border-gray-100 pb-8 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ \App\Models\Setting::get('pharmacy_name', 'PHARMACARE') }}</h1>
                <p class="text-gray-500 mt-1">{!! nl2br(e(\App\Models\Setting::get('pharmacy_address', '123 Health Street, Med City'))) !!}</p>
                <p class="text-gray-500">Phone: {{ \App\Models\Setting::get('pharmacy_phone', '(555) 123-4567') }}</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-gray-600">INVOICE</h2>
                <p class="text-gray-900 font-mono mt-2 font-bold">{{ $invoice->invoice_no }}</p>
                <p class="text-gray-500 text-sm mt-1">Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Items -->
        <table class="w-full text-left mb-8">
            <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">Item Description</th>
                    <th class="px-4 py-3 text-center">Batch</th>
                    <th class="px-4 py-3 text-center">Qty</th>
                    <th class="px-4 py-3 text-right">Unit Price</th>
                    <th class="px-4 py-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($invoice->items as $item)
                <tr>
                    <td class="px-4 py-3">
                        <span class="font-medium text-gray-900">{{ $item->medicine->name }}</span>
                        <div class="text-xs text-gray-500">{{ $item->medicine->sku }}</div>
                    </td>
                    <td class="px-4 py-3 text-center text-sm text-gray-500">
                        {{ $item->batch_id ? 'B-'.$item->batch_id : '-' }}
                    </td>
                    <td class="px-4 py-3 text-center text-sm font-medium text-gray-900">{{ $item->quantity }}</td>
                    <td class="px-4 py-3 text-right text-sm text-gray-500">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="px-4 py-3 text-right text-sm font-bold text-gray-900">${{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="border-t border-gray-100 pt-8">
            <div class="flex justify-end gap-10 mb-2">
                <span class="text-gray-600 font-medium">Subtotal</span>
                <span class="text-gray-900 font-bold">${{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            @if($invoice->discount_amount > 0 || $invoice->discount_percent > 0)
            <div class="flex justify-end gap-10 mb-2 text-red-600">
                <span class="font-medium">Discount</span>
                <span>
                    @if($invoice->discount_amount > 0)
                        -${{ number_format($invoice->discount_amount, 2) }}
                    @else
                        -{{ $invoice->discount_percent }}%
                    @endif
                </span>
            </div>
            @endif
            <div class="flex justify-end gap-10 text-xl pt-4 border-t border-gray-100 mt-4">
                <span class="font-bold text-gray-800">Total</span>
                <span class="font-bold text-blue-600">${{ number_format($invoice->total, 2) }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-100 mt-12 pt-8 text-center text-sm text-gray-500">
            <p>Thank you for your business!</p>
            <p class="mt-1">For questions concerning this invoice, please contact us.</p>
        </div>
    </div>
</div>
@endsection
