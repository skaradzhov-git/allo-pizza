<?php

use App\Support\Money;
use Illuminate\Support\Facades\Storage;

if (! function_exists('money')) {
    function money(float|int|string|null $amount, ?int $decimals = null): string
    {
        return Money::format($amount, $decimals);
    }
}

if (! function_exists('public_media_url')) {
    function public_media_url(mixed $image): ?string
    {
        if (is_string($image)) {
            $image = trim($image);

            if (str_starts_with($image, '[') || str_starts_with($image, '{')) {
                $decoded = json_decode($image, true);
                $image = json_last_error() === JSON_ERROR_NONE ? $decoded : $image;
            }
        }

        if (is_array($image)) {
            $image = array_values($image)[0] ?? null;
        }

        if (! is_string($image) || $image === '') {
            return null;
        }

        if (str_starts_with($image, 'http')) {
            return $image;
        }

        if (str_starts_with($image, 'storage/')) {
            $image = substr($image, strlen('storage/'));
        }

        if (str_starts_with($image, '/storage/')) {
            $image = substr($image, strlen('/storage/'));
        }

        if (! Storage::disk('public')->exists($image)) {
            return null;
        }

        return route('media.public', ['path' => $image], false);
    }
}
