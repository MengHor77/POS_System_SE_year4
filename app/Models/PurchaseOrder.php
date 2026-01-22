<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'product_id',
        'supplier_name',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
