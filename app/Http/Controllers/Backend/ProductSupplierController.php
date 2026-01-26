<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProductSupplier;
use Illuminate\Http\Request;

class ProductSupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // 1. Calculate Stats (Calculate these BEFORE filtering/paginating)
        $today = \Carbon\Carbon::today();
        $thisMonth = \Carbon\Carbon::now()->month;
        $thisYear = \Carbon\Carbon::now()->year;

        $stats = [
            'totalRevenue' => DB::table('sales')->sum('total_amount') ?? 0,
            'todaysSale'   => DB::table('sales')->whereDate('created_at', $today)->sum('total_amount') ?? 0,
            'thisMonth'    => DB::table('sales')->whereMonth('created_at', $thisMonth)
                                                ->whereYear('created_at', $thisYear)
                                                ->sum('total_amount') ?? 0,
            'thisYear'     => DB::table('sales')->whereYear('created_at', $thisYear)->sum('total_amount') ?? 0,
        ];

        // 2. Your existing Search/Query logic
        $query = Sale::with(['product', 'category']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                ->orWhere('cashier_name', 'like', "%{$search}%")
                ->orWhereHas('product', function ($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%");
                });
            });
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(5);

        // 3. Transform data for the table
        $sales->getCollection()->transform(function ($sale) {
            return [
                'id'            => $sale->id,
                'product_id'    => $sale->product_id,
                'product_name'  => $sale->product->name ?? 'N/A',
                'category'      => $sale->category->name ?? 'N/A',
                'unit_price'    => $sale->quantity > 0 ? ($sale->total_amount / $sale->quantity) : 0,
                'quantity'      => $sale->quantity,
                'total_amount'  => $sale->total_amount,
                'cashier_name'  => $sale->cashier_name,
                'cashier_email' => $sale->cashier_email,
                'created_at'    => $sale->created_at->format('Y-m-d H:i'),
            ];
        });

        // 4. IMPORTANT: Return both the sales and the stats
        return response()->json([
            'sales_data' => $sales,
            'stats' => $stats
        ]);
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