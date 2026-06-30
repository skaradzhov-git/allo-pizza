<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_amount',
        'discount_percent',
        'minimum_order_amount',
        'usage_limit',
        'used_count',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'discount_amount' => 'decimal:2',
            'discount_percent' => 'decimal:2',
            'minimum_order_amount' => 'decimal:2',
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function isCurrentlyValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function meetsMinimum(float $subtotal): bool
    {
        return ! $this->minimum_order_amount || $subtotal >= (float) $this->minimum_order_amount;
    }

    public function discountFor(float $subtotal): float
    {
        $discount = 0.0;

        if ($this->discount_amount) {
            $discount += (float) $this->discount_amount;
        }

        if ($this->discount_percent) {
            $discount += $subtotal * (float) $this->discount_percent / 100;
        }

        return min(round($discount, 2), $subtotal);
    }
}
