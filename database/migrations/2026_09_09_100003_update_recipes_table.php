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
                $table->foreignId('category_id')->nullable()->after('user_id')->constrained('recipe_categories')->onDelete('set null');
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
            $table->dropForeignKeyIfExists(['category_id']);
            $cols = [];
            if (Schema::hasColumn('recipes', 'category_id')) $cols[] = 'category_id';
            if (Schema::hasColumn('recipes', 'cook_time')) $cols[] = 'cook_time';
            if (Schema::hasColumn('recipes', 'recipe_image')) $cols[] = 'recipe_image';
            if (Schema::hasColumn('recipes', 'recipe_video')) $cols[] = 'recipe_video';
            if (Schema::hasColumn('recipes', 'average_rating')) $cols[] = 'average_rating';
            if (Schema::hasColumn('recipes', 'rating_count')) $cols[] = 'rating_count';
            if (Schema::hasColumn('recipes', 'view_count')) $cols[] = 'view_count';
            if (Schema::hasColumn('recipes', 'like_count')) $cols[] = 'like_count';
            if (Schema::hasColumn('recipes', 'comment_count')) $cols[] = 'comment_count';
            if (Schema::hasColumn('recipes', 'is_published')) $cols[] = 'is_published';
            if (Schema::hasColumn('recipes', 'deleted_at')) $cols[] = 'deleted_at';
            
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
