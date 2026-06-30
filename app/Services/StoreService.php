<?php

namespace App\Services;

use App\Models\StoreSetting;
use App\Models\WorkingHour;
use Carbon\Carbon;

class StoreService
{
    public function settings(): StoreSetting
    {
        return StoreSetting::current();
    }

    public function isOpen(?Carbon $at = null): bool
    {
        $settings = $this->settings();

        if (! $settings->is_store_open) {
            return false;
        }

        $at ??= now();
        $workingHour = WorkingHour::query()
            ->where('day_of_week', $at->dayOfWeekIso)
            ->first();

        if (! $workingHour || $workingHour->is_closed) {
            return false;
        }

        if (! $workingHour->opens_at || ! $workingHour->closes_at) {
            return false;
        }

        $currentTime = $at->format('H:i:s');

        return $currentTime >= $workingHour->opens_at
            && $currentTime <= $workingHour->closes_at;
    }

    public function workingHoursMessage(?Carbon $at = null): string
    {
        $at ??= now();
        $workingHour = WorkingHour::query()
            ->where('day_of_week', $at->dayOfWeekIso)
            ->first();

        if (! $workingHour || $workingHour->is_closed) {
            return 'Днес сме затворени.';
        }

        if ($this->isOpen($at)) {
            return sprintf(
                'Отворено до %s ч.',
                substr((string) $workingHour->closes_at, 0, 5)
            );
        }

        return sprintf(
            'Работно време днес: %s – %s ч.',
            substr((string) $workingHour->opens_at, 0, 5),
            substr((string) $workingHour->closes_at, 0, 5)
        );
    }
}
