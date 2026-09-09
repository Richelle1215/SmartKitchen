<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientSubstitution extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient',
        'substitute',
        'ratio',
        'notes',
        'category',
    ];
}
