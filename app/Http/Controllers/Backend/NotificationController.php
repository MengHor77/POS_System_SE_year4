<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function lowStock(Request $request)
    {
        $search = $request->get('search');
        $threshold = 5;

        $query = Product::with('category')->where('stock', '<=', $threshold);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search) 
                  ->orWhere('name', 'like', "%{$search}%") 
                  ->orWhere('barcode', 'like', "%{$search}%") 
                  ->orWhereHas('category', function ($catQuery) use ($search) {
                      $catQuery->where('name', 'like', "%{$search}%"); 
                  });
            });
        }

        $perPage = $request->get('per_page', 5);
        $products = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json($products);
    }

    public function lowStockCount()
    {
        $threshold = 5;
        $total = Product::where('stock', '<=', $threshold)->count();
        return response()->json(['total' => $total]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required', 
            'barcode' => 'sometimes|required|integer|unique:products,barcode,' . $id,
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer',
        ]);

        $data = $request->only(['name', 'barcode', 'price', 'stock']);

        if ($request->has('category')) {
            $categoryModel = Category::find($request->category);
            if ($categoryModel) {
                $data['category_id'] = $categoryModel->id;
                $data['category'] = $categoryModel->name;  
            }
        }

        $product->update($data);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->load('category'),
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}