@props([
    'linkClass' => 'font-bold text-brand-600 hover:text-brand-700',
    'stacked' => false,
])

@php
    use App\Models\StoreSetting;

    $storeSetting = StoreSetting::current();
    $phones = $storeSetting->phoneNumbers();
@endphp

@if ($stacked)
    <div {{ $attributes->class(['space-y-1']) }}>
        @foreach ($phones as $phone)
            <a href="tel:{{ \App\Models\StoreSetting::normalizePhone($phone) }}" @class([$linkClass, 'block'])>
                {{ $phone }}
            </a>
        @endforeach

        @if (! empty($storeSetting?->store_email))
            <a href="mailto:{{ $storeSetting->store_email }}" @class([$linkClass, 'block'])>
                {{ $storeSetting->store_email }}
            </a>
        @endif
    </div>
@else
    <div {{ $attributes->class(['flex flex-wrap items-center gap-x-3 gap-y-1']) }}>
        @foreach ($phones as $phone)
            <a href="tel:{{ \App\Models\StoreSetting::normalizePhone($phone) }}" class="{{ $linkClass }}">
                {{ $phone }}
            </a>
        @endforeach

        @if (! empty($storeSetting?->store_email))
            <a href="mailto:{{ $storeSetting->store_email }}" class="{{ $linkClass }}">
                {{ $storeSetting->store_email }}
            </a>
        @endif
    </div>
@endif
