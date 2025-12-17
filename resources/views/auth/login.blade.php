<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Customer Portal</title>
    
    {{-- Include compiled assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Welcome Back</h2>
        
        {{-- Login Form --}}
        <form method="POST" action="{{ route('login.submit') }}" class="space-y-6">
            {{-- Security Token --}}
            @csrf
            
            {{-- Email Field --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                {{-- Error Message for Email --}}
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            {{-- Password Field --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>
            
            {{-- Submit Button --}}
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition-colors">
                Log In
            </button>
        </form>
        
        <p class="mt-6 text-center text-sm text-gray-600">
            Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">Sign up</a>
        </p>
    </div>
</body>
</html>
