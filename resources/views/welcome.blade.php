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
        @if(isset($coupon) && $coupon)
        <div class="mb-16 rounded-3xl bg-indigo-900 p-8 text-white shadow-2xl relative overflow-hidden text-center border-4 border-indigo-200" style="background-color: #312e81;">
            <!-- Decorative circles -->
            <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-indigo-600 rounded-full opacity-30 blur-3xl" style="background-color: #4f46e5;"></div>
            <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-64 h-64 bg-purple-600 rounded-full opacity-30 blur-3xl" style="background-color: #9333ea;"></div>
            
            <div class="relative z-10 max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 bg-indigo-800 text-yellow-300 px-4 py-1.5 rounded-full mb-6 font-semibold text-sm border border-indigo-700 shadow-sm" style="background-color: #3730a3; color: #fde047; border-color: #4338ca;">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    VIP REWARD UNLOCKED
                </div>
                
                <h2 class="text-4xl md:text-5xl font-black mb-4 tracking-tight leading-tight" style="color: #ffffff;">
                    Congratulations! <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-200 to-amber-400" style="background: linear-gradient(to right, #fef08a, #fbbf24); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">You're Our Top Buyer</span>
                </h2>
                
                <p class="text-indigo-200 text-lg mb-8 leading-relaxed max-w-xl mx-auto" style="color: #c7d2fe;">
                    You've shopped more than anyone else this month! Here is an exclusive discount code just for you.
                </p>
                
                <div class="bg-white rounded-xl p-2 inline-flex items-center shadow-lg transform transition hover:scale-105 duration-200 max-w-full" style="background-color: #ffffff;">
                    <div class="px-6 py-3 bg-gray-50 rounded-lg border border-gray-100 border-dashed border-2 mr-2" style="background-color: #f9fafb; border-color: #f3f4f6;">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest text-left" style="color: #9ca3af;">Coupon Code</span>
                        <span class="block text-3xl font-mono font-bold text-indigo-900 tracking-wider" style="color: #312e81;">{{ $coupon }}</span>
                    </div>
                    <button onclick="copyCoupon(this, '{{ $coupon }}')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-8 rounded-lg transition-colors shadow-md whitespace-nowrap" style="background-color: #4f46e5; color: #ffffff;">
                        Copy Code
                    </button>
                </div>
            </div>
        </div>

        <script>
            function copyCoupon(btn, code) {
                navigator.clipboard.writeText(code).then(function() {
                    let originalText = btn.innerHTML;
                    btn.innerHTML = 'Copied!';
                    btn.classList.add('bg-green-600', 'hover:bg-green-700');
                    btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                        btn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                    }, 2000);
                });
            }
        </script>
        @endif

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
