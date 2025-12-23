<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if regular customer or high value customer
            $user = Auth::user();
            $completedOrders = Order::where('user_id', $user->id)
                                    ->where('status', 'completed')
                                    ->count();
            
            $totalSpent = Order::where('user_id', $user->id)
                               ->where('status', 'completed')
                               ->sum('total_amount');

            // Thresholds: > 5 orders OR > $1000 spent
            if ($completedOrders > 5 || $totalSpent > 1000) {
                session(['discount_token' => [
                    'code' => 'LOYALTY10',
                    'rate' => 0.10, // 10%
                    'min_spend' => 0
                ]]);
                
                return redirect()->intended('/')->with('success', 'Welcome back! You have been awarded a 10% Loyalty Discount on your next order!');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
