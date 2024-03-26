<div x-data="{ show: false }" @click.outside="show = false" class="relative">
    <x-shopper::label for="icon" :value="__('shopper::layout.forms.label.icon')" />
    <div class="mt-2 flex rounded-md shadow-sm">
        <div class="relative flex flex-grow items-stretch focus-within:z-10">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                @if($value)
                    <x-dynamic-component :component="$value" class="h-5 w-5 text-gray-500 dark:text-gray-400" />
                @else
                    <x-untitledui-face-frown class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                @endif
            </div>
            <x-shopper::forms.input
                type="text"
                name="icon"
                id="icon"
                wire:model="value"
                class="rounded-none rounded-l-md pl-10"
                :placeholder="__('shopper::layout.forms.placeholder.no_icon')"
                readonly
            />
        </div>
        <button @click="show = !show" type="button" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:ring-gray-700 dark:hover:bg-gray-800">
            <x-untitledui-chevron-selector-vertical class="h-5 w-5 text-gray-400 dark:text-gray-500" />
        </button>
    </div>
    <div x-show="show" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 z-10 mt-2 w-full max-w-sm overflow-hidden origin-top-right rounded-lg bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black dark:ring-gray-900 ring-opacity-5 focus:outline-none"
         role="menu"
    >
        <div>
            <div class="relative">
                <div class="flex items-center px-4 py-3 bg-gray-50/70 dark:bg-gray-700/70 backdrop-blur-sm ring-1 ring-gray-900/10 dark:ring-black/10">
                    <x-shopper::forms.input
                        type="search"
                        wire:model.debounce.550ms="search"
                        class="dark:bg-gray-800"
                        :placeholder="__('shopper::layout.forms.placeholder.icon_placeholder')"
                    />
                </div>
                <div wire:loading class="absolute inset-x-0 bottom-0 h-1 w-full bg-no-repeat bg-gradient-to-r from-primary-500 to-primary-600 animate-progress rounded-full" style="background-size: 60% 100%;"></div>
            </div>
            <div class="max-h-64 overflow-hidden overflow-y-scroll scrolling py-4 px-3 space-y-5">
                <div class="grid grid-cols-6 gap-x-3 gap-y-4">
                    @forelse($icons as $icon)
                        <button
                            type="button"
                            title="{{ $icon }}"
                            wire:click="selectedIcon('{{ $icon }}')"
                            @class([
                                'inline-flex items-center justify-center text-sm leading-5 p-2 rounded-lg',
                                'text-gray-500 dark:text-gray-400 hover:bg-gray-50 hover:text-gray-700 dark:hover:text-white dark:hover:bg-gray-700' => $value !== $icon,
                                'bg-primary-500 text-white' => $value === $icon,
                            ])
                        >
                            <x-dynamic-component :component="$icon" class="w-5 h-5" />
                        </button>
                    @empty
                        <div class="col-span-6 flex flex-col items-center justify-center py-6 gap-y-2">
                            <x-untitledui-face-neutral class="h-8 w-8 text-primary-500" stroke-width="1.5" />
                            <h4 class="text-base font-medium text-gray-500 dark:text-gray-400">
                                {{ __('shopper::words.icon_no_result') }}
                            </h4>
                        </div>
                    @endforelse
                </div>
                @if($icons->hasMorePages())
                    <button wire:click.prevent="loadMore" class="inline-flex items-center justify-center w-full text-sm leading-5 text-gray-500 dark:text-gray-500">
                        {{ __('Load more') }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
