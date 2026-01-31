<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $query = Sale::with(['product']);

        // 1. Dynamic Search (ID, Cashier Name, or Product Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")  
                  ->orWhere('cashier_name', 'like', "%{$search}%") 
                  ->orWhereHas('product', function($p) use ($search) {
                      $p->where('name', 'like', "%{$search}%");  
                  });
            });
        }

        // 2. Date Range Filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // 3. Stats based on the filtered query
        $totalRevenue = (float)$query->sum('total_amount');
        $todaysSale = (float)Sale::whereDate('created_at', $today)->sum('total_amount');

        // 4. Fetch & Group Data by Minute + Cashier
        $allSales = $query->latest()->get();
        $groupedSales = $allSales->groupBy(function($item) {
            return $item->created_at->format('Y-m-d H:i') . $item->cashier_email;
        });

        // 5. Handle "All" for TXT or 10 for Web UI
        $totalGrouped = $groupedSales->count(); 
        $perPage = ($request->per_page === 'all') ? max(1, $totalGrouped) : 10;
        $currentPage = (int)$request->input('page', 1);
        
        $currentItems = $groupedSales->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $formattedData = $currentItems->map(function ($group) {
            $first = $group->first();
            return [
                'id'             => $first->id, 
                'cashier_name'   => $first->cashier_name,
                'cashier_email'  => $first->cashier_email,
                'total_amount'   => (float)$group->sum('total_amount'),
                'date_formatted' => $first->created_at->format('d M Y, h:i A'),
                'products'       => $group->map(function($s) {
                    return [
                        'name'  => $s->product->name ?? 'N/A',
                        'qty'   => $s->quantity,
                        'price' => $s->quantity > 0 ? ($s->total_amount / $s->quantity) : 0
                    ];
                })->values(),
            ];
        });

        return response()->json([
            'dash' => ['totalRevenue' => $totalRevenue, 'todaysSale' => $todaysSale],
            'orders' => [
                'data' => $formattedData,
                'total' => $totalGrouped,
                'current_page' => $currentPage,
                'last_page' => (int)ceil($totalGrouped / $perPage),
                'per_page' => $perPage
            ],
            'reportDate' => Carbon::now()->format('d M Y, h:i A'),
        ]);
    }
}