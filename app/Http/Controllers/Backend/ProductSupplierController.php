<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProductSupplier;
use Illuminate\Http\Request;

class ProductSupplierController extends Controller
{
    // List suppliers (paginated)
    public function index(Request $request)
    {
        $search = $request->search;

    $suppliers = ProductSupplier::with('product')
        ->when($search, fn($q) => $q->where('supplier_name', 'like', "%$search%"))
        ->get(); // or paginate($request->per_page ?? 5)

    // Ensure product data is included
    return response()->json([
        'data' => $suppliers
    ]);
    }

    // Store new supplier
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_name' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
        ]);

        ProductSupplier::create($data);

        return response()->json(['message' => 'Supplier created']);
    }

    // Update existing supplier
    public function update(Request $request, $id)
    {
        $supplier = ProductSupplier::findOrFail($id);

        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_name' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
        ]);

        $supplier->update($data);

        return response()->json(['message' => 'Supplier updated']);
    }

    // Delete supplier
    public function destroy($id)
    {
        ProductSupplier::findOrFail($id)->delete();
        return response()->json(['message' => 'Supplier deleted']);
    }
}
