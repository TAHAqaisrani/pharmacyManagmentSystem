@extends('layouts.admin')

@section('header', 'Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Cards -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-500">Total Revenue</h3>
            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">This Month</span>
        </div>
        <div class="text-3xl font-bold text-gray-900">${{ number_format($monthlyRevenue, 2) }}</div>
        <div class="text-sm text-gray-500 mt-1">Today: <span class="text-green-600 font-medium">+${{ number_format($todaysSales, 2) }}</span></div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-500">Medicines</h3>
            <span class="p-1.5 rounded-lg bg-blue-100 text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </span>
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ $medicineCount }}</div>
        <div class="text-sm text-gray-500 mt-1">Total Stock: {{ $totalStock }} units</div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-500">Expiring Soon</h3>
            <span class="p-1.5 rounded-lg bg-yellow-100 text-yellow-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </span>
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ $expiringSoon }}</div>
        <div class="text-sm text-gray-500 mt-1">Batches expiring < 90 days</div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-500">Suppliers</h3>
            <span class="p-1.5 rounded-lg bg-purple-100 text-purple-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </span>
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ $supplierCount }}</div>
        <div class="text-sm text-gray-500 mt-1">Active partners</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Chart -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-6">Sales Overview (Last 7 Days)</h3>
        <canvas id="salesChart" height="120"></canvas>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-6">Quick Actions</h3>
        <div class="space-y-4">
            <a href="{{ route('sales.create') }}" class="block p-4 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">New Sale</div>
                        <div class="text-sm text-gray-500">Process a new invoice</div>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('admin.batches.create') }}" class="block p-4 rounded-lg border border-gray-200 hover:border-green-500 hover:bg-green-50 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 text-green-600 p-3 rounded-lg group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Add Stock</div>
                        <div class="text-sm text-gray-500">Register new batch</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.settings.index') }}" class="block p-4 rounded-lg border border-gray-200 hover:border-purple-500 hover:bg-purple-50 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 text-purple-600 p-3 rounded-lg group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Settings</div>
                        <div class="text-sm text-gray-500">Configure system</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prepare Chart Data
        const salesData = {!! json_encode($salesData) !!};
        // Generate last 7 days labels to ensure gaps are filled (simplified for now to just show data points)
        const labels = Object.keys(salesData);
        const data = Object.values(salesData);

        const ctx = document.getElementById('salesChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue ($)',
                        data: data,
                        borderColor: 'rgb(37, 99, 235)', // Blue-600
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
