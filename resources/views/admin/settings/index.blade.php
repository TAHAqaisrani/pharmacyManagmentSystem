@extends('layouts.admin')

{{-- 
    This is the System Settings page.
    It uses the 'layouts.admin' layout which includes the sidebar and navigation.
--}}

@section('header', 'System Settings')

@section('content')
<div class="max-w-3xl mx-auto">
    
    {{-- Success Message Alert --}}
    {{-- Checks if there is a 'success' message in the session and displays it --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        {{-- Settings Form --}}
        {{-- This form submits to the 'admin.settings.update' route using the POST method --}}
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            
            {{-- @csrf is a security token required by Laravel for all POST forms to prevent CSRF attacks --}}
            @csrf
            
            <div class="border-b border-gray-100 pb-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pharmacy Details</h3>
                <p class="text-sm text-gray-500 mb-6">These details will appear on invoices and reports.</p>

                <div class="grid grid-cols-1 gap-6">
                    
                    {{-- Pharmacy Name Input --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pharmacy Name</label>
                        {{-- 
                            We use the Null Coalescing Operator (??) to show the saved setting OR a default value 'PHARMACARE' 
                            if the setting doesn't exist yet.
                        --}}
                        <input type="text" 
                               name="pharmacy_name" 
                               value="{{ $settings['pharmacy_name'] ?? 'PHARMACARE' }}" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>

                    {{-- Address Textarea --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea name="pharmacy_address" 
                                  rows="3" 
                                  class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        >{{ $settings['pharmacy_address'] ?? '123 Health Street, Med City' }}</textarea>
                    </div>

                    {{-- Phone Number Input --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" 
                               name="pharmacy_phone" 
                               value="{{ $settings['pharmacy_phone'] ?? '(555) 123-4567' }}" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg shadow-sm hover:shadow transition-all">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
