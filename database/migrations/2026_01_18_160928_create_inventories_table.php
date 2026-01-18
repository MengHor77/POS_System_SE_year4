<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            // Pivot links
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('cashier_id')->constrained('cashiers')->cascadeOnDelete();

            // Stock movement
            $table->enum('type', ['in', 'out']); // 'in' = stock added, 'out' = stock sold
            $table->integer('quantity');
            $table->string('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
