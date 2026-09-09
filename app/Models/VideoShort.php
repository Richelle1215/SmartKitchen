<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoShort extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'recipe_id',
        'title',
        'description',
        'video_url',
        'thumbnail_url',
        'duration',
        'like_count',
        'comment_count',
        'view_count',
        'is_published',
    ];

    protected $casts = [
        'duration' => 'integer',
        'like_count' => 'integer',
        'comment_count' => 'integer',
        'view_count' => 'integer',
        'is_published' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'video_short_likes', 'video_short_id', 'user_id')
            ->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(VideoShortComment::class);
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
}
