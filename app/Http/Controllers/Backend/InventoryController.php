<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 4);

        $products = Product::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
                    
                    // Optional: If user types "low", show items under threshold
                    if (strtolower($search) === 'low') {
                        $q->orWhere('stock', '<=', 5);
                    }
                });
            })
            ->orderBy('stock', 'asc') // Keeps low stock at the top
            ->paginate($perPage);

        return response()->json($products);
    }

    public function stockIn(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $id) {
            $product = Product::findOrFail($id);

            // Increase stock
            $product->increment('stock', $request->qty);

             
        });

        return response()->json([
            'message' => 'Stock added successfully',
        ]);
    }

    /**
     * Stock Out (manual decrease)
     * Used for damaged, lost, expired items
     */
    public function stockOut(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
            'note' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request, $id) {
            $product = Product::findOrFail($id);

            if ($product->stock < $request->qty) {
                abort(400, 'Insufficient stock');
            }

            // Decrease stock
            $product->decrement('stock', $request->qty);

            // OPTIONAL: inventory log table (recommended)
            // DB::table('inventory_logs')->insert([
            //     'product_id' => $product->id,
            //     'type' => 'OUT',
            //     'qty' => $request->qty,
            //     'note' => $request->note,
            //     'created_at' => now(),
            // ]);
        });

        return response()->json([
            'message' => 'Stock reduced successfully',
        ]);
    }

    /**
     * Get low stock products (used by notification system)
     */
    public function lowStock()
    {
        $threshold = 5;

        $products = Product::where('stock', '<=', $threshold)
            ->orderBy('stock', 'asc')
            ->get();

        return response()->json([
            'total' => $products->count(),
            'data' => $products,
        ]);
    }
}
