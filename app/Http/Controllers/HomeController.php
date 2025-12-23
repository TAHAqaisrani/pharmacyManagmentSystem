<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get all medicines. We still load active batches for price calculation if available.
        $medicines = Medicine::with(['batches' => function($q) {
            $q->where('quantity', '>', 0)
              ->where(function($query) {
                  $query->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>', now());
              });
        }])->get();

        // Removed the filter that hides medicines with 0 quantity so customers can see full catalog

        $coupon = null;
        if (auth()->check()) {
            $user = auth()->user();
            // Calculate current user's total confirmed purchases
            $myTotal = \App\Models\Order::where('user_id', $user->id)
                ->where('status', 'completed')
                ->sum('total_amount');

            // Calculate max total of any other customer
            $maxOther = \App\Models\Order::where('user_id', '!=', $user->id)
                ->where('status', 'completed')
                ->selectRaw('user_id, sum(total_amount) as total')
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->first();
            
            $maxOtherTotal = $maxOther ? $maxOther->total : 0;

            // If my total is strictly greater than the next best (or everyone else), they get a coupon
            if ($myTotal > $maxOtherTotal && $myTotal > 0) {
                $coupon = 'REWARD-' . strtoupper(\Illuminate\Support\Str::random(6));
            }
        }

        return view('welcome', compact('medicines', 'coupon'));
    }
}
