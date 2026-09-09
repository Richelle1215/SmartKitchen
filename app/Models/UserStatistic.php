<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_recipes',
        'total_published_recipes',
        'total_views',
        'total_likes_received',
        'total_ratings_received',
        'average_rating',
        'total_comments_received',
        'total_followers',
        'total_following',
        'total_collections',
        'achievement_points',
        'last_recipe_at',
    ];

    protected $casts = [
        'total_recipes' => 'integer',
        'total_published_recipes' => 'integer',
        'total_views' => 'integer',
        'total_likes_received' => 'integer',
        'total_ratings_received' => 'integer',
        'average_rating' => 'decimal:2',
        'total_comments_received' => 'integer',
        'total_followers' => 'integer',
        'total_following' => 'integer',
        'total_collections' => 'integer',
        'achievement_points' => 'integer',
        'last_recipe_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
