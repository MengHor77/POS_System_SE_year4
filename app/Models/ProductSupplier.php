<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier_name',
        'quantity',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
