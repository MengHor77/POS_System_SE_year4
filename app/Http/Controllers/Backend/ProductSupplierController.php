<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProductSupplier;
use Illuminate\Http\Request;

class ProductSupplierController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 5;
        $search = $request->search;

        $query = ProductSupplier::with('product');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('supplier_name', 'like', "%{$search}%")
                ->orWhereHas('product', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }

        $suppliers = $query->paginate($perPage);
        return response()->json($suppliers);
    }

    // Store new supplier
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_name' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
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
       'price' => 'required|numeric|min:0',
    ]);

    $supplier->update($data);
    $supplier->load('product');

    return response()->json([
        'message' => 'Supplier updated',
        'data' => $supplier
    ]);
}

    public function destroy($id)
    {
        ProductSupplier::findOrFail($id)->delete();
        return response()->json(['message' => 'Supplier deleted']);
    }
}