@props([
    'label' => 'Search',
    'for' => 'filter',
    'placeholder' => 'Search',
])

<div class="flex flex-1">
    <label for="{{ $for }}" class="sr-only">{{ __($label) }}</label>
    <div class="flex flex-1 rounded-md shadow-sm">
        <div class="relative grow focus-within:z-10">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-heroicon-o-search class="h-5 w-5 text-secondary-400" />
            </div>
            <x-shopper-input.text type="search" id="filter" wire:model.debounce.300ms="search" class="pl-10" placeholder="{{ __($placeholder) }}" />
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <x-shopper-loader wire:loading wire:target="search" class="text-primary-600" />
            </div>
        </div>
    </div>
</div>
