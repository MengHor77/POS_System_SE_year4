<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class SaleController extends Controller
{
    public function index()
    {
        // Eager load product and cashier for performance
        $sales = Sale::with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($sales->map(function($sale) {
            return [
                'id' => $sale->id,
                'product_name' => $sale->product->name ?? 'N/A',
                'cashier_name' => $sale->cashier_name, // Direct from sales table
                'cashier_email' => $sale->cashier_email, // Direct from sales table
                'quantity' => $sale->quantity,
                'total_amount' => $sale->total_amount,
                'created_at' => $sale->created_at->format('Y-m-d H:i'),
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
            // 1. Create the Sale record
            Sale::create([
                'product_id'    => $item['id'],
                'cashier_id'    => $cashier->id,
                'cashier_name'  => $cashier->name,
                'cashier_email' => $cashier->email,
                'quantity'      => $item['qty'],
                'total_amount'  => $item['price'] * $item['qty'],
            ]);

            // 2. Reduce Product Stock
            $product = Product::find($item['id']);
            if ($product->stock < $item['qty']) {
                throw new \Exception("Not enough stock for {$product->name}");
            }
            $product->decrement('stock', $item['qty']);
        }

        DB::commit();
        return response()->json(['message' => 'Sale recorded and stock updated!']);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => $e->getMessage()], 422);
    }
}
}