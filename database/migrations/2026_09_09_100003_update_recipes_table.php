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
        Schema::table('recipes', function (Blueprint $table) {
            // Only add columns that don't exist yet
            if (!Schema::hasColumn('recipes', 'category_id')) {
                $table->foreignId('category_id')->constrained('recipe_categories')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('recipes', 'cook_time')) {
                $table->integer('cook_time')->nullable()->after('prep_time');
            }
            
            if (!Schema::hasColumn('recipes', 'recipe_image')) {
                $table->string('recipe_image')->nullable()->after('servings');
            }
            
            if (!Schema::hasColumn('recipes', 'recipe_video')) {
                $table->string('recipe_video')->nullable()->after('recipe_image');
            }
            
            if (!Schema::hasColumn('recipes', 'average_rating')) {
                $table->decimal('average_rating', 2, 1)->default(0)->after('recipe_video');
                $table->integer('rating_count')->default(0)->after('average_rating');
                $table->integer('view_count')->default(0)->after('rating_count');
                $table->integer('like_count')->default(0)->after('view_count');
                $table->integer('comment_count')->default(0)->after('like_count');
            }
            
            if (!Schema::hasColumn('recipes', 'is_published')) {
                $table->boolean('is_published')->default(true)->after('comment_count');
            }
            
            if (!Schema::hasColumn('recipes', 'deleted_at')) {
                $table->softDeletes()->after('is_published');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            if (Schema::hasColumn('recipes', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
            if (Schema::hasColumn('recipes', 'cook_time')) $table->dropColumn('cook_time');
            if (Schema::hasColumn('recipes', 'recipe_image')) $table->dropColumn('recipe_image');
            if (Schema::hasColumn('recipes', 'recipe_video')) $table->dropColumn('recipe_video');
            if (Schema::hasColumn('recipes', 'average_rating')) $table->dropColumn(['average_rating', 'rating_count', 'view_count', 'like_count', 'comment_count']);
            if (Schema::hasColumn('recipes', 'is_published')) $table->dropColumn('is_published');
            if (Schema::hasColumn('recipes', 'deleted_at')) $table->dropColumn('deleted_at');
        });
    }
};
