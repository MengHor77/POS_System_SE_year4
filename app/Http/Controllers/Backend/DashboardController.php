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

        $totalRevenue = DB::table('sales')->sum('total_amount') ?? 0;
        $todaysSale = DB::table('sales')->whereDate('created_at', $today)->sum('total_amount') ?? 0;
        $thisMonthRevenue = DB::table('sales')->whereMonth('created_at', $thisMonth)->whereYear('created_at', $thisYear)->sum('total_amount') ?? 0;
        $thisYearRevenue = DB::table('sales')->whereYear('created_at', $thisYear)->sum('total_amount') ?? 0;

        // Get Monthly Sales for the WHOLE YEAR
        $monthlySales = DB::table('sales')
            ->select(
                DB::raw("MONTH(created_at) as month_num"),
                DB::raw("DATE_FORMAT(created_at, '%b') as month_name"),
                DB::raw("SUM(total_amount) as total")
            )
            ->whereYear('created_at', $thisYear)
            ->groupBy('month_num', 'month_name')
            ->orderBy('month_num', 'ASC')
            ->get();

        // Ensure all 12 months exist even if 0 sales (Optional but makes graph look better)
        $labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $values = array_fill(0, 12, 0);

        foreach ($monthlySales as $sale) {
            $values[$sale->month_num - 1] = (float)$sale->total;
        }

        // Keep your old code for other data
        $bestSellingProducts = DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id', 'products.name', 'categories.name as category_name', DB::raw('SUM(sales.quantity) as sales_count'))
            ->groupBy('products.id', 'products.name', 'categories.name')
            ->orderByDesc('sales_count')->limit(5)->get();

        $recentSales = DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select('sales.id', 'products.name as product_name', 'sales.total_amount', 'sales.created_at')
            ->orderByDesc('sales.created_at')->limit(10)->get()
            ->map(function ($sale) {
                $sale->created_at = Carbon::parse($sale->created_at)->format('Y-m-d H:i');
                return $sale;
            });

        $pendingShipments = DB::table('purchase_orders')
            ->where('status', '!=', 'received')->orWhereNull('status')->count();

        return response()->json([
            'totalRevenue' => (float)$totalRevenue,
            'todaysSale'   => (float)$todaysSale,
            'thisMonth'    => (float)$thisMonthRevenue,
            'thisYear'     => (float)$thisYearRevenue,
            'pendingShipments'    => $pendingShipments, 
            'bestSellingProducts' => $bestSellingProducts,
            'recentSales'      => $recentSales,
            'chartLabels' => $labels, 
            'chartValues' => $values,
        ]);
    }
}