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
            $search = $request->input('search', '');
            $perPage = $request->input('per_page', 5);

            // ទាញយក Relation productSupplier និង product
            $query = PurchaseOrder::with(['productSupplier.product']);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    // ១. ស្វែងរកតាម ID ផ្ទាល់របស់ Purchase Order
                    $q->where('id', 'like', "%{$search}%")
                    // ២. ស្វែងរកតាមឈ្មោះ Supplier នៅក្នុងតារាង Purchase Order
                    ->orWhere('supplier_name', 'like', "%{$search}%")
                    // ៣. ស្វែងរកតាមឈ្មោះ Product ដែលស្ថិតក្នុង Relation ឆ្ងាយ
                    ->orWhereHas('productSupplier.product', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
                });
            }

            // រៀបតាមលំដាប់ថ្មីបំផុត និងធ្វើ Pagination
            $orders = $query->latest()->paginate($perPage);

            // រៀបចំ Format ទិន្នន័យសម្រាប់ផ្ញើទៅ Frontend
            $formattedData = $orders->getCollection()->map(function ($order) {
                return [
                    'id' => $order->id,
                    'supplier_name' => $order->supplier_name,
                    'quantity' => $order->quantity,
                    'status' => $order->status,
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
            'product_supplier_id' => 'required|exists:product_suppliers,id',
            'supplier_name'       => 'required|string|max:255',
            'quantity'            => 'required|integer|min:1',
        ]);

        $order->update($validated);

        return response()->json([
            'message' => 'Purchase order updated successfully',
            'data'    => $order->load('productSupplier.product'),
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
 