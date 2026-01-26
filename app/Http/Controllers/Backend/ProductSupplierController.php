<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProductSupplier;
use App\Models\Sale;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductSupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // 1. Calculate Stats (Keeping your old logic)
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

        // 2. Query logic (Corrected to ProductSupplier model to match the page intent)
        $query = ProductSupplier::with(['product']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                ->orWhere('supplier_name', 'like', "%{$search}%")
                ->orWhereHas('product', function ($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%");
                });
            });
        }

        $suppliers = $query->orderBy('created_at', 'desc')->paginate(5);

        // 3. Transform data (Kept your transformation style)
        $suppliers->getCollection()->transform(function ($item) {
            return [
                'id'            => $item->id,
                'product_id'    => $item->product_id,
                'product'       => $item->product, // Required for row.product?.name in Vue
                'supplier_name' => $item->supplier_name,
                'quantity'      => $item->quantity,
                'price'         => $item->price,
                'created_at'    => $item->created_at->format('Y-m-d H:i'),
            ];
        });

        // 4. FIX: Merge stats into the main response so Vue can find pagination keys
        return response()->json(array_merge($suppliers->toArray(), [
            'stats' => $stats
        ]));
    }

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
        return response()->json(['message' => 'Supplier updated', 'data' => $supplier->load('product')]);
    }

    public function destroy($id)
    {
        ProductSupplier::findOrFail($id)->delete();
        return response()->json(['message' => 'Supplier deleted']);
    }
}