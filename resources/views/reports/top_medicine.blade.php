@extends('layouts.admin')

@section('header', 'Top Selling Medicines')

@section('content')
<div class="mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('reports.top_medicine') }}" class="flex items-end gap-4">
            <div class="flex-1 max-w-xs">
                <label class="block text-sm font-medium text-gray-700 mb-1">Time Period</label>
                <select name="days" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="90" {{ $days == 90 ? 'selected' : '' }}>Last 3 Months</option>
                    <option value="365" {{ $days == 365 ? 'selected' : '' }}>Last Year</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition-colors">
                Analyze
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Visual Analysis</h3>
        </div>
        <div class="p-6">
            <canvas id="topMedicinesChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Detailed Rankings</h3>
        </div>
        
        <div class="p-6">
            @if($top->isEmpty())
                <p class="text-center text-gray-500 py-10">No sales data available for this period.</p>
            @else
                <div class="space-y-4">
                    @foreach($top as $index => $item)
                    <div class="flex items-center p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full 
                            {{ $index == 0 ? 'bg-yellow-100 text-yellow-600' : ($index == 1 ? 'bg-gray-200 text-gray-600' : ($index == 2 ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600')) }} 
                            font-bold text-lg">
                            #{{ $index + 1 }}
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex justify-between items-start">
                                <h4 class="text-lg font-bold text-gray-900">{{ $item->medicine->name }}</h4>
                                <span class="text-sm font-medium text-gray-500">{{ $item->qty }} Units Sold</span>
                            </div>
                            <div class="mt-1">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($item->qty / $top->first()->qty) * 100 }}%"></div>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">SKU: {{ $item->medicine->sku }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('topMedicinesChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($top->pluck('medicine.name')) !!},
                    datasets: [{
                        label: 'Units Sold',
                        data: {!! json_encode($top->pluck('qty')) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
