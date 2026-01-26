<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\PurchaseOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Logic for Stats (Total stays the same even when searching)
        $totalRevenue = Sale::sum('total_amount') ?? 0;
        $pendingShipments = PurchaseOrder::where('status', 'pending')->count();
        $lowStockCount = Product::where('stock', '<', 10)->count();

        // 2. Build Query for Table with Search
        $query = PurchaseOrder::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('supplier_name', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        // 3. Paginate the results (10 per page)
        $orders = $query->latest()->paginate(5);

        return response()->json([
            'dash'   => [
                'totalRevenue'     => $totalRevenue,
                'pendingShipments' => $pendingShipments,
            ],
            'notify' => [
                'total' => $lowStockCount,
            ],
            'orders' => $orders, 
            'reportDate' => Carbon::now()->format('d M Y, h:i A'),
        ]);
    }
}