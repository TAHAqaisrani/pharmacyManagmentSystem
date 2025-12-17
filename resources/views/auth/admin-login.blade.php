<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Pharmacy Manager</title>
    
    {{-- Include compiled assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="px-8 py-10">
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-bold text-slate-800">Admin Login</h1>
                    <p class="text-slate-500 mt-2">Sign in to manage inventory and sales</p>
                </div>

                {{-- Admin Login Form --}}
                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                    @csrf
                    
                    {{-- Email Field --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" required autofocus
                            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                            placeholder="admin@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Field --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                        Sign In
                    </button>
                </form>
            </div>
            
            {{-- Footer Links --}}
            <div class="bg-slate-50 px-8 py-4 border-t border-slate-100 text-center">
                <a href="{{ url('/') }}" class="text-sm text-blue-600 hover:underlin font-medium">Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
