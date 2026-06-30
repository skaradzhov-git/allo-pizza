<?php

namespace App\Filament\Resources\LunchMenuResource\Pages;

use App\Filament\Resources\LunchMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLunchMenus extends ListRecords
{
    protected static string $resource = LunchMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
