<div class="flex items-center justify-between py-2 gap-1">
    @foreach([1,2,3,4,5] as $star)
        <x-heroicon-s-star
            @class([
                'h-4 w-4 shrink-0',
                'text-yellow-400' => $getState() >= $star,
                'text-gray-300' => $getState() < $star,
            ])
            aria-hidden="true"
        />
    @endforeach
</div>
