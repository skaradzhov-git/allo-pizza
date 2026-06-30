<?php

namespace App\Support;

class DeliveryZone
{
    /**
     * Delivery zone for Allo! Pizza in Ruse — simplified from the admin-drawn polygon.
     *
     * @return array<int, array{lat: float, lng: float}>
     */
    public static function defaultPolygon(): array
    {
        return [
            ['lat' => 43.844253, 'lng' => 25.951643],
            ['lat' => 43.845862, 'lng' => 25.960275],
            ['lat' => 43.841700, 'lng' => 25.960640],
            ['lat' => 43.836660, 'lng' => 25.958711],
            ['lat' => 43.834117, 'lng' => 25.954904],
            ['lat' => 43.837546, 'lng' => 25.951178],
            ['lat' => 43.841514, 'lng' => 25.947718],
            ['lat' => 43.843408, 'lng' => 25.950315],
        ];
    }

    public static function boundaryDescription(): string
    {
        return 'Районът между: ул. „Николаевска“, бул. „Цар Освободител“, ЖП гарата и бул. „Ген. Скобелев“.';
    }
}
