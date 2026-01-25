<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class PurchaseOrderController extends Controller
{
    
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 5);

        $query = PurchaseOrder::with(['productSupplier.product']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                // Search by PO ID, Supplier Name, or Product Name
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('supplier_name', 'like', "%{$search}%")
                  ->orWhereHas('productSupplier.product', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate($perPage);

        // Transform data to match your Vue <template #cell-product="{ row }">
        $formattedData = $orders->getCollection()->map(function ($order) {
            return [
                'id' => $order->id,
                'supplier_name' => $order->supplier_name,
                'quantity' => $order->quantity,
                'status' => $order->status ?? 'pending',
                'product_supplier_id' => $order->product_supplier_id,
                'product' => [
                    'id' => $order->productSupplier->product->id ?? null,
                    'name' => $order->productSupplier->product->name ?? 'N/A',
                ],
            ];
        });

        return response()->json([
            'data' => $formattedData,
            'current_page' => $orders->currentPage(),
            'last_page' => $orders->lastPage(),
            'per_page' => $orders->perPage(),
            'total' => $orders->total(),
        ]);
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_supplier_id' => 'required|exists:product_suppliers,id',
            'supplier_name'       => 'required|string|max:255',
            'quantity'            => 'required|integer|min:1',
        ]);

        $order = PurchaseOrder::create($validated);

        return response()->json([
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);
    }

   
    public function update(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);

        $validated = $request->validate([
            'product_supplier_id' => 'required|exists:product_suppliers,id',
            'supplier_name'       => 'required|string|max:255',
            'quantity'            => 'required|integer|min:1',
        ]);

        $order->update($validated);

        return response()->json([
            'message' => 'Order updated successfully',
            'data' => $order
        ]);
    }

    public function destroy($id)
    {
        PurchaseOrder::findOrFail($id)->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}