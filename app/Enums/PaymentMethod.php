<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CashOnDelivery = 'cash_on_delivery';
    case PayAtStore = 'pay_at_store';

    public function label(): string
    {
        return match ($this) {
            self::CashOnDelivery => 'Плащане при доставка',
            self::PayAtStore => 'Плащане на място',
        };
    }
}
