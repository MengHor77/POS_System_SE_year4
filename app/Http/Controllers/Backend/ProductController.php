<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ProductController extends Controller
{
  

    public function index(Request $request)
{
    $search = $request->get('search');
    $query = Product::with('category');

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

    if ($request->has('all')) {
        return response()->json($query->orderBy('name', 'asc')->get());
    }

    $perPage = $request->get('per_page', 5);
    $products = $query->orderBy('id', 'desc')->paginate($perPage);

    return response()->json($products);
}
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id', 
            'barcode' => 'required|integer|unique:products,barcode',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        try {
            // 2. Fetch the category name to satisfy the 'category' string column in migration
            $categoryModel = Category::findOrFail($request->category_id);
            // 3. Create the product
            $product = Product::create([
                'name'        => $request->name,
                'category_id' => $request->category_id,
                'category'    => $categoryModel->name, 
                'barcode'     => $request->barcode,
                'price'       => $request->price,
                'stock'       => $request->stock,
            ]);

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product->load('category'), 
            ]);

        } catch (QueryException $e) {
            // Handle Duplicate Barcode (SQL Error 1062)
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'message' => 'This barcode already exists. Please enter a new barcode.'
                ], 409); 
            }
            
            // Log other errors for debugging
            \Log::error($e->getMessage());
            
            return response()->json([
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update product
   public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'category_id' => 'sometimes|required|integer|exists:categories,id',
        'barcode' => 'sometimes|required|integer|unique:products,barcode,' . $id,
        'price' => 'sometimes|required|numeric',
        'stock' => 'sometimes|required|integer',
    ]);

    $data = $request->only(['name', 'category_id', 'barcode', 'price', 'stock']);

    // If category_id is changing, update the 'category' string column too
    if ($request->has('category_id')) {
        $categoryModel = Category::find($request->category_id);
        $data['category'] = $categoryModel->name;
    }

    $product->update($data);

    return response()->json([
        'message' => 'Product updated successfully',
        'product' => $product->load('category'),
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
