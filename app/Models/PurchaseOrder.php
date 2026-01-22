<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'product_supplier_id',
        'supplier_name',
        'quantity',
        'status',
    ];

    public function productSupplier()
    {
        return $this->belongsTo(ProductSupplier::class, 'product_supplier_id');
    }

    public function product()
    {
        // via product_supplier relationship
        return $this->hasOneThrough(
            Product::class,         // final model
            ProductSupplier::class, // intermediate
            'id',                   // Foreign key on ProductSupplier table
            'id',                   // Foreign key on Product table
            'product_supplier_id',  // Local key on PurchaseOrder table
            'product_id'            // Local key on ProductSupplier table
        );
    }
}
