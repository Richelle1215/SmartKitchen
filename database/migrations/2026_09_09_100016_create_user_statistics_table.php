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
        Schema::create('user_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            
            // Content statistics
            $table->integer('total_recipes')->default(0);
            $table->integer('total_published_recipes')->default(0);
            $table->integer('total_views')->default(0);
            
            // Engagement statistics
            $table->integer('total_likes_received')->default(0);
            $table->integer('total_ratings_received')->default(0);
            $table->decimal('average_rating', 2, 1)->default(0);
            $table->integer('total_comments_received')->default(0);
            
            // Social statistics
            $table->integer('total_followers')->default(0);
            $table->integer('total_following')->default(0);
            $table->integer('total_collections')->default(0);
            
            // Achievement points
            $table->integer('achievement_points')->default(0);
            
            $table->timestamp('last_recipe_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_statistics');
    }
};
