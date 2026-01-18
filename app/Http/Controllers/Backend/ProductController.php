<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // List all products
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    // Store a new product
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'brand'   => 'required|string|max:255',
            'barcode' => 'required|integer|unique:products,barcode',
            'price'   => 'required|numeric',
            'stock'   => 'required|integer',
        ]);

        $product = Product::create($request->all());

        return response()->json($product, 201);
    }

    // Show a single product
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    // Update a product
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

        return response()->json($product);
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
