<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class PurchaseOrderController extends Controller
{
    /**
     * List purchase orders (with search & pagination)
     */
 public function index(Request $request)
{
    $orders = PurchaseOrder::with(['productSupplier.product'])->latest()->get();

    // Transform for Vue
    $data = $orders->map(function ($order) {
        return [
            'id' => $order->id,
            'supplier_name' => $order->supplier_name,
            'quantity' => $order->quantity,
            'status' => $order->status,
            'product' => [
                'id' => $order->product->id ?? null,
                'name' => $order->product->name ?? '',
            ],
        ];
    });

    return response()->json($data);
}


    /**
     * Store purchase order
     */
    public function store(Request $request)
    {
            $validated = $request->validate([
            'product_supplier_id' => 'required|exists:product_suppliers,id',
            'supplier_name'       => 'required|string|max:255',
            'quantity'            => 'required|integer|min:1',
        ]);

        $order = PurchaseOrder::create($validated);

        return response()->json([
            'message' => 'Purchase order created successfully',
            'data' => $order->load('productSupplier.product'),
        ], 201);
    }

    /**
     * Update purchase order
     */
    public function update(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);

        $validated = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'supplier_name' => 'required|string|max:255',
            'quantity'      => 'required|integer|min:1',
        ]);

        $order->update($validated);

        return response()->json([
            'message' => 'Purchase order updated successfully',
            'data'    => $order->load('product'),
        ]);
    }

    /**
     * Delete purchase order
     */
    public function destroy($id)
    {
        PurchaseOrder::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Purchase order deleted successfully',
        ]);
    }
}
 