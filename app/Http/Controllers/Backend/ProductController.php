<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ProductController extends Controller
{
    // List all products
 public function index(Request $request)
{
    $perPage = $request->get('per_page', 5);
    $search = $request->get('search'); // the filter value

    // Start query
    $query = Product::query();

    // Apply search filter if exists
    if ($search) {
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%")
              ->orWhere('barcode', 'like', "%{$search}%");
    }

    // Order and paginate
    $products = $query->orderBy('id', 'desc')->paginate($perPage);

    return response()->json($products);
}

    // Show a single product
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
            'brand' => 'required|string|max:255',
            'barcode' => 'required|integer|unique:products,barcode',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        try {
            $product = Product::create($request->all());
            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product,
            ]);
        } catch (QueryException $e) {
            // check for duplicate barcode error
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'message' => 'This barcode already exists. Please enter a new barcode.'
                ], 409); // 409 Conflict
            }
            return response()->json(['message' => 'Failed to save product.'], 500);
        }
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'    => 'sometimes|required|string|max:255',
            'brand'   => 'sometimes|required|string|max:255',
            'barcode' => 'sometimes|required|integer|unique:products,barcode,' . $id,
            'price'   => 'sometimes|required|numeric',
            'stock'   => 'sometimes|required|integer',
        ]);

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