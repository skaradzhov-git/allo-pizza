<?php

namespace App\Enums;

enum OrderStatus: string
{
    case New = 'new';
    case Accepted = 'accepted';
    case Preparing = 'preparing';
    case OnDelivery = 'on_delivery';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Нова',
            self::Accepted => 'Приета',
            self::Preparing => 'Приготвя се',
            self::OnDelivery => 'На доставка',
            self::Completed => 'Завършена',
            self::Cancelled => 'Отказана',
        };
    }
}
