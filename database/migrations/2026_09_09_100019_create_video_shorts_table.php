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
        Schema::create('video_shorts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipe_id')->nullable()->constrained('recipes')->onDelete('set null');
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url');
            $table->string('thumbnail_url')->nullable();
            $table->integer('duration'); // in seconds
            
            $table->integer('like_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->integer('view_count')->default(0);
            
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->index('is_published');
        });

        Schema::create('video_short_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('video_short_id')->constrained('video_shorts')->onDelete('cascade');
            
            $table->unique(['user_id', 'video_short_id']);
            $table->timestamps();
        });

        Schema::create('video_short_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('video_short_id')->constrained('video_shorts')->onDelete('cascade');
            
            $table->text('content');
            $table->integer('like_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('video_short_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_short_comments');
        Schema::dropIfExists('video_short_likes');
        Schema::dropIfExists('video_shorts');
    }
};
