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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('type'); // comment, rating, like, follow, etc.
            $table->string('title');
            $table->text('message');
            $table->string('action_url')->nullable();
            $table->foreignId('related_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('related_recipe_id')->nullable()->constrained('recipes')->onDelete('set null');
            
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
