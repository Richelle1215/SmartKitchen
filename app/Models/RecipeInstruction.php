<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeInstruction extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipe_id',
        'step_number',
        'instruction',
        'image',
        'video',
    ];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
