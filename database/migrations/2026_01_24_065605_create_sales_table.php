<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            
            // Link to products
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); 
            
            // Link to cashier ID
            // We use onDelete('restrict') to keep the data linked safely
            $table->foreignId('cashier_id')->constrained('cashiers')->onDelete('restrict');
            
            // Storing Cashier details directly for historical records
            $table->string('cashier_name');
            $table->string('cashier_email');
            
            $table->integer('quantity');
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};