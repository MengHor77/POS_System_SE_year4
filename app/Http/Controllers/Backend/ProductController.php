<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ProductController extends Controller
{
    // List all products with search & pagination
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $search = $request->get('search'); 

        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
        }

        $products = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json($products);
    }

    // Show single product
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    // Store a new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', 
            'barcode' => 'required|integer|unique:products,barcode',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        try {
            // Get category name from category_id
            $categoryName = Category::findOrFail($request->category_id)->name;

            $product = Product::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'category' => $categoryName,
                'barcode' => $request->barcode,
                'price' => $request->price,
                'stock' => $request->stock,
            ]);

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product,
            ]);

        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'message' => 'This barcode already exists. Please enter a new barcode.'
                ], 409); 
            }
            return response()->json(['message' => 'Failed to save product.'], 500);
        }
    }

    // Update existing product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'barcode' => 'sometimes|required|integer|unique:products,barcode,' . $id,
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        // If category_id is changed, update category name too
        if ($request->has('category_id')) {
            $request->merge([
                'category' => Category::findOrFail($request->category_id)->name
            ]);
        }

        $product->update($request->all());

        return response()->json([
            'message' => 'Product updated successfully'
        ]);
    }

    // Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
