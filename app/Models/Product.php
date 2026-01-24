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
    'category',    // category name
    'category_id', // foreign key
    'barcode',
    'price',
    'stock',
];


    // Optional: cast fields
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Relationship: Product belongs to a Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relation to Inventory (optional pivot tracking)
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
