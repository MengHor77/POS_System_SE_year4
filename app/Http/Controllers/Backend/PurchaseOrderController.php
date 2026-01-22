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
        $search = $request->search;

        $orders = PurchaseOrder::with('product')
            ->when($search, function ($q) use ($search) {
                $q->where('supplier_name', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($p) use ($search) {
                      $p->where('name', 'like', "%{$search}%");
                  });
            })
            ->latest()
            ->paginate($request->per_page ?? 5);

        return response()->json($orders);
    }

    /**
     * Store purchase order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'supplier_name' => 'required|string|max:255',
            'quantity'      => 'required|integer|min:1',
        ]);

        $order = PurchaseOrder::create($validated);

        return response()->json([
            'message' => 'Purchase order created successfully',
            'data'    => $order->load('product'),
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
