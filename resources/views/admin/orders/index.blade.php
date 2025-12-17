@extends('layouts.admin')

@section('header', 'Customer Orders')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Order ID</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Items</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-mono text-sm text-gray-600">{{ $order->order_no }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $order->user->name ?? 'Guest' }}</div>
                        <div class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $order->items->count() }} items
                            <div class="text-xs text-gray-500 truncate max-w-xs" title="{{ $order->items->pluck('medicine.name')->join(', ') }}">
                                {{ $order->items->pluck('medicine.name')->take(2)->join(', ') }} {{ $order->items->count() > 2 ? '...' : '' }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($order->status === 'approved') bg-green-100 text-green-800
                            @elseif($order->status === 'rejected') bg-red-100 text-red-800
                            @elseif($order->status === 'completed') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $order->created_at->format('M d, Y H:i') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($order->status === 'pending')
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" onsubmit="return confirm('Approve this order and deduct stock?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="bg-green-100 hover:bg-green-200 text-green-700 p-2 rounded-lg transition-colors" title="Approve">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </form>
                                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" onsubmit="return confirm('Reject this order?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition-colors" title="Reject">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-xs text-gray-400">Processed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        No orders found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
