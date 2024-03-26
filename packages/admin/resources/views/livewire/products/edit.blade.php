<div
    x-data="{
        open: false,
        options: ['detail', 'variants', 'attributes', 'inventory', 'seo', 'shipping', 'related'],
        words: {
            'detail': '{{ __('shopper::words.overview') }}',
            'variants': '{{ __('shopper::words.variants') }}',
            'attributes': '{{ __('shopper::words.attributes') }}',
            'inventory': '{{ __('shopper::words.location') }}',
            'seo': '{{ __('shopper::words.seo') }}',
            'shipping': '{{ __('shopper::words.shipping') }}',
            'related': '{{ __('shopper::pages/products.related_products') }}'
        },
        currentTab: 'detail',
        activeTab(tab) {
            return this.currentTab === tab;
        },
    }"
>
    <x-shopper::container>
        <x-shopper::breadcrumb :back="route('shopper.products.index')" :current="$product->name">
            <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-gray-300 dark:text-gray-600" />
            <x-shopper::breadcrumb.link :link="route('shopper.products.index')" :title="__('shopper::layout.sidebar.products')" />
        </x-shopper::breadcrumb>
    </x-shopper::container>

    <div class="sticky z-30 mt-5 top-7 bg-white/75 dark:bg-gray-900 backdrop-blur-sm">
        <div class="space-y-4">
            <x-shopper::container>
                <div class="space-y-3 lg:flex lg:items-start lg:justify-between lg:space-y-0">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white sm:text-3xl sm:leading-9 sm:truncate font-heading">
                            {{ $product->name }}
                        </h3>
                    </div>
                    <div class="flex pt-1 space-x-3">
                    <span class="hidden sm:block">
                        <x-shopper::buttons.danger wire:click="$emit('openModal', 'shopper-modals.delete-product', {{ json_encode([$product->id, 'type' => 'product']) }})" type="button">
                            <x-untitledui-trash-03 class="w-5 h-5 mr-2 -ml-1" />
                            {{ __('shopper::layout.forms.actions.delete') }}
                        </x-shopper::buttons.danger>
                    </span>
                    </div>
                </div>
            </x-shopper::container>
            <div class="pb-5 border-b lg:pb-0 border-gray-200 dark:border-gray-700">
                <div class="px-4 lg:hidden">
                    <x-shopper::forms.select x-model="currentTab" aria-label="{{ __('shopper::words.selected_tab') }}" class="block w-full py-2 pl-3 pr-10">
                        <template x-for="option in options" :key="option">
                            <option
                                x-bind:value="option"
                                x-text="words[option]"
                                x-bind:selected="option === currentTab"
                            ></option>
                        </template>
                    </x-shopper::forms.select>
                </div>

                <div class="hidden lg:block">
                    <nav class="flex -mb-px space-x-8 px-4 2xl:px-6">
                        <button
                            type="button"
                            class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                            @click="currentTab = 'detail'"
                            :class="activeTab('detail') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                        >
                            {{ __('shopper::words.overview') }}
                        </button>
                        <button
                            type="button"
                            class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                            @click="currentTab = 'variants'"
                            :class="activeTab('variants') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                        >
                            {{ __('shopper::words.variants') }}
                        </button>
                        <button
                            type="button"
                            class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                            @click="currentTab = 'attributes'"
                            :class="activeTab('attributes') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                        >
                            {{ __('shopper::words.attributes') }}
                        </button>
                        <button
                            type="button"
                            class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                            @click="currentTab = 'inventory'"
                            :class="activeTab('inventory') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                        >
                            {{ __('shopper::words.location') }}
                        </button>
                        <button
                            type="button"
                            class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                            @click="currentTab = 'seo'"
                            :class="activeTab('seo') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                        >
                            {{ __('shopper::words.seo') }}
                        </button>
                        <button
                            type="button"
                            class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                            @click="currentTab = 'shipping'"
                            :class="activeTab('shipping') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                        >
                            {{ __('shopper::words.shipping') }}
                        </button>
                        <button
                            type="button"
                            class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                            @click="currentTab = 'related'"
                            :class="activeTab('related') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                        >
                            {{ __('shopper::pages/products.related_products') }}
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="pb-10 mt-8">
        <div x-show="currentTab === 'detail'">
            <livewire:shopper-products.form.edit :product="$product" :currency="$currency" />
        </div>
        <div x-cloak x-show="currentTab === 'variants'">
            <livewire:shopper-products.form.variants :product="$product" :currency="$currency" />
        </div>
        <div x-cloak x-show="currentTab === 'attributes'">
            <livewire:shopper-products.form.attributes :product="$product" />
        </div>
        <div x-cloak x-show="currentTab === 'inventory'">
            <livewire:shopper-products.form.inventory :product="$product" :inventories="$inventories" :defaultInventory="$inventory" />
        </div>
        <div x-cloak x-show="currentTab === 'seo'">
            <livewire:shopper-products.form.seo :product="$product" />
        </div>
        <div x-cloak x-show="currentTab === 'shipping'">
            <livewire:shopper-products.form.shipping :product="$product" />
        </div>
        <div x-cloak x-show="currentTab === 'related'">
            <livewire:shopper-products.form.related-products :product="$product" />
        </div>
    </div>
</div>
