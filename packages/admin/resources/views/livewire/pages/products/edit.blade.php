<div>
    <x-shopper::container>
        <x-shopper::breadcrumb :back="route('shopper.products.index')" :current="$product->name">
            <x-untitledui-chevron-left class="h-4 w-4 shrink-0 text-gray-300 dark:text-gray-600" />
            <x-shopper::breadcrumb.link
                :link="route('shopper.products.index')"
                :title="__('shopper::layout.sidebar.products')"
            />
        </x-shopper::breadcrumb>
    </x-shopper::container>

    <div
        class="pb-10 pt-6"
        x-data="{
            options: [
                'detail',
                'media',
                'variants',
                'attributes',
                'inventory',
                'seo',
                'shipping',
                'related',
            ],
            activeTab: 'detail',
        }"
    >
        <div class="sticky top-6 z-30 bg-white/75 backdrop-blur-sm dark:bg-gray-900/80">
            <div class="space-y-4">
                <x-shopper::container>
                    <x-shopper::heading>
                        <x-slot:title>
                            {{ $product->name }}
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

                    <x-filament::tabs.item
                        alpine-active="activeTab === 'variants'"
                        x-on:click="activeTab = 'variants'"
                        icon="untitledui-book-open"
                    >
                        {{ __('shopper::words.variants') }}
                    </x-filament::tabs.item>

                    <x-filament::tabs.item
                        alpine-active="activeTab === 'attributes'"
                        x-on:click="activeTab = 'attributes'"
                        icon="untitledui-puzzle-piece"
                    >
                        {{ __('shopper::words.attributes') }}
                    </x-filament::tabs.item>

                    <x-filament::tabs.item
                        alpine-active="activeTab === 'inventory'"
                        x-on:click="activeTab = 'inventory'"
                        icon="untitledui-package"
                    >
                        {{ __('shopper::words.location') }}
                    </x-filament::tabs.item>

                    <x-filament::tabs.item
                        alpine-active="activeTab === 'shipping'"
                        x-on:click="activeTab = 'shipping'"
                        icon="untitledui-plane"
                    >
                        {{ __('shopper::words.shipping') }}
                    </x-filament::tabs.item>

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
                <livewire:shopper-products.form.edit :product="$product" />
            </div>
            <div x-show="activeTab === 'media'">
                <livewire:shopper-products.form.media :product="$product" />
            </div>
            <div x-cloak x-show="activeTab === 'variants'">
                <livewire:shopper-products.form.variants :product="$product" />
            </div>
            <div x-cloak x-show="activeTab === 'attributes'">
                <livewire:shopper-products.form.attributes :product="$product" />
            </div>
            <div x-cloak x-show="activeTab === 'inventory'">
                <livewire:shopper-products.form.inventory :product="$product" />
            </div>
            <div x-cloak x-show="activeTab === 'seo'">
                <livewire:shopper-products.form.seo :product="$product" />
            </div>
            <div x-cloak x-show="activeTab === 'shipping'">
                <livewire:shopper-products.form.shipping :product="$product" />
            </div>
            <div x-cloak x-show="activeTab === 'related'">
                <livewire:shopper-products.form.related-products :product="$product" />
            </div>
        </div>
    </div>

    <x-filament-actions::modals />
</div>
