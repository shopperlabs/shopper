<div
    x-data="{
        open: false,
        options: ['detail', 'variants', 'attributes', 'inventory', 'seo', 'shipping', 'related'],
        words: {
            'detail': '{{ __("Overview") }}',
            'variants': '{{ __("Variants") }}',
            'attributes': '{{ __("Attributes") }}',
            'inventory': '{{ __("Inventory") }}',
            'seo': '{{ __("SEO") }}',
            'shipping': '{{ __("Shipping") }}',
            'related': '{{ __("Related Products") }}'
        },
        currentTab: 'detail'
    }"
>
    <x:shopper-breadcrumb back="shopper.products.index">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.products.index')" title="Products" />
    </x:shopper-breadcrumb>

    <div class="mt-3 bg-gray-100 dark:bg-gray-900 z-30 relative pb-5 sm:pb-0 sticky top-4 sm:top-2 sm:-mx-8">
        <div class="sm:px-8 space-y-4">
            <div class="space-y-3 md:flex md:items-start md:justify-between md:space-y-0">
                <div class="flex-1 min-w-0">
                    <h3 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white sm:text-3xl sm:leading-9 sm:truncate">{{ $product->name }}</h3>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_visible ? 'bg-green-100 text-green-800': 'bg-yellow-100 text-yellow-800' }}">
                            {{ $product->is_visible ? __('Visible'): __('Not visible') }}
                        </span>
                    </div>
                </div>
                <div class="flex space-x-3 pt-1">
                    <span class="hidden sm:block">
                        <x-shopper-danger-button wire:click="$emit('openModal', 'shopper-modals.delete-product', {{ json_encode([$product->id]) }})" type="button">
                            <x-heroicon-s-archive class="w-5 h-5 -ml-1 mr-2" />
                            {{ __('Delete') }}
                        </x-shopper-danger-button>
                    </span>
                </div>
            </div>
            <div class="pb-5 sm:pb-0 border-b border-gray-200 dark:border-gray-700">
                <!-- Dropdown menu on small screens -->
                <div class="sm:hidden">
                    <x-shopper-input.select x-model="currentTab" aria-label="Selected tab" class="block w-full pl-3 pr-10 py-2">
                        <template x-for="option in options" :key="option">
                            <option
                                x-bind:value="option"
                                x-text="words[option]"
                                x-bind:selected="option === currentTab"
                            ></option>
                        </template>
                    </x-shopper-input.select>
                </div>
                <!-- Tabs at small breakpoint and up -->
                <div class="hidden sm:block">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="currentTab = 'detail'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" aria-current="page" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'detail' }">
                            {{ __('Overview') }}
                        </button>

                        <button @click="currentTab = 'variants'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'variants' }">
                            {{ __('Variants') }}
                        </button>

                        <button @click="currentTab = 'attributes'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'attributes' }">
                            {{ __('Attributes') }}
                        </button>

                        <button @click="currentTab = 'inventory'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'inventory' }">
                            {{ __('Inventory') }}
                        </button>

                        <button @click="currentTab = 'seo'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'seo' }">
                            {{ __('SEO') }}
                        </button>

                        <button @click="currentTab = 'shipping'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'shipping' }">
                            {{ __('Shipping') }}
                        </button>

                        <button @click="currentTab = 'related'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'related' }">
                            {{ __('Related Products') }}
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 pb-10">
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
