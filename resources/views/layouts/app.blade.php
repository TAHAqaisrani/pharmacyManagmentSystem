<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pharmacy Management System</title>
    
    {{-- Include compiled assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-white text-gray-800 antialiased flex flex-col min-h-screen">
    
    {{-- Main Navigation Bar --}}
    <nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                {{-- Logo / Brand --}}
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="text-2xl font-bold text-blue-600 tracking-tight">PHARMA<span class="text-slate-800">CARE</span></a>
                    </div>
                </div>

                {{-- Right Side Buttons --}}
                <div class="flex items-center space-x-6">
                    
                    {{-- Cart Link with Badge --}}
                    <a href="{{ route('cart.index') }}" class="group flex items-center p-2 text-gray-400 hover:text-blue-600 transition-colors relative">
                        <!-- Shopping Bag Icon -->
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-gray-800">Cart</span>
                        
                        {{-- Show Cart Count if items exist in session --}}
                        @if(session('cart'))
                        <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                            {{ count(session('cart')) }}
                        </span>
                        @endif
                    </a>

                    {{-- Authentication Links --}}
                    {{-- @auth checks if the user is currently logged in --}}
                    @auth
                        <span class="text-sm font-medium text-gray-600">Hello, {{ Auth::user()->name }}</span>
                        
                        {{-- Logout Form --}}
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">Logout</button>
                        </form>
                    
                    {{-- @else block runs if the user is NOT logged in --}}
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">Log in</a>
                        <a href="{{ route('admin.login') }}" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">Admin Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">Sign up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content Injection Point --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-white py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-400">&copy; {{ date('Y') }} PharmaCare System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
