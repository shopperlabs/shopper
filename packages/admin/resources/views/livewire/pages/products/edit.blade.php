<div>
    <x-shopper::container class="bg-white/75 py-5 dark:bg-gray-900/80">
        <x-shopper::breadcrumb :back="route('shopper.products.index')" :current="$product->name">
            <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" aria-hidden="true" />
            <x-shopper::breadcrumb.link
                :link="route('shopper.products.index')"
                :title="__('shopper::pages/products.menu')"
            />
        </x-shopper::breadcrumb>
    </x-shopper::container>

    <div
        class="pb-10"
        x-data="{
            options: [
                'detail',
                'media',
                'price',
                'files',
                'attributes',
                'variants',
                'inventory',
                'seo',
                'shipping',
                'related'
            ],
            activeTab: @entangle('activeTab')
        }"
    >
        <div @class([
            'sticky z-30 bg-white/75 backdrop-blur-sm dark:bg-gray-900/80',
            '-top-2' => $product->type,
            'top-6' => ! $product->type
        ])>
            <div class="space-y-4">
                <x-shopper::container>
                    <x-shopper::heading>
                        <x-slot:title>
                            <div class="space-y-1">
                                @if($product->type)
                                    <x-filament::badge
                                        :color="$product->type->getColor()"
                                        :icon="$product->type->getIcon()"
                                        class="inline-flex"
                                    >
                                        {{ $product->type->getLabel() }}
                                    </x-filament::badge>
                                @endif
                                <h2
                                    class="font-heading text-2xl font-bold leading-6 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:leading-9"
                                >
                                    {{ $product->name }}
                                </h2>
                            </div>
                        </x-slot>
                        <x-slot:action>
                            {{ $this->deleteAction }}
                        </x-slot>
                    </x-shopper::heading>
                </x-shopper::container>

                <x-filament::tabs :contained="true">
                    <x-filament::tabs.item
                        alpine-active="activeTab === 'detail'"
                        x-on:click="activeTab = 'detail'"
                        icon="untitledui-file-02"
                    >
                        {{ __('shopper::words.overview') }}
                    </x-filament::tabs.item>

                    <x-filament::tabs.item
                        alpine-active="activeTab === 'media'"
                        x-on:click="activeTab = 'media'"
                        icon="untitledui-image"
                    >
                        {{ __('shopper::words.media') }}
                    </x-filament::tabs.item>

                    @if (! $product->isVariant())
                        <x-filament::tabs.item
                            alpine-active="activeTab === 'price'"
                            x-on:click="activeTab = 'price'"
                            icon="untitledui-coins-stacked-02"
                        >
                            {{ __('shopper::words.pricing') }}
                        </x-filament::tabs.item>
                    @endif

                    @if ($product->isVirtual())
                        <x-filament::tabs.item
                            alpine-active="activeTab === 'files'"
                            x-on:click="activeTab = 'files'"
                            icon="untitledui-paperclip"
                        >
                            {{ __('shopper::words.files') }}
                        </x-filament::tabs.item>
                    @endif

                    @if (\Shopper\Feature::enabled('attribute') && $product->canUseAttributes())
                        <x-filament::tabs.item
                            alpine-active="activeTab === 'attributes'"
                            x-on:click="activeTab = 'attributes'"
                            icon="untitledui-puzzle-piece"
                        >
                            {{ __('shopper::pages/attributes.menu') }}
                        </x-filament::tabs.item>
                    @endif

                    @if ($product->canUseVariants())
                        <x-filament::tabs.item
                            alpine-active="activeTab === 'variants'"
                            x-on:click="activeTab = 'variants'"
                            icon="untitledui-book-open"
                        >
                            {{ __('shopper::words.variants') }}
                        </x-filament::tabs.item>
                    @endif

                    <x-filament::tabs.item
                        alpine-active="activeTab === 'inventory'"
                        x-on:click="activeTab = 'inventory'"
                        icon="untitledui-package"
                    >
                        {{ __('shopper::pages/products.stock_inventory_heading') }}
                    </x-filament::tabs.item>

                    @if ($product->canUseShipping())
                        <x-filament::tabs.item
                            alpine-active="activeTab === 'shipping'"
                            x-on:click="activeTab = 'shipping'"
                            icon="untitledui-plane"
                        >
                            {{ __('shopper::words.shipping') }}
                        </x-filament::tabs.item>
                    @endif

                    <x-filament::tabs.item
                        alpine-active="activeTab === 'seo'"
                        x-on:click="activeTab = 'seo'"
                        icon="untitledui-monitor-02"
                    >
                        {{ __('shopper::words.seo.slug') }}
                    </x-filament::tabs.item>

                    <x-filament::tabs.item
                        alpine-active="activeTab === 'related'"
                        x-on:click="activeTab = 'related'"
                        icon="untitledui-dataflow-04"
                    >
                        {{ __('shopper::pages/products.related_products') }}
                    </x-filament::tabs.item>
                </x-filament::tabs>
            </div>
        </div>

        <div class="mt-8">
            <div x-show="activeTab === 'detail'">
                <livewire:shopper-products.form.edit :$product />
            </div>
            <div x-show="activeTab === 'media'">
                <livewire:shopper-products.form.media :$product />
            </div>

            @if (! $product->isVariant())
                <div x-cloak x-show="activeTab === 'price'">
                    <x-shopper::container class="space-y-8">
                        <div>
                            <x-filament::section.heading>
                                {{ __('shopper::pages/products.pricing.title') }}
                            </x-filament::section.heading>
                            <x-filament::section.description class="mt-1 max-w-2xl">
                                {{ __('shopper::pages/products.pricing.description') }}
                            </x-filament::section.description>
                        </div>

                        <livewire:shopper-products.pricing :model="$product" />
                    </x-shopper::container>
                </div>
            @endif

            @if ($product->isVirtual())
                <div x-cloak x-show="activeTab === 'files'">
                    <livewire:shopper-products.form.files :$product />
                </div>
            @endif

            @if (\Shopper\Feature::enabled('attribute') && $product->canUseAttributes())
                <div x-cloak x-show="activeTab === 'attributes'">
                    <livewire:shopper-products.form.attributes :$product />
                </div>
            @endif

            @if ($product->canUseVariants())
                <div x-cloak x-show="activeTab === 'variants'">
                    <livewire:shopper-products.form.variants :$product />
                </div>
            @endif

            <div x-cloak x-show="activeTab === 'inventory'">
                <livewire:shopper-products.form.inventory :$product />
            </div>
            <div x-cloak x-show="activeTab === 'seo'">
                <livewire:shopper-products.form.seo :$product />
            </div>

            @if ($product->canUseShipping())
                <div x-cloak x-show="activeTab === 'shipping'">
                    <livewire:shopper-products.form.shipping :$product />
                </div>
            @endif

            <div x-cloak x-show="activeTab === 'related'">
                <livewire:shopper-products.form.related-products :$product />
            </div>
        </div>
    </div>

    <div x-data>
        <template x-teleport="body">
            <x-filament-actions::modals />
        </template>
    </div>
</div>
