@props(['items' => []])

@if (! empty($items))
    <nav {{ $attributes->merge(['class' => 'mb-4 text-sm text-stone-500 sm:mb-6']) }} aria-label="Breadcrumb">
        @foreach ($items as $index => $item)
            @if ($index > 0)
                <span class="mx-2">/</span>
            @endif

            @if (! empty($item['url']) && $index < count($items) - 1)
                <a href="{{ $item['url'] }}" class="hover:text-brand-600">{{ $item['label'] }}</a>
            @else
                <span @class(['text-stone-800' => $index === count($items) - 1])>{{ $item['label'] }}</span>
            @endif
        @endforeach
    </nav>
@endif
