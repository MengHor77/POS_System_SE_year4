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
    public function index()
    {
        // We load the product and the category relationship
        $sales = Sale::with(['product', 'category']) 
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($sales->map(function($sale) {
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
        }));
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
                    'category_id'   => $product->category_id, // Important: Linking category
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