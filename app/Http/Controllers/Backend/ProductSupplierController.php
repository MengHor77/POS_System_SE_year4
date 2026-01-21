<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProductSupplier;
use Illuminate\Http\Request;

class ProductSupplierController extends Controller
{
    public function index()
    {
        $suppliers = ProductSupplier::with('product')->get();
        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ]);

        $supplier = ProductSupplier::create($request->all());

        return response()->json([
            'message' => 'Supplier added successfully',
            'supplier' => $supplier,
        ]);
    }

    public function update(Request $request, $id)
    {
        $supplier = ProductSupplier::findOrFail($id);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ]);

        $supplier->update($request->all());

        return response()->json($supplier);
    }

    public function destroy($id)
    {
        $supplier = ProductSupplier::findOrFail($id);
        $supplier->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
