<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'category_id',
        'cashier_id',
        'cashier_name',
        'cashier_email',
        'quantity',
        'total_amount',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cashier()
    {
        return $this->belongsTo(Cashier::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}