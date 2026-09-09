<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PantryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'ingredient_name',
        'unit',
        'quantity',
        'category',
        'expiry_date',
        'low_stock_threshold',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'low_stock_threshold' => 'decimal:2',
        'expiry_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isLowOnStock(): bool
    {
        if (!$this->low_stock_threshold) {
            return false;
        }
        
        return $this->quantity <= $this->low_stock_threshold;
    }

    public function isExpired(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        return $this->expiry_date->isPast();
    }
}
