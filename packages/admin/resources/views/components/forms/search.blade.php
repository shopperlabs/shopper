@props([
    'label' => __('shopper::forms.label.search'),
    'for' => 'search-filter',
    'placeholder' => __('shopper::forms.label.search'),
])

<div class="flex flex-1">
    <label for="{{ $for }}" class="sr-only">{{ $label }}</label>
    <div class="flex flex-1">
        <div class="relative grow focus-within:z-10">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <x-untitledui-search-sm class="size-5 text-gray-400 dark:text-gray-500" aria-hidden="true" />
            </div>
            <x-shopper::forms.input
                type="search"
                id="{{ $for }}"
                class="pl-10"
                :placeholder="$placeholder"
                {{ $attributes->except(['placeholder']) }}
            />
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <x-shopper::loader wire:loading wire:target="search" class="text-primary-600" />
            </div>
        </div>
    </div>
</div>
