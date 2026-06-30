<?php

namespace App\Filament\Resources\StoreSettingResource\Pages;

use App\Filament\Resources\StoreSettingResource;
use App\Models\StoreSetting;
use Filament\Resources\Pages\EditRecord;

class ManageStoreSetting extends EditRecord
{
    protected static string $resource = StoreSettingResource::class;

    protected static ?string $title = 'Настройки на магазина';

    public function mount(int | string | null $record = null): void
    {
        $this->record = StoreSetting::current();

        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
