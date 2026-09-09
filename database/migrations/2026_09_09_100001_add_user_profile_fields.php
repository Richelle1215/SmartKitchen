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
        Schema::table('users', function (Blueprint $table) {
            // User role: guest or registered
            $table->enum('role', ['guest', 'registered'])->default('guest')->after('email');
            
            // User profile information
            $table->string('profile_picture')->nullable()->after('password');
            $table->text('bio')->nullable()->after('profile_picture');
            
            // User statistics
            $table->integer('total_recipes')->default(0)->after('bio');
            $table->integer('total_followers')->default(0)->after('total_recipes');
            $table->integer('total_following')->default(0)->after('total_followers');
            $table->integer('total_likes_received')->default(0)->after('total_following');
            $table->integer('total_views_received')->default(0)->after('total_likes_received');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'profile_picture',
                'bio',
                'total_recipes',
                'total_followers',
                'total_following',
                'total_likes_received',
                'total_views_received',
            ]);
        });
    }
};
