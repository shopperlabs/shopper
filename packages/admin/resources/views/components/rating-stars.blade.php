@props([
    'rating' => 0,
])

<div {{ $attributes->class(['flex items-center gap-1']) }}>
    @foreach ([1, 2, 3, 4, 5] as $star)
        {{-- format-ignore-start --}}
        <x-heroicon-s-star
            @class([
                'size-4 shrink-0',
                'text-yellow-400' => $rating >= $star,
                'text-sh-fg-muted' => $rating < $star,
            ])
            aria-hidden="true"
        />
        {{-- format-ignore-end --}}
    @endforeach
</div>
