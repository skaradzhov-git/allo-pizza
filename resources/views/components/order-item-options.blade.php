@props(['options' => []])

@php
    $optionList = $options instanceof \Illuminate\Support\Collection ? $options->all() : (array) $options;
@endphp

@if (count($optionList) > 0)
    <div {{ $attributes->merge(['class' => 'mt-0.5 space-y-0.5']) }}>
        @foreach ($optionList as $option)
            @php
                if ($option instanceof \App\Models\OrderItemOption) {
                    $type = $option->option_type->value;
                    $name = $option->name;
                    $price = (float) $option->price;
                } else {
                    $type = $option['type'] ?? '';
                    $name = $option['name'] ?? '';
                    $price = (float) ($option['price'] ?? 0);
                }
                $isRemoved = $type === 'ingredient_removed';
                $isExtra = $type === 'extra_added';
            @endphp
            <p class="text-xs {{ $isRemoved ? 'text-stone-400 line-through' : 'text-stone-500' }}">
                {{ $isExtra ? '+ ' : ($isRemoved ? '− ' : '') }}{{ $name }}
                @if ($isExtra && $price > 0)
                    <span class="text-stone-400">({{ money($price) }})</span>
                @endif
            </p>
        @endforeach
    </div>
@endif
