<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('category');
            $table->text('description')->nullable();
            $table->longText('ingredients');
            $table->longText('instructions');
            $table->unsignedInteger('prep_time')->default(0);
            $table->unsignedInteger('servings')->default(1);
            $table->boolean('is_public')->default(true);
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
