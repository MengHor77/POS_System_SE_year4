<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Return dashboard data for admin Vue SPA
     */
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        // 1. Total Revenue (All time)
        $totalRevenue = DB::table('sales')->sum('total_amount') ?? 0;

        // 2. Today's Sale amount
        $todaysSale = DB::table('sales')
            ->whereDate('created_at', $today)
            ->sum('total_amount') ?? 0;

        // 3. This Month amount
        $thisMonthRevenue = DB::table('sales')
            ->whereMonth('created_at', $thisMonth)
            ->whereYear('created_at', $thisYear)
            ->sum('total_amount') ?? 0;

        // 4. This Year amount
        $thisYearRevenue = DB::table('sales')
            ->whereYear('created_at', $thisYear)
            ->sum('total_amount') ?? 0;

        // 5. Best Selling Products (top 5)
        $bestSellingProducts = DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', DB::raw('SUM(sales.quantity) as sales_count'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('sales_count')
            ->limit(5)
            ->get();

        // 6. Recent Sales (latest 10) 
        $recentSales = DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select('sales.id', 'products.name as product_name', 'sales.total_amount', 'sales.created_at')
            ->orderByDesc('sales.created_at')
            ->limit(10)
            ->get()
            ->map(function ($sale) {
                $sale->created_at = Carbon::parse($sale->created_at)->format('Y-m-d H:i');
                return $sale;
            });

        return response()->json([
            'totalRevenue' => (float)$totalRevenue,
            'todaysSale'   => (float)$todaysSale,
            'thisMonth'    => (float)$thisMonthRevenue,
            'thisYear'     => (float)$thisYearRevenue,
            'bestSellingProducts' => $bestSellingProducts,
            'recentSales'      => $recentSales,
        ]);
    }
}