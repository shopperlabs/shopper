<div
    x-data="{ modal: false, show: false, on: false }"
    x-init="
        flatpickr('.date', { minDate: 'today', dateFormat: 'Y-m-d H:i', time_24hr: true, enableTime: true });
        flatpickr($refs.datepicker, { minDate: 'today', dateFormat: 'Y-m-d H:i', time_24hr: true, enableTime: true });
    "
>

    <x:shopper-breadcrumb back="shopper.discounts.index">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.discounts.index')" title="Discounts" />
    </x:shopper-breadcrumb>

    <x-shopper-heading class="mt-3">
        <x-slot name="title">
            {{ __('Create discount code') }}
        </x-slot>

        <x-slot name="action">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                {{ __('Save') }}
            </x-shopper-button>
        </x-slot>
    </x-shopper-heading>

    <div class="mt-6 grid gap-4 sm:grid-cols-6 sm:gap-6">
        <div class="lg:col-span-4 space-y-5">
            <div class="bg-white p-4 sm:p-5 shadow rounded-md dark:bg-gray-800">
                <div class="w-full mb-3">
                    <div class="flex items-center justify-between">
                        <x-shopper-label for="code" :value="__('Code')" />
                        <button wire:click="generate" type="button" class="text-blue-600 text-sm leading-5 hover:text-blue-500 dark:text-blue-500">{{ __('Generate code') }}</button>
                    </div>
                    <div class="mt-4 relative rounded-md shadow-sm">
                        <x-shopper-input.text wire:model.debounce.350ms="code" id="code" type="text" placeholder="{{ __('Eg.: NOELCMR900') }}" autocomplete="off" />
                        @error('code')
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @enderror
                    </div>
                    @error('code')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <p class="mt-2 text-sm text-gray-500 leading-5 dark:text-gray-400">{{ __('Customers will enter this discount code at checkout.') }}</p>
            </div>
            <div class="bg-white divide-y divide-gray-200 shadow rounded-md dark:bg-gray-800 dark:divide-gray-700">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base leading-5 font-medium text-gray-900 dark:text-white">{{ __('Types') }}</h4>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center">
                                <x-shopper-input.radio wire:model.debounce.350ms="type" id="percentage" value="percentage" name="type" />
                                <label for="percentage" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">{{ __('Percentage') }}</span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <x-shopper-input.radio wire:model.debounce.350ms="type" id="amount" value="fixed_amount" name="type" />
                                <label for="amount" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">{{ __('Fixed amount') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <x-shopper-label for="value" :value="__('Value')" />
                        <div class="mt-1 relative rounded-md shadow-sm w-full sm:w-64">
                            <x-shopper-input.text wire:model="value" id="value" type="text" autocomplete="off" />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm sm:leading-5 dark:text-gray-400">
                                    {{ $type === 'percentage' ? '%' : shopper_currency() }}
                                </span>
                            </div>
                        </div>
                        @error('value')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base leading-5 font-medium text-gray-900 dark:text-white">{{ __('Applies To') }}</h4>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center">
                                <x-shopper-input.radio wire:model.debounce.350ms="apply" id="order" value="order" name="apply" />
                                <label for="order" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-gray-700 dark:text-gray-300">{{ __('Entire order') }}</span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <x-shopper-input.radio wire:model.debounce.350ms="apply" id="product" value="products" name="apply" />
                                <label for="product" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-gray-700 dark:text-gray-300">{{ __('Specific products') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @if($apply === 'products')
                        <div class="mt-4">
                            <span class="inline-flex rounded-md shadow-sm">
                                <x-shopper-default-button @click="modal = true" type="button">
                                    {{ __('Select products') }}
                                </x-shopper-default-button>
                            </span>
                        </div>
                        @if(count($productsDetails) > 0)
                            <div class="mt-2 divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($productsDetails as $key => $productDetail)
                                    <div class="flex items-center justify-between py-2">
                                        <div class="flex items-center">
                                            @if($productDetail['image'] !== null)
                                                <span class="flex-shrink-0 h-10 w-10 rounded-md overflow-hidden">
                                                    <img class="object-cover object-center w-full h-full block" src="{{ $productDetail['image'] }}" alt="" />
                                                </span>
                                            @else
                                                <span class="flex items-center justify-center h-10 w-10 bg-gray-100 text-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-500">
                                                    <x-heroicon-o-photograph class="w-8 h-8" />
                                                </span>
                                            @endif
                                            <p class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400">
                                                {{ $productDetail['name'] }}
                                            </p>
                                        </div>
                                        <button wire:key="product_{{ $loop->index }}" wire:click="removeProduct({{ $key }}, {{ $productDetail['id'] }})" type="button" class="text-gray-500 text-sm font-medium inline-flex items-center dark:text-gray-400">
                                            <x-heroicon-s-x class="h-5 w-5" />
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
                <div class="p-4 sm:p-5">
                    <div>
                        <h4 class="text-base leading-5 font-medium text-gray-900 dark:text-white">{{ __('Minimum requirements') }}</h4>
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center">
                                <x-shopper-input.radio wire:model.lazy="minRequired" id="none" value="none" name="min" />
                                <label for="none" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 text-gray-700 dark:text-gray-300">{{ __('None') }}</span>
                                </label>
                            </div>
                            <div>
                                <div class="flex items-center">
                                    <x-shopper-input.radio wire:model.lazy="minRequired" id="price" value="price" name="min" />
                                    <label for="price" class="ml-3 cursor-pointer">
                                        <span class="block text-sm leading-5 text-gray-700 dark:text-gray-300">{{ __('Minimum purchase amount (:currency)', ['currency' => shopper_currency()]) }}</span>
                                    </label>
                                </div>
                                @if($minRequired === 'price')
                                    <div class="mt-2 relative rounded-md shadow-sm w-full sm:w-64">
                                        <x-shopper-input.text wire:model.lazy="minRequiredValue" aria-label="{{ __('Min Required Value') }}" type="text" autocomplete="off" class="sm:w-64" />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                                                {{ shopper_currency() }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Applies only to selected products.') }}</p>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center">
                                    <x-shopper-input.radio wire:model.debounce.350ms="minRequired" id="quantity" value="quantity" name="min" />
                                    <label for="quantity" class="ml-3 cursor-pointer">
                                        <span class="block text-sm leading-5 text-gray-700 dark:text-gray-300">{{ __('Minimum quantity of items') }}</span>
                                    </label>
                                </div>
                                @if($minRequired === 'quantity')
                                    <div class="mt-2 relative rounded-md shadow-sm w-full sm:w-64">
                                        <x-shopper-input.text wire:model.lazy="minRequiredValue" aria-label="{{ __('Min Required Value') }}" type="number" autocomplete="off" class="sm:w-64" />
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Applies only to selected products.') }}</p>
                                @endif
                            </div>
                        </div>
                        @error('minRequiredValue')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base leading-5 font-medium text-gray-900 dark:text-white">{{ __('Customer eligibility') }}</h4>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center">
                                <x-shopper-input.radio wire:model.lazy="eligibility" id="everyone" value="everyone" />
                                <label for="everyone" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">{{ __('Everyone') }}</span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <x-shopper-input.radio wire:model.lazy="eligibility" id="customer" value="customers" />
                                <label for="customer" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">{{ __('Specific customers') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @if($eligibility === 'customers')
                        <div class="mt-4">
                            <span class="inline-flex rounded-md shadow-sm">
                                <x-shopper-default-button @click="show = true" type="button">
                                    {{ __('Select customers') }}
                                </x-shopper-default-button>
                            </span>
                        </div>
                        @if(count($customersDetails) > 0)
                            <div class="mt-2 divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($customersDetails as $key => $customerDetail)
                                    <div class="flex items-center justify-between py-2">
                                        <div class="flex items-center space-x-3">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $customerDetail['name'] }}</span>
                                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ $customerDetail['email'] }}</span>
                                        </div>
                                        <button wire:key="customer_{{ $loop->index }}" wire:click="removeCustomer({{ $key }}, {{ $customerDetail['id'] }})" type="button" class="text-gray-500 text-sm font-medium inline-flex items-center dark:text-gray-400">
                                            <x-heroicon-s-x class="h-5 w-5" />
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <div class="p-4 bg-white shadow rounded-md sm:p-5 dark:bg-gray-800">
                <h4 class="text-base leading-6 font-medium text-gray-900 dark:text-white">{{ __('Usage limits') }}</h4>
                <div class="mt-5 space-y-4">
                    <div>
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <x-shopper-input.checkbox wire:model.lazy="usage_number" id="usage_number" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <x-shopper-label for="usage_number" class="text-gray-500 cursor-pointer dark:text-gray-400" :value="__('Limit number of times this discount can be used in total')" />
                            </div>
                        </div>
                        @if($usage_number)
                            <div class="mt-2">
                                <div class="relative rounded-md shadow-sm w-full sm:w-64">
                                    <x-shopper-input.text wire:model.lazy="usage_limit" aria-label="{{ __('Usage limit value') }}" type="number" min="1" step="1" autocomplete="off" class="sm:w-64" />
                                </div>
                                @error('usage_limit')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <x-shopper-input.checkbox wire:model.lazy="usage_limit_per_user" id="usage_limit_per_user" />
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <x-shopper-label for="usage_limit_per_user" class="text-gray-500 cursor-pointer dark:text-gray-400" :value="__('Limit to one use per customer')" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-white shadow rounded-md sm:p-5 dark:bg-gray-800">
                <h4 class="text-base leading-6 font-medium text-gray-900 dark:text-white">{{ __('Active dates') }}</h4>
                <div class="mt-4 grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-1">
                        <x-shopper-label for="dateStart" :value="__('Start date')" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-o-calendar class="h-5 w-5 text-gray-400" />
                            </div>
                            <x-shopper-input.text wire:model.lazy="dateStart" id="dateStart" type="text" class="pl-10 date" readonly autocomplete="off" placeholder="{{ __('Choose start date period') }}" />
                        </div>
                        @error('dateStart')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-1">
                        <x-shopper-label for="dateEnd" :value="__('End At')" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-o-calendar class="h-5 w-5 text-gray-400" />
                            </div>
                            <x-shopper-input.text wire:model.lazy="dateEnd" id="dateEnd" x-ref="datepicker" type="text" class="pl-10" readonly autocomplete="off" placeholder="{{ __('Choose end date') }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-2">
            <aside class="sticky top-6">
                <div class="space-y-5">
                    <div class="p-4 bg-white shadow-md rounded-md sm:p-5 dark:bg-gray-800">
                        <h4 class="font-medium text-base text-gray-900 dark:text-white">{{ __('Summary') }}</h4>
                        @if($this->isEmpty())
                            <p class="text-gray-500 text-sm mt-4 dark:text-gray-400">{{ __('No information entered yet.') }}</p>
                        @else
                            @if($code !== '') <p class="text-base mt-4 font-bold text-gray-700 leading-6 dark:text-gray-300">{{ $code }}</p> @endif
                            <ul class="list-disc list-inside mt-4 space-y-1 text-sm text-gray-500 dark:text-gray-400">
                                @if($value !== '' && (int) $value > 0)
                                    <li>
                                        {{ $type === 'percentage' ? $value . ' %' : $this->formattedPrice($value) }}
                                        <span>{{ __('of') }} {{ $apply === 'order' ? __('entire order') : $this->getProductSize() }}</span>
                                    </li>
                                @endif
                                @if($minRequiredValue !== '' && (int) $minRequiredValue > 0 && $minRequired !== 'none')
                                    <li>
                                        <span>{{ __('Minimum purchase of') }}</span>
                                        {{ $minRequired === 'quantity' ?  __(':count items', ['count' => $minRequiredValue]) : $this->formattedPrice($minRequiredValue) }}
                                    </li>
                                @endif
                                @if($this->getCustomSize() !== null)
                                    <li>
                                        <span>{{ $this->getCustomSize() }}</span>
                                    </li>
                                @endif
                                @if($this->getUsageLimitMessage() !== null)
                                    <li>
                                        <span>{{ $this->getUsageLimitMessage() }}</span>
                                    </li>
                                @endif
                                @if($this->getDateWord() !== null)
                                    <li>
                                        <span>{{ $this->getDateWord() }}</span>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                    <div class="p-4 bg-white shadow-md rounded-md sm:p-5 dark:bg-gray-800">
                        <h4 class="text-gray-900 font-medium text-base leading-6 dark:text-white">{{ __('Visibility') }}</h4>
                        <p class="text-sm mt-4 font-normal text-gray-500 leading-5 dark:text-gray-400">{{ __('Setup discount visibility for the customers.') }}</p>
                        <div class="mt-3 px-3 py-2.5 bg-blue-50 rounded-md text-blue-600 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="h-8 w-8 flex items-center justify-center rounded-md bg-blue-600 flex-shrink-0">
                                    <x-heroicon-o-eye class="h-5 w-5 text-white" />
                                </span>
                                <span class="font-semibold ml-3 text-sm">{{ __('Visible') }}</span>
                            </div>
                            <div>
                                <span wire:model="is_active" role="checkbox" tabindex="0" x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': !on, 'bg-blue-600': on }" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-blue bg-gray-200">
                                    <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                    <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200 translate-x-0"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div class="mt-6 pt-5 pb-10 border-t border-gray-200 dark:border-gray-700">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                {{ __('Save') }}
            </x-shopper-button>
        </div>
    </div>

    @if($apply === 'products')
        <div
            x-cloak
            x-show="modal"
            class="fixed bottom-0 z-100 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center"
            x-on:products-added.window="modal = false"
        >
            <div
                x-cloak
                x-show="modal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity"
            >
                <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
            </div>
            <div
                x-cloak
                x-show="modal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="bg-white rounded-md overflow-hidden shadow-xl transform transition-all sm:max-w-2xl sm:w-full dark:bg-gray-800"
            >
                <div>
                    <div class="sm:flex sm:items-start px-4 sm:px-6 py-4">
                        <div class="text-center sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-700 dark:text-gray-300">{{ __('Add Products') }}</h3>
                        </div>
                    </div>
                    <div class="py-2 px-4 sm:px-6 border-t border-gray-100 dark:border-gray-700">
                        <label for="search" class="sr-only">{{ __('Search') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-o-search class="h-5 w-5 text-gray-400" />
                            </div>
                            <x-shopper-input.text id="search" type="search" wire:model.debounce.350ms="searchProduct" autocomplete="off" class="pl-10 pr-6" placeholder="{{ __('Search product by name') }}" />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <x-shopper-loader wire:loading wire:target="searchProduct" class="text-blue-600" />
                            </div>
                        </div>
                    </div>
                    <div class="my-2 divide-y divide-gray-200 h-80 overflow-auto dark:divide-gray-700">
                        @foreach($products as $product)
                            <label for="product_{{ $product->id }}" class="flex items-center py-3 cursor-pointer hover:bg-gray-50 px-4 sm:px-6 focus:bg-gray-50 dark:hover:bg-gray-700 dark:focus:bg-gray-700">
                                <span class="mr-4">
                                    <x-shopper-input.checkbox id="product_{{ $product->id }}" aria-label="{{ __('Product') }}" wire:model="selectedProducts" value="{{ $product->id }}" />
                                </span>
                                <span class="flex flex-1 items-center justify-between">
                                    <span class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ $product->name }}</span>
                                    <span class="flex items-center space-x-2">
                                        <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">{{ __(':stock available', ['stock' => $product->stock]) }}</span>
                                        <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">{{ $product->formattedPrice }}</span>
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <x-shopper-button wire:click="addProducts" wire.loading.attr="disabled" type="button">
                            <x-shopper-loader wire:loading wire:target="addProducts" class="text-white" />
                            {{ __('Add Selected Products') }}
                        </x-shopper-button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                        <x-shopper-default-button @click="modal = false;" type="button">
                            {{ __('Cancel') }}
                        </x-shopper-default-button>
                    </span>
                </div>
            </div>
        </div>
    @endif

    @if($eligibility === 'customers')
        <div
            x-cloak
            x-show="show"
            class="fixed bottom-0 z-100 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center"
            x-on:customers-added.window="show = false"
        >
            <div
                x-cloak
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity"
            >
                <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
            </div>
            <div
                x-cloak
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="bg-white rounded-md overflow-hidden shadow-xl transform transition-all sm:max-w-2xl sm:w-full dark:bg-gray-800"
            >
                <div>
                    <div class="sm:flex sm:items-start px-4 sm:px-6 py-4">
                        <div class="text-center sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-700 dark:text-gray-300">{{ __('Add Customers') }}</h3>
                        </div>
                    </div>
                    <div class="py-2 px-4 sm:px-6 border-t border-gray-100 dark:border-gray-700">
                        <label for="search" class="sr-only">{{ __('Search') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-o-search class="h-5 w-5 text-gray-400" />
                            </div>
                            <x-shopper-input.text id="search" type="search" wire:model.debounce.350ms="searchCustomer" autocomplete="off" class="pl-10 pr-6" placeholder="{{ __('Search customer by name') }}" />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <x-shopper-loader wire:loading wire:target="searchCustomer" class="text-blue-600" />
                            </div>
                        </div>
                    </div>
                    <div class="my-2 divide-y divide-gray-200 h-80 overflow-auto dark:divide-gray-700">
                        @foreach($customers as $customer)
                            <label for="customer_{{ $customer->id }}" class="flex items-center py-3 cursor-pointer hover:bg-gray-50 px-4 sm:px-6 focus:bg-gray-50 dark:hover:bg-gray-700 dark:focus:bg-gray-700">
                                <span class="mr-4">
                                    <x-shopper-input.checkbox id="customer_{{ $customer->id }}" aria-label="{{ __('Customer') }}" wire:model.lazy="selectedCustomers" value="{{ $customer->id }}" />
                                </span>
                                <div class="flex flex-1 items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-shrink-0">
                                            <img class="h-8 w-8 rounded-full" src="{{ $customer->picture }}" alt="{{ $customer->email }}">
                                        </div>
                                        <span class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ $customer->full_name }}</span>
                                    </div>
                                    <span class="flex items-center space-x-2">
                                        <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">{{ $customer->email }}</span>
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <x-shopper-button wire:click="addCustomers" wire.loading.attr="disabled" type="button">
                            <x-shopper-loader wire:loading wire:target="addCustomers" class="text-white" />
                            {{ __('Add Selected Customers') }}
                        </x-shopper-button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                        <x-shopper-default-button @click="show = false;" type="button">
                            {{ __('Cancel') }}
                        </x-shopper-default-button>
                    </span>
                </div>
            </div>
        </div>
    @endif

</div>
