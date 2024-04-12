<div class="flex flex-col items-center justify-center">
    <div class="relative w-full flex items-center justify-center px-4 gap-2">
        <div class="relative w-6 h-6 grow-1 shrink-0 gap-1">
            <x-filament::icon
                icon="{{ $icon }}"
                class="w-full h-full absolute"
                aria-hidden="true"
            />
            {{-- Ugly fix for choices.js not registering clicks on SVGs. --}}
            <div class="w-full h-full absolute z-10"></div>
        </div>
        <span class="w-full text-sm leading-5 grow-0 shrink-0 truncate">
            {{ $icon }}
        </span>
    </div>
</div>
