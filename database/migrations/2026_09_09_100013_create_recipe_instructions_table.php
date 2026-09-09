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
        Schema::create('recipe_instructions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            
            $table->integer('step_number');
            $table->text('instruction');
            $table->string('image')->nullable(); // image for this step
            $table->string('video')->nullable(); // video for this step
            
            $table->timestamps();
            
            $table->index('recipe_id');
            $table->unique(['recipe_id', 'step_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_instructions');
    }
};
