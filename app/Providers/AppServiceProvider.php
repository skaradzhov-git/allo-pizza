<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\StoreSetting;
use App\Observers\OrderObserver;
use App\Services\CartService;
use App\Services\StoreService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Order::observe(OrderObserver::class);

        View::composer('layouts.app', function ($view) {
            $settings = StoreSetting::current();

            $view->with([
                'storeSetting' => $settings,
                'storeIsOpen' => app(StoreService::class)->isOpen(),
                'cartCount' => app(CartService::class)->itemCount(),
            ]);
        });
    }
}
