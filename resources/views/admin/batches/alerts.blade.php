@extends('layouts.admin')

@section('header', 'Expiry Alerts')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Expiring Soon -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-yellow-50 flex items-center justify-between">
            <h3 class="font-bold text-yellow-800 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Expiring Soon (Next 90 Days)
            </h3>
            <span class="bg-yellow-200 text-yellow-800 text-xs font-bold px-2 py-1 rounded-full">{{ $expiringSoon->count() }}</span>
        </div>
        <div class="p-6">
            @if($expiringSoon->isEmpty())
                <p class="text-gray-500 text-center py-4">No medicines are expiring soon.</p>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach($expiringSoon as $batch)
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $batch->medicine->name }}</p>
                            <p class="text-xs text-gray-500">Batch: {{ $batch->batch_no }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-yellow-600 font-bold">{{ $batch->expiry_date }}</p>
                            <p class="text-xs text-gray-400">{{ $batch->quantity }} units</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Expired -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-red-50 flex items-center justify-between">
            <h3 class="font-bold text-red-800 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Already Expired
            </h3>
            <span class="bg-red-200 text-red-800 text-xs font-bold px-2 py-1 rounded-full">{{ $expired->count() }}</span>
        </div>
        <div class="p-6">
            @if($expired->isEmpty())
                <p class="text-gray-500 text-center py-4">No expired medicines found.</p>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach($expired as $batch)
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $batch->medicine->name }}</p>
                            <p class="text-xs text-gray-500">Batch: {{ $batch->batch_no }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-red-600 font-bold decoration-dotted underline">{{ $batch->expiry_date }}</p>
                            <p class="text-xs text-gray-400">{{ $batch->quantity }} units</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
