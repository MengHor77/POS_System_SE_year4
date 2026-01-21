<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProductSupplier;
use Illuminate\Http\Request;

class ProductSupplierController extends Controller
{
    // List
    public function index(Request $request)
    {
        $search = $request->search;

        $suppliers = ProductSupplier::with('product')
            ->when($search, function ($q) use ($search) {
                $q->where('supplier_name', 'like', "%$search%");
            })
            ->paginate($request->per_page ?? 5);

        return response()->json($suppliers);
    }

    // Store
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

    // Update
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

    // Delete
    public function destroy($id)
    {
        ProductSupplier::findOrFail($id)->delete();
        return response()->json(['message' => 'Supplier deleted']);
    }
}
