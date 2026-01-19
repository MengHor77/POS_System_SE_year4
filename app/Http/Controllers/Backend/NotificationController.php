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
        $threshold = 5; // Low stock threshold
        $search = $request->get('search'); // can be barcode or name

        $query = Product::where('stock', '<=', $threshold);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('barcode', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('stock', 'asc')->paginate(5);

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

    // ✅ Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
           'name'    => 'sometimes|required|string|max:255',
            'brand'   => 'sometimes|required|string|max:255',
            'barcode' => 'sometimes|required|integer|unique:products,barcode,' . $id,
            'price'   => 'sometimes|required|numeric',
            'stock'   => 'sometimes|required|integer',
        ]);

        $product->update($validated);

        return response()->json(['message' => 'Product updated successfully']);
    }

    // ✅ Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

}
