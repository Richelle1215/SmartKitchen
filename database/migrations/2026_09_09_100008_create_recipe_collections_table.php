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
        Schema::create('recipe_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_public')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
        });

        // Pivot table for recipe collections
        Schema::create('collection_recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('recipe_collections')->onDelete('cascade');
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            
            $table->unique(['collection_id', 'recipe_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_recipes');
        Schema::dropIfExists('recipe_collections');
    }
};
