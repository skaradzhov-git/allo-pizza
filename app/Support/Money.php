<?php

namespace App\Support;

class Money
{
    public static function bgnPerEur(): float
    {
        return (float) config('money.bgn_per_eur', 1.95583);
    }

    public static function fromBgn(float $bgn): float
    {
        return round($bgn / self::bgnPerEur(), (int) config('money.decimals', 2));
    }

    public static function format(float|int|string|null $amount, ?int $decimals = null): string
    {
        $decimals ??= (int) config('money.decimals', 2);

        if ($amount === null || $amount === '') {
            $amount = 0;
        }

        return number_format((float) $amount, $decimals, '.', ' ')
            .' '.config('money.symbol', '€');
    }

    public static function symbol(): string
    {
        return (string) config('money.symbol', '€');
    }
}
