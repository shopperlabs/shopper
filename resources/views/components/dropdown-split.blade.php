<div class="shadow-sm rounded-md flex flex-row">
    {{ $button }}

    <x-shopper-dropdown align="right" width="56">
        <x-slot name="trigger">
            <div class="border-l border-blue-400">
                <x-shopper-button type="button" class="border-l-none rounded-l-none ml-0 pl-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                    </svg>
                </x-shopper-button>
            </div>
        </x-slot>

        <x-slot name="content">
            {{ $content }}
        </x-slot>
    </x-shopper-dropdown>
</div>
