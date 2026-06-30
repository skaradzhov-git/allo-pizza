<?php

namespace App\Filament\Resources\LunchMenuResource\Pages;

use App\Filament\Resources\LunchMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLunchMenu extends EditRecord
{
    protected static string $resource = LunchMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
