<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the sales with search and pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');  
        $endDate = $request->input('end_date');
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
        
    // --- filtr date time   Add Carbon parsing to include the full day (00:00:00 to 23:59:59) ---
    if (!empty($startDate) && !empty($endDate)) {
        $query->whereBetween('created_at', [
            \Carbon\Carbon::parse($startDate)->startOfDay(), 
            \Carbon\Carbon::parse($endDate)->endOfDay()
        ]);
    } elseif (!empty($startDate)) {
        $query->where('created_at', '>=', \Carbon\Carbon::parse($startDate)->startOfDay());
    } elseif (!empty($endDate)) {
        $query->where('created_at', '<=', \Carbon\Carbon::parse($endDate)->endOfDay());
    }
       
        $sales = $query->orderBy('created_at', 'desc')->paginate(5);

        // Transform the data inside the paginator
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

        return response()->json($sales);
    }

  
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
        ]);

        $cashier = Auth::user();

        try {
            DB::beginTransaction();

            foreach ($request->items as $item) {
                // Find product to get its category_id and current stock
                $product = Product::findOrFail($item['id']);

                if ($product->stock < $item['qty']) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }

                // 1. Create the Sale record including category_id
                Sale::create([
                    'product_id'    => $product->id,
                    'category_id'   => $product->category_id, 
                    'cashier_id'    => $cashier->id,
                    'cashier_name'  => $cashier->name,
                    'cashier_email' => $cashier->email,
                    'quantity'      => $item['qty'],
                    'total_amount'  => $item['price'] * $item['qty'],
                ]);

                // 2. Reduce Product Stock
                $product->decrement('stock', $item['qty']);
            }

            DB::commit();
            return response()->json(['message' => 'Sale recorded successfully!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}