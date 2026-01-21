<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSupplier extends Model
{
    use HasFactory;

    protected $table = 'product_suppliers'; // ensure correct table name

    // Make fields mass assignable
    protected $fillable = [
        'product_id',
        'supplier_name',
        'quantity',
        'price',
    ];

    // Relationship to Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
