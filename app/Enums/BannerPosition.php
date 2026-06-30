<?php

namespace App\Enums;

enum BannerPosition: string
{
    case HomeHero = 'home_hero';
    case HomeSmallCards = 'home_small_cards';
    case PromoSection = 'promo_section';
    case LunchSection = 'lunch_section';

    public function label(): string
    {
        return match ($this) {
            self::HomeHero => 'Начална hero секция',
            self::HomeSmallCards => 'Малки промо карета',
            self::PromoSection => 'Промо секция',
            self::LunchSection => 'Обедно меню секция',
        };
    }
}
