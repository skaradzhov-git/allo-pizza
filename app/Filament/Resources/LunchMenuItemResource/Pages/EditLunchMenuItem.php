<?php

namespace App\Filament\Resources\LunchMenuItemResource\Pages;

use App\Filament\Resources\LunchMenuItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLunchMenuItem extends EditRecord
{
    protected static string $resource = LunchMenuItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
