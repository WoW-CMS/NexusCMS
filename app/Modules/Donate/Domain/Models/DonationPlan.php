<?php

namespace Modules\Donate\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DonationPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'dp_base',
        'extra_pct',
        'is_promo',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'amount' => 'float',
        'dp_base' => 'integer',
        'extra_pct' => 'integer',
        'is_promo' => 'boolean',
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopePromo($query)
    {
        return $query->where('is_promo', true);
    }

    public function getDpTotalAttribute(): int
    {
        return $this->dp_base + (int) ($this->dp_base * $this->extra_pct / 100);
    }

    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format($this->amount, 2);
    }
}