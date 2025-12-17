<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - Pharmacy Manager</title>
    
    {{-- Include the compiled CSS and JS assets using Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    <div class="min-h-screen flex flex-row">
        
        {{-- Sidebar Navigation --}}
        <aside class="w-64 bg-slate-900 text-white flex flex-col shadow-xl">
            {{-- Sidebar Header / Logo --}}
            <div class="h-16 flex items-center justify-center border-b border-slate-800">
                <h1 class="text-xl font-bold tracking-wider text-blue-400">PHARMA<span class="text-white">SYS</span></h1>
            </div>
            
            {{-- Navigation Links --}}
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-2">
                    
                    {{-- Dashboard Link --}}
                    <li>
                        {{-- 
                            The 'request()->routeIs()' function checks if the current URL matches the given route name.
                            We use it here to conditionally apply active styles (blue background) to the link.
                        --}}
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    
                    {{-- Section Header: Inventory --}}
                    <li class="pt-4 pb-1 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Inventory</li>
                    
                    {{-- Medicines Link --}}
                    <li>
                        <a href="{{ route('admin.medicines.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.medicines.*') ? 'bg-slate-800 text-blue-400' : 'text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            <span>Medicines</span>
                        </a>
                    </li>
                    
                    {{-- Suppliers Link --}}
                    <li>
                        <a href="{{ route('admin.suppliers.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.suppliers.*') ? 'bg-slate-800 text-blue-400' : 'text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span>Suppliers</span>
                        </a>
                    </li>
                    
                    {{-- Batches Link --}}
                    <li>
                        <a href="{{ route('admin.batches.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.batches.*') ? 'bg-slate-800 text-blue-400' : 'text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <span>Stock / Batches</span>
                        </a>
                    </li>

                    {{-- Section Header: Business --}}
                    <li class="pt-4 pb-1 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Business</li>

                    {{-- Customer Orders Link --}}
                    <li>
                        <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-slate-800 text-blue-400' : 'text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span>Customer Orders</span>
                        </a>
                    </li>

                    {{-- Sales & Billing Link --}}
                    <li>
                        <a href="{{ route('sales.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('sales.*') ? 'bg-slate-800 text-blue-400' : 'text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Sales & Billing</span>
                        </a>
                    </li>

                    {{-- Reports Link --}}
                    <li>
                        <a href="{{ route('reports.daily_sales') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('reports.*') ? 'bg-slate-800 text-blue-400' : 'text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>Reports</span>
                        </a>
                    </li>

                    {{-- Settings Link --}}
                    <li>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-slate-800 text-blue-400' : 'text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Settings</span>
                        </a>
                    </li>

                    {{-- Email Logs Link --}}
                    <li>
                        <a href="{{ route('admin.email-logs.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('admin.email-logs.*') ? 'bg-slate-800 text-blue-400' : 'text-slate-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>Email Logs</span>
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- Logout Button --}}
            <div class="p-4 border-t border-slate-800">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-slate-400 hover:text-white transition-colors w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            
            {{-- Top Header --}}
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8">
                <div class="flex items-center gap-4">
                    {{-- @yield('header') allows child views to inject a custom header title here --}}
                    <h2 class="text-xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                        A
                    </div>
                    <span class="text-sm font-medium text-gray-600">TAHA_qaisrani (Admin)</span>
                </div>
            </header>

            {{-- Scrollable Content Wrapper --}}
            <div class="flex-1 overflow-y-auto p-8">
                
                {{-- Toast Notifications --}}
                @if(session('success'))
                    <div id="toast-success" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-md p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-lg border-l-4 border-green-500 animate-slide-in">
                        <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-green-500 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-sm font-medium text-gray-900">{{ session('success') }}</div>
                        <button type="button" onclick="closeToast('toast-success')" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div id="toast-error" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-md p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-lg border-l-4 border-red-500 animate-slide-in">
                        <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-red-500 bg-red-100 rounded-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-sm font-medium text-gray-900">{{ session('error') }}</div>
                        <button type="button" onclick="closeToast('toast-error')" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                {{-- The main content of child views will be injected here --}}
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Toast Auto-dismiss Script --}}
    <script>
        // Auto-dismiss toasts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successToast = document.getElementById('toast-success');
            const errorToast = document.getElementById('toast-error');
            
            if (successToast) {
                setTimeout(() => closeToast('toast-success'), 5000);
            }
            
            if (errorToast) {
                setTimeout(() => closeToast('toast-error'), 5000);
            }
        });

        function closeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }
        }
    </script>

    {{-- Toast Animation Styles --}}
    <style>
        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }

        #toast-success, #toast-error {
            transition: opacity 0.3s ease-out, transform 0.3s ease-out;
        }
    </style>

