<x-shopper::container class="py-5 space-y-5">
    <x-shopper::breadcrumb :back="route('shopper.products.index')">
        <x-untitledui-chevron-left class="size-4 text-gray-300 dark:text-gray-600" aria-hidden="true" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.products.edit', ['product' => $product, 'tab' => 'variants'])"
            :title="$product->name"
            class="truncate"
        />
        <x-untitledui-chevron-left class="size-4 text-gray-300 dark:text-gray-600" aria-hidden="true" />
        <span class="truncate text-gray-500 dark:text-gray-400">
            {{ $variant->name }}
        </span>
    </x-shopper::breadcrumb>

    <x-shopper::heading :title="$variant->name" class="border-b border-gray-200 dark:border-white/10 pb-5" />

    <div class="grid gap-x-6 gap-y-8 lg:grid-cols-3">
        <div class="space-y-8 *:space-y-4 lg:col-span-2">
            <div>
                <x-filament::section.heading>
                    {{ __('shopper::pages/products.variants.variant_information') }}
                </x-filament::section.heading>

                <x-shopper::card class="divide-y divide-gray-200 dark:divide-white/10 overflow-hidden">
                    <div class="p-4 flex items-center justify-end">
                        <x-shopper::buttons.default class="font-semibold text-gray-950" type="button" wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.update-variant', arguments: { variantId: {{ $variant->id }}, productId: {{ $product->id }} }})">
                            {{ __('shopper::forms.actions.edit') }}
                        </x-shopper::buttons.default>
                    </div>

                    <dl class="grid gap-4 grid-cols-1 p-4 sm:grid-cols-2 lg:grid-cols-3 lg:gap-y-6">
                        <div>
                            <dt class="text-sm/6 font-medium text-gray-700 dark:text-gray-300">
                                {{ __('shopper::forms.label.name') }}
                            </dt>
                            <dd class="mt-2 text-sm/5 text-gray-500 sm:mt-3 dark:text-gray-400">
                                {{ $variant->name }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm/6 font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Ean') }}
                            </dt>
                            <dd class="mt-2 text-sm/5 text-gray-500 sm:mt-3 dark:text-gray-400">
                                {{ $variant->ean ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm/6 font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Upc') }}
                            </dt>
                            <dd class="mt-2 text-sm/5 text-gray-500 sm:mt-3 dark:text-gray-400">
                                {{ $variant->upc ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm/6 font-medium text-gray-700 dark:text-gray-300">
                                {{ __('shopper::forms.label.position') }}
                            </dt>
                            <dd class="mt-2 text-sm/5 text-gray-500 sm:mt-3 dark:text-gray-400">
                                {{ $variant->position }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm/6 font-medium text-gray-700 dark:text-gray-300">
                                {{ __('shopper::pages/products.allow_backorder') }}
                            </dt>
                            <dd class="mt-2 text-sm/5 text-gray-500 sm:mt-3 dark:text-gray-400">
                                <span
                                    @class([
                                        'relative inline-flex h-6 w-11 shrink-0 rounded-full border-2 border-transparent',
                                        'bg-gray-200 dark:bg-gray-800' => ! $variant->allow_backorder,
                                        'bg-primary-600' => $variant->allow_backorder,
                                    ])
                                    role="switch"
                                    aria-checked="{{ $variant->allow_backorder }}"
                                >
                                    <span
                                        aria-hidden="true"
                                        @class([
                                            'pointer-events-none inline-block size-5 transform rounded-full bg-white dark:bg-gray-950 shadow ring-0',
                                            'translate-x-0' => ! $variant->allow_backorder,
                                            'translate-x-5' => $variant->allow_backorder,
                                        ])
                                    ></span>
                                </span>
                            </dd>
                        </div>
                    </dl>

                    <x-filament-tables::table>
                        <x-slot name="header">
                            <x-filament-tables::header-cell class="lg:py-3">
                                <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                    {{ __('shopper::pages/attributes.menu') }}
                                </span>
                            </x-filament-tables::header-cell>
                            <x-filament-tables::header-cell class="lg:py-3" />
                        </x-slot>

                        @foreach($variant->values->loadMissing('attribute') as $value)
                            <x-filament-tables::row>
                                <x-filament-tables::cell>
                                    <div class="grid w-full gap-y-1 py-2 px-3">
                                        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white">
                                            {{ $value->attribute->name }}
                                        </span>
                                    </div>
                                </x-filament-tables::cell>
                                <x-filament-tables::cell>
                                    <div class="flex items-center flex-wrap gap-3 w-full gap-y-1 py-2 px-3">
                                        <x-filament::badge color="gray">{{ $value->value }}</x-filament::badge>
                                    </div>
                                </x-filament-tables::cell>
                            </x-filament-tables::row>
                        @endforeach
                    </x-filament-tables::table>
                </x-shopper::card>
            </div>
            <div>
                <div>
                    <x-filament::section.heading>
                        {{ __('shopper::pages/products.pricing.title') }}
                    </x-filament::section.heading>
                    <x-filament::section.description class="mt-1">
                        {{ __('shopper::pages/products.pricing.description') }}
                    </x-filament::section.description>
                </div>

                <livewire:shopper-products.pricing :model="$variant" />
            </div>
            <div>
                <x-filament::section.heading>
                    {{ __('shopper::pages/settings/menu.location') }}
                </x-filament::section.heading>

                <x-shopper::card class="divide-y divide-gray-200 dark:divide-white/10">
                    <dl class="grid gap-4 grid-cols-1 p-4 lg:grid-cols-3">
                        <div>
                            <dt class="text-sm/6 font-medium text-gray-700 dark:text-gray-300">
                                {{ __('shopper::forms.label.sku') }}
                            </dt>
                            <dd class="mt-2 text-sm/5 text-gray-500 sm:mt-3 dark:text-gray-400">
                                {{ $variant->sku ?? '--' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm/6 font-medium text-gray-700 dark:text-gray-300">
                                {{ __('shopper::forms.label.barcode') }}
                            </dt>
                            @if($variant->barcode)
                                <dd class="mt-2 space-y-1.5 text-sm/5 text-gray-500 sm:mt-3 dark:text-gray-400">
                                    {!! DNS1D::getBarcodeHTML($variant->barcode, config('shopper.core.barcode_type')) !!}
                                </dd>
                            @endif
                        </div>
                        <div class="flex items-center justify-end">
                            {{ $this->updateStockAction }}
                        </div>
                    </dl>
                    <div class="p-4">
                        <livewire:shopper-products.variant-stock :$variant />
                    </div>
                </x-shopper::card>
            </div>
        </div>
        <div class="space-y-6">
            <x-shopper::card class="divide-y divide-gray-200 dark:divide-white/10">
                <div class="p-4 flex items-center justify-between gap-4">
                    <x-filament::section.heading>
                        {{ __('shopper::words.media') }}
                    </x-filament::section.heading>
                    {{ $this->mediaAction }}
                </div>
                <div class="p-4 space-y-6">
                    @if($this->variant->media->isEmpty())
                        <div class="text-center text-sm leading-5">
                            <p class="text-sm/5 font-medium text-gray-700 dark:text-gray-300">
                                {{ __('shopper::words.images') }}
                            </p>
                            <span class="text-sm leading-3 text-gray-500 dark:text-gray-400">
                                {{ __('shopper::words.empty_space') }}
                            </span>
                        </div>
                    @endif

                    @if($this->variant->getFirstMedia(config('shopper.media.storage.thumbnail_collection')))
                        <div class="space-y-3">
                            <p class="text-sm/5 font-medium text-gray-700 dark:text-gray-300">
                                {{ __('shopper::forms.label.thumbnail') }}
                            </p>
                            <img
                                class="rounded-lg max-w-none object-cover object-center ring-white dark:ring-gray-900 size-[3.5rem]"
                                src="{{ $this->variant->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection')) }}"
                                alt="Thumbnail"
                            />
                        </div>
                    @endif

                    @if($this->variant->getMedia(config('shopper.media.storage.collection_name'))->isNotEmpty())
                        <div class="space-y-3">
                            <p class="text-sm/5 font-medium text-gray-700 dark:text-gray-300">
                                {{ __('shopper::words.images') }}
                            </p>
                            <div class="flex flex-wrap gap-4">
                                @foreach($this->variant->getMedia(config('shopper.media.storage.collection_name')) as $media)
                                    <img
                                        class="rounded-lg max-w-none object-cover object-center ring-white dark:ring-gray-900 size-[3.5rem]"
                                        src="{{ $media->getFullUrl() }}"
                                        alt="Thumbnail"
                                    />
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </x-shopper::card>
            <x-shopper::card class="divide-y divide-gray-200 dark:divide-white/10">
                <div class="p-4">
                    <x-filament::section.heading>
                        {{ __('shopper::pages/products.shipping.package_dimension') }}
                    </x-filament::section.heading>
                    <x-filament::section.description class="mt-1">
                        {{ __('shopper::pages/products.shipping.package_dimension_description') }}
                    </x-filament::section.description>
                </div>
                <dl class="p-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <dt class="text-sm/6 font-medium text-gray-700 dark:text-gray-300">
                            {{ __('shopper::forms.label.width') }}
                        </dt>
                        <dd class="mt-1 text-sm/5 text-gray-500 dark:text-gray-400">
                            {{ $variant->width }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm/6 font-medium text-gray-700 dark:text-gray-300">
                            {{ __('shopper::forms.label.height') }}
                        </dt>
                        <dd class="mt-1 text-sm/5 text-gray-500 dark:text-gray-400">
                            {{ $variant->height }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm/6 font-medium text-gray-700 dark:text-gray-300">
                            {{ __('shopper::forms.label.weight') }}
                        </dt>
                        <dd class="mt-1 text-sm/5 text-gray-500 dark:text-gray-400">
                            {{ $variant->weight }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm/6 font-medium text-gray-700 dark:text-gray-300">
                            {{ __('shopper::forms.label.volume') }}
                        </dt>
                        <dd class="mt-1 text-sm/5 text-gray-500 dark:text-gray-400">
                            {{ $variant->volume }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm/6 font-medium text-gray-700 dark:text-gray-300">
                            {{ __('shopper::forms.label.depth') }}
                        </dt>
                        <dd class="mt-1 text-sm/5 text-gray-500 dark:text-gray-400">
                            {{ $variant->depth }}
                        </dd>
                    </div>
                </dl>
            </x-shopper::card>
        </div>
    </div>

    <div x-data>
        <template x-teleport="body">
            <x-filament-actions::modals />
        </template>
    </div>
</x-shopper::container>
