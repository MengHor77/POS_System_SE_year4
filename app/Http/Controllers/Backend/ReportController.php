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
        $totalRevenue = Sale::sum('total_amount') ?? 0;
        $todaysSale = Sale::whereDate('created_at', $today)->sum('total_amount') ?? 0;

         $query = Sale::with(['product']);

         if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")  
                  ->orWhere('cashier_email', 'like', "%{$search}%")  
                  ->orWhere('cashier_name', 'like', "%{$search}%") 
                  ->orWhereHas('product', function($p) use ($search) {
                      $p->where('name', 'like', "%{$search}%");  
                  });
            });
        }
 
        $sales = $query->latest()->get()->groupBy(function($item) {
            return $item->created_at->format('Y-m-d H:i:s') . $item->cashier_email;
        });

         $currentPage = $request->input('page', 1);
        $perPage = 10;
        $currentItems = $sales->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $formattedData = $currentItems->map(function ($group) {
            $first = $group->first();
            return [
                'id'             => $first->id, 
                'cashier_name'   => $first->cashier_name,
                'cashier_email'  => $first->cashier_email,
                'products'       => $group->map(function($s) {
                    return [
                        'name' => $s->product->name ?? 'N/A',
                        'qty'  => $s->quantity,
                        'price'=> $s->quantity > 0 ? ($s->total_amount / $s->quantity) : 0
                    ];
                }),
                'total_amount'   => $group->sum('total_amount'),
                'date_formatted' => $first->created_at->format('d M Y, h:i A'),
            ];
        });

        return response()->json([
            'dash' => [
                'totalRevenue' => $totalRevenue,
                'todaysSale'   => $todaysSale,
            ],
            'orders' => [
                'data' => $formattedData,
                'total' => $sales->count(),
                'current_page' => $currentPage,
                'last_page' => ceil($sales->count() / $perPage),
                'per_page' => $perPage
            ],
            'reportDate' => Carbon::now()->format('d M Y, h:i A'),
        ]);
    }

     public function getTransactionReport(Request $request)
    {
        $search = $request->input('search');
        $query = Sale::with(['product']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                ->orWhere('cashier_email', 'like', "%{$search}%")
                ->orWhere('cashier_name', 'like', "%{$search}%")
                ->orWhereHas('product', function ($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%");
                });
            });
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(10);

        $sales->getCollection()->transform(function ($sale) {
            return [
                'id'             => $sale->id,
                'cashier_id'     => $sale->cashier_id,
                'cashier_email'  => $sale->cashier_email,
                'cashier_name'   => $sale->cashier_name,
                'product_name'   => $sale->product->name ?? 'N/A',
                'unit_price'     => $sale->quantity > 0 ? ($sale->total_amount / $sale->quantity) : 0,
                'quantity'       => $sale->quantity,
                'total_amount'   => $sale->total_amount,
                'date_formatted' => $sale->created_at->format('d-m-Y H:i:s'),
            ];
        });

        return response()->json([
            'orders' => $sales,
            'dash' => [
                'totalRevenue' => Sale::sum('total_amount'),
                'pendingShipments' => 0,
            ],
            'reportDate' => now()->format('d-m-Y H:i:s')
        ]);
    }
}