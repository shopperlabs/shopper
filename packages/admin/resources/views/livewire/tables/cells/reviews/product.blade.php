@php
    $product = $getRecord()->reviewrateable;
@endphp

<div class="flex items-center gap-2 truncate py-2">
    <p class="text-sm leading-6 text-gray-500 dark:text-gray-400">{{ __('shopper::pages/products.single') }} :</p>
    <x-filament::section.heading class="!text-sm">
        <x-shopper::link :href="route('shopper.products.edit', $product)" class="underline">
            {{ $product->name }}
        </x-shopper::link>
    </x-filament::section.heading>
</div>
