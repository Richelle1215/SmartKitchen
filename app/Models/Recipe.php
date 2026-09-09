<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'prep_time',
        'cook_time',
        'servings',
        'recipe_image',
        'recipe_video',
        'average_rating',
        'rating_count',
        'view_count',
        'like_count',
        'comment_count',
        'is_published',
    ];

    protected $casts = [
        'prep_time' => 'integer',
        'cook_time' => 'integer',
        'servings' => 'integer',
        'average_rating' => 'decimal:2',
        'rating_count' => 'integer',
        'view_count' => 'integer',
        'like_count' => 'integer',
        'comment_count' => 'integer',
        'is_published' => 'boolean',
    ];

    // ====== RELATIONSHIPS ======
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RecipeCategory::class);
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function instructions(): HasMany
    {
        return $this->hasMany(RecipeInstruction::class)->orderBy('step_number');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes', 'recipe_id', 'user_id')
            ->withTimestamps();
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites', 'recipe_id', 'user_id')
            ->withTimestamps();
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(RecipeCollection::class, 'collection_recipes', 'recipe_id', 'collection_id')
            ->withTimestamps();
    }

    public function mealPlanItems(): HasMany
    {
        return $this->hasMany(MealPlanItem::class);
    }

    public function videoShorts(): HasMany
    {
        return $this->hasMany(VideoShort::class);
    }

    // ====== HELPER METHODS ======
    public function getTotalTimeAttribute(): int
    {
        return ($this->prep_time ?? 0) + ($this->cook_time ?? 0);
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function incrementLikeCount(): void
    {
        $this->increment('like_count');
    }

    public function decrementLikeCount(): void
    {
        $this->decrement('like_count');
    }

    public function incrementCommentCount(): void
    {
        $this->increment('comment_count');
    }

    public function decrementCommentCount(): void
    {
        $this->decrement('comment_count');
    }

    public function updateAverageRating(): void
    {
        $average = $this->ratings()->avg('stars') ?? 0;
        $count = $this->ratings()->count();
        
        $this->update([
            'average_rating' => round($average, 1),
            'rating_count' => $count,
        ]);
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }
}
