<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LunchMenuItem extends Model
{
    use HasFactory;

    /** @var list<string> */
    public const MEAL_SECTION_ORDER = [
        'Супи',
        'Салати',
        'Пици',
        'Пърленки',
        'Напитки',
        'Десерти',
    ];

    public const SECTIONS = [
        'Супи' => 'Супи',
        'Салати' => 'Салати',
        'Пици' => 'Пици',
        'Пърленки' => 'Пърленки',
        'Напитки' => 'Напитки',
        'Десерти' => 'Десерти',
    ];

    public function mealSectionOrder(): int
    {
        $index = array_search($this->section, self::MEAL_SECTION_ORDER, true);

        return $index === false ? 999 : $index;
    }

    public function mealSortKey(): string
    {
        return sprintf('%04d-%04d', $this->mealSectionOrder(), $this->sort_order ?? 999);
    }

    protected $fillable = [
        'section',
        'name',
        'description',
        'price',
        'image',
        'is_active',
        'is_spicy',
        'is_hit',
        'is_new',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_spicy' => 'boolean',
            'is_hit' => 'boolean',
            'is_new' => 'boolean',
        ];
    }

    public function lunchMenus(): BelongsToMany
    {
        return $this->belongsToMany(LunchMenu::class, 'lunch_menu_lunch_menu_item')
            ->withPivot('sort_order');
    }

    public function fallbackIcon(): string
    {
        return match ($this->section) {
            'Супи' => '🍲',
            'Салати' => '🥗',
            'Пици' => '🍕',
            'Пърленки' => '🫓',
            'Напитки' => '🥤',
            'Десерти' => '🍰',
            default => '🍽️',
        };
    }
}
