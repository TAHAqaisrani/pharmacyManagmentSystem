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

        return view('welcome', compact('medicines'));
    }
}
