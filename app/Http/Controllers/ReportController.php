<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dailySales(Request $request)
    {
        $date = $request->filled('date') ? $request->date : now()->toDateString();
        $total = Invoice::whereDate('invoice_date', $date)->sum('total');
        $invoices = Invoice::whereDate('invoice_date', $date)->orderBy('id', 'desc')->get();
        return view('reports.daily_sales', compact('date', 'total', 'invoices'));
    }

    public function topMedicines(Request $request)
    {
        $days = (int) ($request->input('days') ?? 30);
        $from = now()->subDays($days)->toDateString();
        $top = InvoiceItem::select('medicine_id', DB::raw('SUM(quantity) as qty'))
            ->whereHas('invoice', function ($q) use ($from) {
                $q->whereDate('invoice_date', '>=', $from);
            })
            ->groupBy('medicine_id')
            ->orderByDesc('qty')
            ->with('medicine')
            ->limit(10)
            ->get();
        return view('reports.top_medicine', compact('days', 'top'));
    }
}

