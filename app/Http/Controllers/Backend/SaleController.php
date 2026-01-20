<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($sales->map(function($sale){
            return [
                'id' => $sale->id,
                'product_name' => $sale->product->name,
                'quantity' => $sale->quantity,
                'total_amount' => $sale->total_amount,
                'created_at' => $sale->created_at->format('Y-m-d H:i'),
            ];
        }));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'total_amount' => 'required|numeric',
        ]);

        $sale = Sale::create($request->all());

        return response()->json([
            'message' => 'Sale recorded successfully',
            'sale' => $sale
        ]);
    }
}
