<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'profile_picture', 'bio'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ====== RECIPE RELATIONSHIPS ======
    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    // ====== COMMUNITY RELATIONSHIPS ======
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'likes', 'user_id', 'recipe_id')
            ->withTimestamps();
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'favorites', 'user_id', 'recipe_id')
            ->withTimestamps();
    }

    // ====== COLLECTIONS ======
    public function recipeCollections(): HasMany
    {
        return $this->hasMany(RecipeCollection::class);
    }

    // ====== SOCIAL RELATIONSHIPS ======
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    // ====== PANTRY & MEAL PLANNING ======
    public function pantryItems(): HasMany
    {
        return $this->hasMany(PantryItem::class);
    }

    public function mealPlans(): HasMany
    {
        return $this->hasMany(MealPlan::class);
    }

    // ====== NOTIFICATIONS & MESSAGES ======
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversation_participants', 'user_id', 'conversation_id')
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // ====== ACHIEVEMENTS & STATISTICS ======
    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements', 'user_id', 'achievement_id')
            ->withPivot('unlocked_at')
            ->withTimestamps();
    }

    public function statistics(): HasOne
    {
        return $this->hasOne(UserStatistic::class);
    }

    // ====== VIDEO SHORTS ======
    public function videoShorts(): HasMany
    {
        return $this->hasMany(VideoShort::class);
    }

    public function videoShortLikes(): BelongsToMany
    {
        return $this->belongsToMany(VideoShort::class, 'video_short_likes', 'user_id', 'video_short_id')
            ->withTimestamps();
    }

    public function videoShortComments(): HasMany
    {
        return $this->hasMany(VideoShortComment::class);
    }

    // ====== MODERATION ======
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    // ====== HELPER METHODS ======
    public function isGuest(): bool
    {
        return $this->role === 'guest';
    }

    public function isRegistered(): bool
    {
        return $this->role === 'registered';
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    public function hasLikedRecipe(Recipe $recipe): bool
    {
        return $this->likes()->where('recipe_id', $recipe->id)->exists();
    }

    public function hasFavoritedRecipe(Recipe $recipe): bool
    {
        return $this->favorites()->where('recipe_id', $recipe->id)->exists();
    }

    public function hasRatedRecipe(Recipe $recipe): bool
    {
        return $this->ratings()->where('recipe_id', $recipe->id)->exists();
    }
}
