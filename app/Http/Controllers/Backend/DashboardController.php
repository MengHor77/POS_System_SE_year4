<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // Total Revenue (sum of total_amount from sales)
        $totalRevenue = DB::table('sales')->sum('total_amount');

        // Today's Sale
        $todaysSale = DB::table('sales')
            ->whereDate('created_at', $today)
            ->sum('total_amount');

        // This Month Revenue
        $thisMonthRevenue = DB::table('sales')
            ->whereMonth('created_at', $thisMonth)
            ->whereYear('created_at', $thisYear)
            ->sum('total_amount');

        // This Year Revenue
        $thisYearRevenue = DB::table('sales')
            ->whereYear('created_at', $thisYear)
            ->sum('total_amount');

        // Pending Shipments
        $pendingShipments = DB::table('shipments')
            ->where('status', 'pending')
            ->count();

        // Notifications (unread)
        $notifications = DB::table('notifications')
            ->where('is_read', 0)
            ->count();

        // Best Selling Products (top 5)
        $bestSellingProducts = DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', DB::raw('SUM(sales.quantity) as sales_count'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('sales_count')
            ->limit(5)
            ->get();

        // Recent Sales (latest 10)
        $recentSales = DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select('sales.id', 'products.name as product_name', 'sales.total_amount', 'sales.created_at')
            ->orderByDesc('sales.created_at')
            ->limit(10)
            ->get();

        return response()->json([
            'totalRevenue' => $totalRevenue,
            'todaysSale' => $todaysSale,
            'thisMonth' => $thisMonthRevenue,
            'thisYear' => $thisYearRevenue,
            'pendingShipments' => $pendingShipments,
            'notifications' => $notifications,
            'bestSellingProducts' => $bestSellingProducts,
            'recentSales' => $recentSales,
        ]);
    }
}
