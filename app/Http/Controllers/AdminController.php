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

        $medicines = Medicine::select('id', 'name')->orderBy('name')->get();

        return view('admin.dashboard', compact(
            'medicineCount',
            'supplierCount',
            'totalStock',
            'expiringSoon',
            'expired',
            'todaysSales',
            'monthlyRevenue',
            'salesData',
            'medicines'
        ));
    }

    public function salesChartData(Request $request)
    {
        // Default: Last 7 days
        $startDate = now()->subDays(6);
        $endDate = now();

        $query = \App\Models\InvoiceItem::selectRaw('DATE(invoices.invoice_date) as date, SUM(invoice_items.subtotal) as total')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereDate('invoices.invoice_date', '>=', $startDate)
            ->whereDate('invoices.invoice_date', '<=', $endDate);

        if ($request->filled('medicine_id')) {
            $query->where('invoice_items.medicine_id', $request->medicine_id);
        }

        $data = $query->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();
        
        // Fill missing dates with 0
        $formattedData = [];
        for ($i = 0; $i <= 6; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $formattedData[$date] = $data[$date] ?? 0;
        }

        // If no filter selected, use the broader Invoice query for accuracy on "All Medicines" 
        // (handles extra fees/discounts not per item if any exist on Invoice level, though here assuming simple sum)
        if (!$request->filled('medicine_id')) {
            $data = \App\Models\Invoice::selectRaw('DATE(invoice_date) as date, SUM(total) as total')
                ->whereDate('invoice_date', '>=', $startDate)
                ->whereDate('invoice_date', '<=', $endDate)
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date')
                ->toArray();
            
            $formattedData = [];
            for ($i = 0; $i <= 6; $i++) {
                $date = $startDate->copy()->addDays($i)->toDateString();
                $formattedData[$date] = $data[$date] ?? 0;
            }
        }

        return response()->json($formattedData);
    }
}

