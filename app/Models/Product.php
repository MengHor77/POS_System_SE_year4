<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Fillable fields for mass assignment
    protected $fillable = [
        'name',
        'brand',
        'barcode',
        'price',
        'stock',
    ];

    // Optional: cast fields
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    // Relation to Inventory (if you want pivot tracking)
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
