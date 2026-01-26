<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Product; 
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource with search, status, and date range filtering.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 5);
        $status = $request->input('status', '');
        
        // --- NEW: Capture Date Range Inputs ---
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = PurchaseOrder::with(['productSupplier.product']);

        // Filter by Status
        if ($status !== '' && $status !== null) {
            $query->where('status', $status);
        }

        // --- NEW: Date Range Filtering Logic ---
        if ($startDate && $endDate) {
            // Parses the datetime-local format and ensures proper comparison
            $query->whereBetween('created_at', [
                Carbon::parse($startDate), 
                Carbon::parse($endDate)
            ]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', Carbon::parse($startDate));
        } elseif ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate));
        }

        // Search logic (ID, Supplier, or Product Name)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('supplier_name', 'like', "%{$search}%")
                  ->orWhereHas('productSupplier.product', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate($perPage);

        // Transform data for the Vue Table
        $formattedData = $orders->getCollection()->map(function ($order) {
            return [
                'id'            => $order->id,
                'product_supplier_id' => $order->product_supplier_id, 
                'supplier_name' => $order->supplier_name,
                'quantity'      => $order->quantity,
                'status'        => $order->status ?? 'pending',
                'created_at'    => $order->created_at->format('Y-m-d H:i'),
                'product'       => [
                    'id'   => $order->productSupplier->product->id ?? null,
                    'name' => $order->productSupplier->product->name ?? 'N/A',
                ],
            ];
        });

        return response()->json([
            'data'         => $formattedData,
            'current_page' => $orders->currentPage(),
            'last_page'    => $orders->lastPage(),
            'per_page'     => $orders->perPage(),
            'total'        => $orders->total(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_supplier_id' => 'required|exists:product_suppliers,id',
            'supplier_name'       => 'required|string|max:255',
            'quantity'            => 'required|integer|min:1',
        ]);

        $validated['status'] = 'pending';

        $order = PurchaseOrder::create($validated);

        return response()->json([
            'message' => 'Order created successfully',
            'data'    => $order
        ], 201);
    }

    /**
     * Update the specified resource in storage.
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
            'message' => 'Order updated successfully',
            'data'    => $order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        PurchaseOrder::findOrFail($id)->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }

    /**
     * Process receiving of the order and increment product stock.
     */
    public function receive($id)
    {
        try {
            DB::beginTransaction();

            $order = PurchaseOrder::with('productSupplier.product')->findOrFail($id);

            if ($order->status === 'received') {
                return response()->json(['message' => 'Order already received.'], 422);
            }

            $product = $order->productSupplier->product;

            if (!$product) {
                throw new \Exception("Linked product not found.");
            }

            // Update Product Stock
            $product->increment('stock', $order->quantity);

            // Update Order Status
            $order->update([
                'status'      => 'received',
                'received_at' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'Stock updated and order marked as received!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}