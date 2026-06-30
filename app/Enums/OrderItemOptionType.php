<?php

namespace App\Enums;

enum OrderItemOptionType: string
{
    case ExtraAdded = 'extra_added';
    case IngredientRemoved = 'ingredient_removed';
    case Variant = 'variant';
}
