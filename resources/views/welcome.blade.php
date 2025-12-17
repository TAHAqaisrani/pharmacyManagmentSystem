@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen">
    @guest
    <div class="flex items-center justify-center min-h-[calc(100vh-4rem)]">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 mx-4">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Welcome Back</h2>
            
            <form method="POST" action="{{ route('login.submit') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition-colors">
                    Log In
                </button>
            </form>
            
            <p class="mt-6 text-center text-sm text-gray-600">
                Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">Sign up</a>
            </p>
        </div>
    </div>
    @endguest

    @auth
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Available Medicines</h2>
            <p class="mt-4 text-lg text-gray-500">Browse our selection of high-quality healthcare products.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($medicines as $medicine)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group">
                <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-t-2xl bg-gray-100 relative">
                    <!-- Placeholder image since we don't have real photos -->
                    <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2 truncate" title="{{ $medicine->name }}">{{ $medicine->name }}</h3>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-500">{{ $medicine->generic_name }}</span>
                        @php
                            $activeBatch = $medicine->batches->first();
                            $price = $activeBatch->selling_price ?? 0;
                            $hasStock = $medicine->batches->sum('quantity') > 0;
                        @endphp
                        
                        @if($hasStock)
                            <span class="text-lg font-bold text-blue-600">${{ number_format($price, 2) }}</span>
                        @else
                            <span class="text-sm font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded">Out of Stock</span>
                        @endif
                    </div>
                    
                    @if($hasStock)
                    <a href="{{ route('cart.add', $medicine->id) }}" class="block w-full text-center bg-gray-50 hover:bg-blue-600 text-gray-900 hover:text-white font-medium py-3 rounded-xl transition-colors border border-gray-200 hover:border-blue-600">
                        Add to Cart
                    </a>
                    @else
                    <button disabled class="block w-full text-center bg-gray-100 text-gray-400 font-medium py-3 rounded-xl cursor-not-allowed border border-gray-200">
                        Unavailable
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endauth
</div>
@endsection
