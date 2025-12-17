@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-xl overflow-hidden print:shadow-none">
        
        <div class="bg-green-600 px-8 py-6 text-white flex justify-between items-center print:bg-white print:text-black">
            <div>
                <h1 class="text-2xl font-bold">Payment Receipt</h1>
                <p class="text-green-100 text-sm mt-1 print:text-gray-600">Thank you for your purchase!</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold">Rs. {{ number_format($order->total_amount, 2) }}</div>
                <div class="text-green-100 text-xs uppercase tracking-wide print:text-gray-600">Paid Successfully</div>
            </div>
        </div>

        <div class="p-8">
            <div class="flex justify-between border-b border-gray-100 pb-8 mb-8">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Billed To</h3>
                    <p class="mt-2 text-lg font-bold text-gray-900">{{ $order->user->name }}</p>
                    <p class="text-gray-600">{{ $order->user->email }}</p>
                </div>
                <div class="text-right">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Transaction Info</h3>
                    <p class="mt-2 text-sm text-gray-900"><span class="font-medium">Order #:</span> {{ $order->order_no }}</p>
                    <p class="text-sm text-gray-900"><span class="font-medium">Trx ID:</span> {{ $order->payment->transaction_id ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-900"><span class="font-medium">Date:</span> {{ $order->payment->created_at->format('d M Y, h:i A') }}</p>
                    <p class="text-sm text-gray-900"><span class="font-medium">Method:</span> {{ $order->payment->payment_method }}</p>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h3>
            <table class="w-full mb-8">
                <thead>
                    <tr class="text-left border-b-2 border-gray-100">
                        <th class="pb-3 text-sm font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="pb-3 text-sm font-medium text-gray-500 uppercase tracking-wider text-right">Qty</th>
                        <th class="pb-3 text-sm font-medium text-gray-500 uppercase tracking-wider text-right">Price</th>
                        <th class="pb-3 text-sm font-medium text-gray-500 uppercase tracking-wider text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="py-4 text-gray-900">{{ $item->medicine->name }}</td>
                        <td class="py-4 text-gray-600 text-right">{{ $item->quantity }}</td>
                        <td class="py-4 text-gray-600 text-right">Rs. {{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-4 text-gray-900 font-medium text-right">Rs. {{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="border-t-2 border-gray-100 pt-8 flex justify-center print:hidden">
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Return to Shop
                </a>
                <button onclick="window.print()" class="ml-6 text-gray-600 hover:text-gray-900 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
