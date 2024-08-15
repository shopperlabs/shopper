<div class="flex flex-col items-center justify-center">
    <div class="relative flex w-full items-center justify-center gap-2 px-4">
        <div class="grow-1 relative size-6 shrink-0 gap-1">
            <x-filament::icon icon="{{ $icon }}" class="absolute h-full w-full" aria-hidden="true" />
            {{-- Ugly fix for choices.js not registering clicks on SVGs. --}}
            <div class="absolute z-10 h-full w-full"></div>
        </div>
        <span class="w-full shrink-0 grow-0 truncate text-sm leading-5">
            {{ $icon }}
        </span>
    </div>
</div>
