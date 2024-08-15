<div class="flex items-center justify-between gap-1 py-2">
    @foreach ([1, 2, 3, 4, 5] as $star)
        {{-- format-ignore-start --}}
        <x-heroicon-s-star
            @class([
                'size-4 shrink-0',
                'text-yellow-400' => $getState() >= $star,
                'text-gray-300' => $getState() < $star,
            ])
            aria-hidden="true"
        />
        {{-- format-ignore-end --}}
    @endforeach
</div>
