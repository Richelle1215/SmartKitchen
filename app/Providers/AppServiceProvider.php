<?php

namespace App\Providers;

use App\Models\Recipe;
use App\Models\Comment;
use App\Models\Rating;
use App\Models\PantryItem;
use App\Models\MealPlan;
use App\Policies\RecipePolicy;
use App\Policies\CommentPolicy;
use App\Policies\RatingPolicy;
use App\Policies\PantryItemPolicy;
use App\Policies\MealPlanPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::policy(Recipe::class, RecipePolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
        Gate::policy(Rating::class, RatingPolicy::class);
        Gate::policy(PantryItem::class, PantryItemPolicy::class);
        Gate::policy(MealPlan::class, MealPlanPolicy::class);
    }
}
