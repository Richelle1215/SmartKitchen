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
        Schema::create('ingredient_substitutions', function (Blueprint $table) {
            $table->id();
            
            $table->string('ingredient');
            $table->string('substitute');
            $table->string('ratio'); // e.g., "1:1", "1:2"
            $table->text('notes')->nullable();
            $table->string('category')->nullable(); // Dairy alternatives, gluten-free, vegan, etc.
            
            $table->timestamps();
            
            $table->unique(['ingredient', 'substitute']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_substitutions');
    }
};
