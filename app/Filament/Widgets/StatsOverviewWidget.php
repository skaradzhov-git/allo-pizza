<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\On;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    #[On('order-status-updated')]
    public function refreshStats(): void
    {
        $this->cachedStats = null;
    }

    protected function getStats(): array
    {
        $todayQuery = Order::query()->whereDate('created_at', today());

        $ordersToday = (clone $todayQuery)->count();
        $newOrdersToday = (clone $todayQuery)
            ->where('status', OrderStatus::New)
            ->count();
        $revenueToday = (clone $todayQuery)
            ->where('status', OrderStatus::Completed)
            ->sum('total');
        $revenueThisWeek = Order::query()
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', OrderStatus::Completed)
            ->sum('total');
        $revenueThisMonth = Order::query()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->where('status', OrderStatus::Completed)
            ->sum('total');

        return [
            Stat::make('Нови поръчки днес', $newOrdersToday)
                ->description('Поръчки със статус „Нова“')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('info'),
            Stat::make('Приход днес', money((float) $revenueToday))
                ->description('Само завършени поръчки')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Приход тази седмица', money((float) $revenueThisWeek))
                ->description('Само завършени поръчки')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success'),
            Stat::make('Приход този месец', money((float) $revenueThisMonth))
                ->description('Само завършени поръчки')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),
            Stat::make('Поръчки днес', $ordersToday)
                ->description('Общ брой поръчки')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),
        ];
    }
}
