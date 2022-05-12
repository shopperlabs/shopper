<div
    x-data="{
        open: false,
        options: ['detail', 'variants', 'attributes', 'inventory', 'seo', 'shipping', 'related'],
        words: {
            'detail': '{{ __('shopper::messages.overview') }}',
            'variants': '{{ __('shopper::messages.variants') }}',
            'attributes': '{{ __('shopper::messages.attributes') }}',
            'inventory': '{{ __('shopper::messages.inventory') }}',
            'seo': '{{ __('shopper::messages.seo') }}',
            'shipping': '{{ __('shopper::messages.shipping') }}',
            'related': '{{ __('shopper::pages/products.related_products') }}'
        },
        currentTab: 'detail'
    }"
>
    <x-shopper::breadcrumb back="shopper.products.index">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper::breadcrumb.link :link="route('shopper.products.index')" title="shopper::layout.sidebar.products" />
    </x-shopper::breadcrumb>

    <div class="mt-3 bg-secondary-100 dark:bg-secondary-900 z-30 relative pb-5 sm:pb-0 sticky top-4 sm:top-2 sm:-mx-8">
        <div class="sm:px-8 space-y-4">
            <div class="space-y-3 md:flex md:items-start md:justify-between md:space-y-0">
                <div class="flex-1 min-w-0">
                    <h3 class="text-2xl font-bold leading-6 text-secondary-900 dark:text-white sm:text-3xl sm:leading-9 sm:truncate">{{ $product->name }}</h3>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_visible ? 'bg-green-100 text-green-800': 'bg-yellow-100 text-yellow-800' }}">
                            {{ $product->is_visible ? __('shopper::layout.forms.label.visible'): __('shopper::layout.forms.label.invisible') }}
                        </span>
                    </div>
                </div>
                <div class="flex space-x-3 pt-1">
                    <span class="hidden sm:block">
                        <x-shopper::buttons.danger wire:click="$emit('openModal', 'shopper-modals.delete-product', {{ json_encode([$product->id, 'type' => 'product']) }})" type="button">
                            <x-heroicon-s-archive class="w-5 h-5 -ml-1 mr-2" />
                            {{ __('shopper::layout.forms.actions.delete') }}
                        </x-shopper::buttons.danger>
                    </span>
                </div>
            </div>
            <div class="pb-5 sm:pb-0 border-b border-secondary-200 dark:border-secondary-700">
                <div class="sm:hidden">
                    <x-shopper::forms.select x-model="currentTab" aria-label="Selected tab" class="block w-full pl-3 pr-10 py-2">
                        <template x-for="option in options" :key="option">
                            <option
                                x-bind:value="option"
                                x-text="words[option]"
                                x-bind:selected="option === currentTab"
                            ></option>
                        </template>
                    </x-shopper::forms.select>
                </div>

                <div class="hidden sm:block">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="currentTab = 'detail'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" aria-current="page" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'detail' }">
                            {{ __('shopper::messages.overview') }}
                        </button>

                        <button @click="currentTab = 'variants'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'variants' }">
                            {{ __('shopper::messages.variants') }}
                        </button>

                        <button @click="currentTab = 'attributes'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'attributes' }">
                            {{ __('shopper::messages.attributes') }}
                        </button>

                        <button @click="currentTab = 'inventory'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'inventory' }">
                            {{ __('shopper::messages.inventory') }}
                        </button>

                        <button @click="currentTab = 'seo'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'seo' }">
                            {{ __('shopper::messages.seo') }}
                        </button>

                        <button @click="currentTab = 'shipping'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'shipping' }">
                            {{ __('shopper::messages.shipping') }}
                        </button>

                        <button @click="currentTab = 'related'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'related' }">
                            {{ __('shopper::pages/products.related_products') }}
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
