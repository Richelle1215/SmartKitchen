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
        Schema::create('meal_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('meal_type')->default('weekly'); // weekly, daily, custom
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
        });

        // Pivot table for meal plan items (day -> breakfast/lunch/dinner -> recipe)
        Schema::create('meal_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_plan_id')->constrained('meal_plans')->onDelete('cascade');
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            
            $table->date('meal_date');
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack'])->default('lunch');
            $table->integer('servings')->default(1);
            
            $table->timestamps();
            
            $table->index(['meal_plan_id', 'meal_date']);
            $table->unique(['meal_plan_id', 'meal_date', 'meal_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_plan_items');
        Schema::dropIfExists('meal_plans');
    }
};
