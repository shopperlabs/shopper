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
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.products.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Products') }}</a>
    </x:shopper-breadcrumb>

    <div class="mt-3 bg-gray-100 z-30 relative pb-5 sm:pb-0 sticky top-4 sm:top-0 sm:-mx-8">
        <div class="sm:px-8 space-y-4">
            <div class="space-y-3 md:flex md:items-start md:justify-between md:space-y-0">
                <div class="flex-1 min-w-0">
                    <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">{{ $product->name }}</h3>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_visible ? 'bg-green-100 text-green-800': 'bg-yellow-100 text-yellow-800' }}">
                            {{ $product->is_visible ? __('Visible'): __('Not visible') }}
                        </span>
                    </div>
                </div>
                <div class="flex space-x-3 pt-1">
                    <span class="hidden sm:block">
                        <x-shopper-danger-button wire:click="$emit('openModal', 'shopper-modals.delete-product', {{ json_encode(['id' => $product->id]) }})" type="button">
                            <svg class="w-5 h-5 -ml-1 mr-2" x-description="Heroicon name: archive" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Delete') }}
                        </x-shopper-danger-button>
                    </span>
                </div>
            </div>
            <div class="pb-5 sm:pb-0 border-b border-gray-200">
                <!-- Dropdown menu on small screens -->
                <div class="sm:hidden">
                    <select x-model="currentTab" aria-label="Selected tab" class="form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5 transition ease-in-out duration-150">
                        <template x-for="option in options" :key="option">
                            <option
                                x-bind:value="option"
                                x-text="words[option]"
                                x-bind:selected="option === currentTab"
                            ></option>
                        </template>
                    </select>
                </div>
                <!-- Tabs at small breakpoint and up -->
                <div class="hidden sm:block">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="currentTab = 'detail'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" aria-current="page" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'detail' }">
                            {{ __('Overview') }}
                        </button>

                        <button @click="currentTab = 'variants'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'variants' }">
                            {{ __('Variants') }}
                        </button>

                        <button @click="currentTab = 'attributes'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'attributes' }">
                            {{ __('Attributes') }}
                        </button>

                        <button @click="currentTab = 'inventory'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'inventory' }">
                            {{ __('Inventory') }}
                        </button>

                        <button @click="currentTab = 'seo'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'seo' }">
                            {{ __('SEO') }}
                        </button>

                        <button @click="currentTab = 'shipping'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'shipping' }">
                            {{ __('Shipping') }}
                        </button>

                        <button @click="currentTab = 'related'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'related' }">
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
