<?php

namespace App\Providers;

use App\Models\Recipe;
use App\Models\Comment;
use App\Models\Rating;
use App\Models\PantryItem;
use App\Policies\RecipePolicy;
use App\Policies\CommentPolicy;
use App\Policies\RatingPolicy;
use App\Policies\PantryItemPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        $this->registerPolicies();
    }

    /**
     * Register authorization policies.
     */
    protected function registerPolicies(): void
    {
        $this->gate->policy(Recipe::class, RecipePolicy::class);
        $this->gate->policy(Comment::class, CommentPolicy::class);
        $this->gate->policy(Rating::class, RatingPolicy::class);
        $this->gate->policy(PantryItem::class, PantryItemPolicy::class);
    }
}
