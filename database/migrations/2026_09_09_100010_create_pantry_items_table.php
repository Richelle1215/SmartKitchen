<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pantry_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('ingredient_name');
            $table->string('unit')->nullable(); // kg, g, L, ml, pcs, etc.
            $table->decimal('quantity', 8, 2)->default(0);
            $table->string('category')->nullable(); // Vegetables, Spices, Dairy, etc.
            $table->date('expiry_date')->nullable();
            $table->decimal('low_stock_threshold', 8, 2)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->unique(['user_id', 'ingredient_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pantry_items');
    }
};
