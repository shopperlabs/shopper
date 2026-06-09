<div>
    <x-shopper::heading :title="$variant->name" />

    {{ shopper()->getRenderHook(\Shopper\View\ProductRenderHook::VARIANT_HEADER_AFTER) }}

    <div class="mt-8 grid gap-x-6 gap-y-8 lg:grid-cols-3">
        <div class="space-y-8 *:space-y-4 lg:col-span-2">
            <div>
                <x-shopper::card class="[&>div:first-of-type]:p-0">
                    <x-slot name="title">
                        <div class="flex items-center justify-between">
                            <x-shopper::section-heading
                                :title="__('shopper::pages/products.variants.variant_information')"
                            />
                            <x-filament::button
                                type="button"
                                color="gray"
                                size="sm"
                                @click="Livewire.dispatch('openPanel', { component: 'shopper-slide-overs.update-variant', arguments: { variant: {{ $variant->id }}, product: {{ $product->id }} }})"
                            >
                                {{ __('shopper::forms.actions.edit') }}
                            </x-filament::button>
                        </div>
                    </x-slot>

                    <div>
                        <dl class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 lg:grid-cols-3 lg:gap-y-6">
                            <div>
                                <dt class="text-sm/6 font-medium text-sh-fg-secondary">
                                    {{ __('shopper::forms.label.name') }}
                                </dt>
                                <dd class="mt-2 text-sm/5 text-sh-fg-muted sm:mt-3">
                                    {{ $variant->name }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm/6 font-medium text-sh-fg-secondary">
                                    {{ __('Ean') }}
                                </dt>
                                <dd class="mt-2 text-sm/5 text-sh-fg-muted sm:mt-3">
                                    {{ $variant->ean ?? '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm/6 font-medium text-sh-fg-secondary">
                                    {{ __('Upc') }}
                                </dt>
                                <dd class="mt-2 text-sm/5 text-sh-fg-muted sm:mt-3">
                                    {{ $variant->upc ?? '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm/6 font-medium text-sh-fg-secondary">
                                    {{ __('shopper::forms.label.position') }}
                                </dt>
                                <dd class="mt-2 text-sm/5 text-sh-fg-muted sm:mt-3">
                                    {{ $variant->position }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm/6 font-medium text-sh-fg-secondary">
                                    {{ __('shopper::pages/products.allow_backorder') }}
                                </dt>
                                <dd class="mt-2 text-sm/5 text-sh-fg-muted sm:mt-3">
                                    <span
                                        @class([
                                            'relative inline-flex h-6 w-11 shrink-0 rounded-full border-2 border-transparent',
                                            'bg-sh-muted' => ! $variant->allow_backorder,
                                            'bg-primary-600' => $variant->allow_backorder,
                                        ])
                                        role="switch"
                                        aria-checked="{{ $variant->allow_backorder }}"
                                    >
                                        <span
                                            aria-hidden="true"
                                            @class([
                                                'pointer-events-none inline-block size-5 transform rounded-full bg-sh-surface shadow ring-0',
                                                'translate-x-0' => ! $variant->allow_backorder,
                                                'translate-x-5' => $variant->allow_backorder,
                                            ])
                                        ></span>
                                    </span>
                                </dd>
                            </div>
                        </dl>

                        <table
                            class="fi-ta-table w-full table-auto divide-y divide-sh-border text-start ring-1 ring-sh-border"
                        >
                            <thead>
                                <tr>
                                    <th class="fi-ta-header-cell px-3 py-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                        <span
                                            class="fi-ta-header-cell-label text-sm font-semibold text-sh-fg"
                                        >
                                            {{ __('shopper::pages/attributes.menu') }}
                                        </span>
                                    </th>
                                    <th
                                        class="fi-ta-header-cell px-3 py-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6"
                                    ></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-sh-border whitespace-nowrap">
                                @foreach ($variant->values->loadMissing('attribute') as $value)
                                    <tr>
                                        <td
                                            class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3"
                                        >
                                            <div class="grid w-full gap-y-1 px-3 py-2">
                                                <span
                                                    class="fi-ta-text-item-label text-sm leading-6 text-sh-fg"
                                                >
                                                    {{ $value->attribute->name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td
                                            class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3"
                                        >
                                            <div class="flex w-full flex-wrap items-center gap-3 gap-y-1 px-3 py-2">
                                                <x-filament::badge color="gray">
                                                    {{ $value->value }}
                                                </x-filament::badge>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-shopper::card>
            </div>

            <livewire:shopper-products.pricing :model="$variant" />

            <div>
                <x-shopper::section-heading :title="__('shopper::pages/settings/menu.location')" />

                <x-shopper::card>
                    <x-slot name="title">
                        <dl class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                            <div>
                                <dt class="text-sm/6 font-medium text-sh-fg-secondary">
                                    {{ __('shopper::forms.label.sku') }}
                                </dt>
                                <dd class="mt-2 text-sm/5 text-sh-fg-muted sm:mt-3">
                                    {{ $variant->sku ?? '--' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm/6 font-medium text-sh-fg-secondary">
                                    {{ __('shopper::forms.label.barcode') }}
                                </dt>

                                @if ($variant->barcode)
                                    <dd class="mt-2 space-y-1.5 text-sm/5 text-sh-fg-muted sm:mt-3">
                                        {!! Milon\Barcode\Facades\DNS1DFacade::getBarcodeHTML($variant->barcode, config('shopper.core.barcode_type')) !!}
                                    </dd>
                                @endif
                            </div>
                            <div class="flex items-center justify-end">
                                {{ $this->updateStockAction }}
                            </div>
                        </dl>
                    </x-slot>

                    <livewire:shopper-products.variant-stock :$variant />
                </x-shopper::card>
            </div>

            {{ shopper()->getRenderHook(\Shopper\View\ProductRenderHook::VARIANT_MAIN_AFTER) }}
        </div>
        <div class="space-y-6">
            <x-shopper::card>
                <x-slot name="title">
                    <div class="flex items-center justify-between gap-4">
                        <x-shopper::section-heading :title="__('shopper::words.media')" />
                        {{ $this->mediaAction }}
                    </div>
                </x-slot>

                <div class="space-y-6">
                    @if ($this->variant->media->isEmpty())
                        <div class="flex gap-3">
                            <x-phosphor-image-duotone
                                class="size-5 text-sh-fg-muted"
                                aria-hidden="true"
                            />
                            <div>
                                <p class="text-sm font-medium text-sh-fg-secondary">
                                    {{ __('shopper::words.images') }}
                                </p>
                                <span class="text-sm leading-3 text-sh-fg-muted">
                                    {{ __('shopper::words.empty_space') }}
                                </span>
                            </div>
                        </div>
                    @endif

                    @if ($this->variant->getFirstMedia(config('shopper.media.storage.thumbnail_collection')))
                        <div class="space-y-3">
                            <p class="text-sm/5 font-medium text-sh-fg-secondary">
                                {{ __('shopper::forms.label.thumbnail') }}
                            </p>
                            <img
                                class="size-14 max-w-none rounded-lg object-cover object-center ring-1 ring-sh-border"
                                src="{{ $this->variant->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection')) }}"
                                alt="Thumbnail"
                            />
                        </div>
                    @endif

                    @if ($this->variant->getMedia(config('shopper.media.storage.collection_name'))->isNotEmpty())
                        <div class="space-y-3">
                            <p class="text-sm/5 font-medium text-sh-fg-secondary">
                                {{ __('shopper::words.images') }}
                            </p>
                            <div class="flex flex-wrap gap-4">
                                @foreach ($this->variant->getMedia(config('shopper.media.storage.collection_name')) as $media)
                                    <img
                                        class="size-14 max-w-none rounded-lg object-cover object-center ring-1 ring-sh-border"
                                        src="{{ $media->getFullUrl() }}"
                                        alt="Thumbnail"
                                    />
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </x-shopper::card>
            <x-shopper::card
                :title="__('shopper::pages/products.shipping.package_dimension')"
                :description="__('shopper::pages/products.shipping.package_dimension_description')"
            >
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <dt class="text-sm/6 font-medium text-sh-fg-secondary">
                            {{ __('shopper::forms.label.width') }}
                        </dt>
                        <dd class="mt-1 text-sm/5 text-sh-fg-muted">
                            {{ $variant->width }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm/6 font-medium text-sh-fg-secondary">
                            {{ __('shopper::forms.label.height') }}
                        </dt>
                        <dd class="mt-1 text-sm/5 text-sh-fg-muted">
                            {{ $variant->height }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm/6 font-medium text-sh-fg-secondary">
                            {{ __('shopper::forms.label.weight') }}
                        </dt>
                        <dd class="mt-1 text-sm/5 text-sh-fg-muted">
                            {{ $variant->weight }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm/6 font-medium text-sh-fg-secondary">
                            {{ __('shopper::forms.label.volume') }}
                        </dt>
                        <dd class="mt-1 text-sm/5 text-sh-fg-muted">
                            {{ $variant->volume }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm/6 font-medium text-sh-fg-secondary">
                            {{ __('shopper::forms.label.depth') }}
                        </dt>
                        <dd class="mt-1 text-sm/5 text-sh-fg-muted">
                            {{ $variant->depth }}
                        </dd>
                    </div>
                </dl>
            </x-shopper::card>

            {{ shopper()->getRenderHook(\Shopper\View\ProductRenderHook::VARIANT_SIDEBAR_AFTER) }}
        </div>
    </div>

    <x-filament-actions::modals />
</div>
