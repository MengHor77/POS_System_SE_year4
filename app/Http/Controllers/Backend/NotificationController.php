<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Get low-stock products with optional barcode filter
    public function lowStock(Request $request)
    {
        $threshold = 5; // Set low stock threshold
        $barcode = $request->get('barcode');

        $query = Product::where('stock', '<=', $threshold);

        if ($barcode) {
            $query->where('barcode', 'like', "%$barcode%");
        }

        $products = $query->orderBy('stock', 'asc')->paginate(10);

        return response()->json([
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
        ]);
    }

    // Get total low-stock count for sidebar badge
    public function lowStockCount()
    {
        $threshold = 5;
        $total = Product::where('stock', '<=', $threshold)->count();

        return response()->json([
            'total' => $total
        ]);
    }
}
