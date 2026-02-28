@blaze

<div {{ $attributes->twMerge(['class' => 'relative isolate flex min-h-screen items-center justify-center']) }}>
    <div class="relative w-full max-w-sm space-y-10">
        <x-shopper::brand class="mx-auto size-12" />

        <x-shopper::card class="w-full p-1.5 max-w-sm [&>div:first-of-type]:shadow-[0_1px_16px_-2px_rgba(63,63,71,0.2)]">
            {{ $slot }}
        </x-shopper::card>
    </div>
</div>
