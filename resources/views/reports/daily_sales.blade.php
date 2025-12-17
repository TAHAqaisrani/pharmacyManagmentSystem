@extends('layouts.admin')

@section('header', 'Daily Sales Report')

@section('content')
<div class="mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('reports.daily_sales') }}" class="flex items-end gap-4">
            <div class="flex-1 max-w-xs">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Date</label>
                <input type="date" name="date" value="{{ $date }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition-colors">
                Generate Report
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg shadow-blue-200 p-6 text-white">
        <h4 class="text-blue-100 text-sm font-medium uppercase tracking-wider">Total Revenue</h4>
        <div class="mt-2 text-3xl font-bold">${{ number_format($total, 2) }}</div>
        <div class="mt-1 text-blue-100 text-sm">for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h4 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Invoices</h4>
        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $invoices->count() }}</div>
        <div class="mt-1 text-gray-400 text-sm">processed today</div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h4 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Average Sale</h4>
        <div class="mt-2 text-3xl font-bold text-gray-900">
            ${{ $invoices->count() > 0 ? number_format($total / $invoices->count(), 2) : '0.00' }}
        </div>
        <div class="mt-1 text-gray-400 text-sm">per invoice</div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-800">Transactions</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 w-10"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($invoices as $inv)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-blue-600">
                        {{ $inv->invoice_no }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $inv->created_at->format('H:i A') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                        ${{ number_format($inv->subtotal, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-red-500">
                        @if($inv->discount_amount > 0)
                            -${{ number_format($inv->discount_amount, 2) }}
                        @elseif($inv->discount_percent > 0)
                            -{{ $inv->discount_percent }}%
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                        ${{ number_format($inv->total, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('sales.show', $inv->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">
                        No sales recorded for this date.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
