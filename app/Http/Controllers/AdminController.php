<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Medicine;
use App\Models\Supplier;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $medicineCount = Medicine::count();
        $supplierCount = Supplier::count();
        $totalStock = Batch::sum('quantity');
        $expiringSoon = Batch::expiringSoon()->count();
        $expired = Batch::expired()->count();

        // New Sales Stats
        $today = now()->toDateString();
        $todaysSales = \App\Models\Invoice::whereDate('invoice_date', $today)->sum('total');
        $monthlyRevenue = \App\Models\Invoice::whereMonth('invoice_date', now()->month)->sum('total');
        
        // Chart Data (Last 7 Days)
        $salesData = \App\Models\Invoice::selectRaw('DATE(invoice_date) as date, SUM(total) as total')
            ->whereDate('invoice_date', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        return view('admin.dashboard', compact(
            'medicineCount',
            'supplierCount',
            'totalStock',
            'expiringSoon',
            'expired',
            'todaysSales',
            'monthlyRevenue',
            'salesData'
        ));
    }
}

