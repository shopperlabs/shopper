@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

<div>
    <x-shopper-breadcrumb back="shopper.products.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.products.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Products') }}</a>
    </x-shopper-breadcrumb>

    <div class="mt-3 pb-5 border-b border-gray-200 space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
        <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            {{ __("Create product") }}
        </h3>
        <div class="flex">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                {{ __("Save") }}
            </x-shopper-button>
        </div>
    </div>

    <div class="mt-6 grid sm:grid-cols-6 gap-4 lg:gap-6">
        <div class="sm:col-span-4 space-y-5">
            <div class="bg-white rounded-lg shadow p-4 sm:p-5">
                <div>
                    <x-shopper-input.group label="Name" for="name" isRequired :error="$errors->first('name')">
                        <x-shopper-input.text wire:model="name" id="name" type="text" autocomplete="off" placeholder="{{ __('Apple, Nike, Samsung...') }}" />
                    </x-shopper-input.group>
                </div>
                <div class="mt-5 border-t border-gray-200 pt-4">
                    <x-shopper-input.group label="Description" for="description">
                        <x-shopper-input.rich-text wire:model.lazy="description" id="description" />
                    </x-shopper-input.group>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 sm:p-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-gray-900">{{ __("Product Media") }}</h4>
                <div class="mt-4">
                    <x-shopper-input.multiple-upload id="file" wire:model="files" :files="$files" :error="$errors->first('files.*')" />
                </div>
            </div>
            <div class="bg-white rounded-lg shadow pt-4 sm:pt-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-gray-900 px-4 sm:px-5">{{ __("Pricing") }}</h4>
                <div class="divide-y divide-gray-200">
                    <div class="grid gap-4 sm:grid-cols-6 sm:gap-6 p-4 sm:p-5">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="price_amount" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Price amount") }}</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input wire:model="price_amount" id="price_amount" type="number" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" min="0" autocomplete="off" placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="old_price_amount" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Compare at price") }}</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input wire:model="old_price_amount" id="old_price_amount" type="number" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" min="0" autocomplete="off" placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 p-4 sm:p-5">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="cost_amount" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Cost per item") }}</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input wire:model="cost_amount" id="cost_amount" type="number" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" min="0" autocomplete="off" placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">{{ __("Customers won’t see this.") }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow pt-4 sm:pt-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-gray-900 px-4 sm:px-5">{{ __("Inventory") }}</h4>
                <div class="divide-y divide-gray-200">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 p-4 sm:p-5">
                        <div class="sm:col-span-1">
                            <x-shopper-input.group label="SKU (Stock Keeping Unit)" for="sku" :error="$errors->first('sku')">
                                <x-shopper-input.text wire:model="sku" id="sku" type="text" autocomplete="off" />
                            </x-shopper-input.group>
                        </div>
                        <div class="sm:col-span-1">
                            <x-shopper-input.group label="Barcode (ISBN, UPC, GTIN, etc.)" for="barcode" :error="$errors->first('barcode')">
                                <x-shopper-input.text wire:model="barcode" id="barcode" type="text" autocomplete="off" />
                            </x-shopper-input.group>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 p-4 sm:p-5">
                        @if($inventories->count() <= 1)
                            <div class="sm:col-span-1">
                                <x-shopper-input.group label="Quantity" for="quantity">
                                    <x-shopper-input.text wire:model="quantity.{{ $inventories->first()->id }}" id="quantity" type="number" min="0" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                        @endif
                        <div class="sm:col-span-1">
                            <x-shopper-input.group label="Safety Stock" for="security_stock" helpText="The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock.">
                                <x-shopper-input.text wire:model="securityStock" id="security_stock" type="number" min="1" step="1" autocomplete="off" />
                            </x-shopper-input.group>
                        </div>
                    </div>
                    @if($inventories->count() > 1)
                        <div class="p-4 sm:p-5">
                            <div class="flex items-center justify-between">
                                <h4 class="block text-sm font-medium leading-5 text-gray-900">{{ __("Quantity Inventory") }}</h4>
                                <a href="{{ route('shopper.settings.inventories.index') }}" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out">{{ __("Manage Inventories") }}</a>
                            </div>
                            <div class="mt-4 divide-y divide-gray-200">
                                <div class="grid grid-cols-3 py-4">
                                    <div class="col-span-2">
                                        <span class="text-sm leading-5 font-semibold text-gray-900 uppercase">{{ __('Inventory name') }}</span>
                                    </div>
                                    <div class="col-span-1 pl-4 flex justify-end">
                                        <span class="text-sm leading-5 font-semibold text-gray-900 uppercase">{{ __('Available') }}</span>
                                    </div>
                                </div>
                                @foreach($inventories as $inventory)
                                    <div class="grid grid-cols-3 py-4" wire:key="inventory-{{ $inventory->id }}">
                                        <div class="col-span-2">
                                            <span class="text-sm leading-5 text-gray-600">{{ $inventory->name }}</span>
                                        </div>
                                        <div class="col-span-1 pl-4 flex justify-end">
                                            <input wire:model="quantity.{{ $inventory->id }}" type="number" class="form-input block w-24 transition duration-150 ease-in-out sm:text-sm sm:leading-5" aria-label="{{ __("Inventory quantity") }}"  min="0" autocomplete="off" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="bg-white rounded-lg shadow overflow-hidden divide-y divide-gray-200">
                <div class="p-4 sm:p-5">
                    <h4 class="block text-base font-medium leading-6 text-gray-900">{{ __("Shipping") }}</h4>
                    <div class="mt-5 space-y-4">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input wire:model="backorder" id="backorder" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <label for="backorder" class="font-medium text-gray-700 cursor-pointer">{{ __("This product can be returned") }}</label>
                                <p class="text-gray-500">{{ __("Users have the option of returning this product if there is a problem or dissatisfaction.") }}</p>
                            </div>
                        </div>
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input wire:model="requiresShipping" id="required_shipping" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <label for="required_shipping" class="font-medium text-gray-700 cursor-pointer">{{ __("This product will be shipped") }}</label>
                                <p class="text-gray-500">{{ __("Reassure to fill in the information concerning the shipment of the product.") }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @if($requiresShipping)
                    <div class="p-4 sm:p-5">
                        <h4 class="block text-base font-medium leading-6 text-gray-900">{{ __("Weight and Dimension") }}</h4>
                        <p class="text-sm text-gray-500 leading-5">{{ __("Used to calculate shipping charges during checkout and to label prices during order processing.") }}</p>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2 sm:gap-6 sm:gap-y-4">
                            <div class="sm:col-span-1">
                                <label for="WidthValue" class="block text-sm leading-5 font-medium text-gray-700">{{ __("Width") }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input wire:model="widthValue" id="WidthValue" type="text" class="form-input block w-full pl-3 pr-12 sm:text-sm sm:leading-5" placeholder="0" />
                                    <div class="absolute inset-y-0 right-0 flex items-center">
                                        <select wire:model="WidthUnit" aria-label="{{ __("Width Unit") }}" class="form-select h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm sm:leading-5">
                                            <option value="cm">cm</option>
                                            <option value="m">m</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="sm:col-span-1">
                                <label for="heightValue" class="block text-sm leading-5 font-medium text-gray-700">{{ __("Height") }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input wire:model="heightValue" id="heightValue" type="text" class="form-input block w-full pl-3 pr-12 sm:text-sm sm:leading-5" placeholder="0" />
                                    <div class="absolute inset-y-0 right-0 flex items-center">
                                        <select wire:model="heightUnit" aria-label="{{ __("height Unit") }}" class="form-select h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm sm:leading-5">
                                            <option value="cm">cm</option>
                                            <option value="m">m</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="sm:col-span-1">
                                <label for="weightValue" class="block text-sm leading-5 font-medium text-gray-700">{{ __("Weight") }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input wire:model="weightValue" id="weightValue" type="text" class="form-input block w-full pl-3 pr-12 sm:text-sm sm:leading-5" placeholder="0" />
                                    <div class="absolute inset-y-0 right-0 flex items-center">
                                        <select wire:model="weightUnit" aria-label="{{ __("weight Unit") }}" class="form-select h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm sm:leading-5">
                                            <option value="kg">kg</option>
                                            <option value="g">g</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="sm:col-span-1">
                                <label for="VolumeValue" class="block text-sm leading-5 font-medium text-gray-700">{{ __("Volume") }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input wire:model="volumeValue" id="VolumeValue" type="text" class="form-input block w-full pl-3 pr-12 sm:text-sm sm:leading-5" placeholder="0" />
                                    <div class="absolute inset-y-0 right-0 flex items-center">
                                        <select wire:model="VolumeUnit" aria-label="{{ __("Volume Unit") }}" class="form-select h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm sm:leading-5">
                                            <option value="l">l</option>
                                            <option value="ml">ml</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="bg-white rounded-lg shadow-md divide-y divide-gray-200">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __("Search engine listing preview") }}</h3>
                        @if(! $updateSeo)
                            <button wire:click="updateSeo" type="button" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out">{{ __("Edit SEO preview") }}</button>
                        @endif
                    </div>
                    <div class="mt-4">
                        @if(! $updateSeo)
                            <p class="text-sm leading-5 text-gray-500">{{ __("Add a title and description to see how this product might appear in a search engine listing.") }}</p>
                        @else
                            <div class="flex flex-col">
                                <h3 class="text-base text-blue-800 font-medium leading-6">{{ $seoTitle }}</h3>
                                <span class="mt-1 text-green-600 text-sm leading-5">{{ env('APP_URL') }}/products/{{ str_slug($name) }}</span>
                                <p class="mt-1 text-gray-500 text-sm leading-5">{{ str_limit($seoDescription, 160) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @if($updateSeo)
                    <div class="px-4 py-5 sm:px-6 space-y-5">
                        <div>
                            <label for="seo_title" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Title") }}</label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <input
                                    wire:model="seoTitle"
                                    id="seo_title"
                                    type="text"
                                    class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                    autocomplete="off"
                                />
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between">
                                <label for="seo_description" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Description") }}</label>
                                <span class="ml-4 text-sm leading-5 text-gray-500">{{ __("160 characters") }}</span>
                            </div>
                            <div class="mt-1 rounded-md shadow-sm">
                                <textarea
                                    wire:model="seoDescription"
                                    id="seo_description"
                                    rows="4"
                                    class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                >
                                </textarea>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="sm:col-span-2">
            <aside class="space-y-5">
                <div class="bg-white rounded-lg shadow overflow-hidden divide-y divide-gray-200">
                    <div class="p-4 sm:p-5">
                        <h4 class="text-sm leading-5 text-gray-700 font-medium">{{ __("Product status") }}</h4>
                        <div class="mt-4 px-3 py-2.5 bg-blue-50 rounded-md text-blue-600 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="h-8 w-8 flex items-center justify-center rounded-md bg-blue-600 flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </span>
                                <span class="font-semibold ml-3 text-sm">{{ __("Visible") }}</span>
                            </div>
                            <div>
                                <span wire:model="isVisible" x-data="{ on: @entangle('isVisible') }" role="checkbox" tabindex="0"x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-bind:class="{ 'bg-gray-100': !on, 'bg-blue-600': on }" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-blue bg-gray-200">
                                    <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                    <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200 translate-x-0"></span>
                                </span>
                            </div>
                        </div>
                        <p class="mt-2 text-gray-500 leading-5 text-sm">
                            {{ __("This product will be hidden from all sales channels.") }}
                        </p>
                    </div>
                    <div
                        x-data
                        x-init="flatpickr($refs.input, {dateFormat: 'Y-m-d'});"
                        class="p-4 sm:p-5"
                    >
                        <label for="date" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Product availability") }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input wire:model="publishedAt" x-ref="input" id="date" type="text" class="form-input block w-full pl-10 sm:text-sm sm:leading-5" placeholder="{{ __("Choose a date") }}" readonly />
                        </div>
                        @if($publishedAt)
                            <div class="mt-2 flex items-start">
                                <div class="mt-1 flex-shrink-0 w-2.5 h-2.5 rounded-full bg-blue-600"></div>
                                <p class="ml-2.5 text-sm text-gray-500 leading-5">
                                    {{ __("Will be published on:") }} <br>
                                    {{ $publishedAtFormatted }}
                                </p>
                            </div>
                        @else
                            <p class="mt-2 text-sm leading-5 text-gray-500">
                                {{ __("Specify a publication date so that your product are scheduled on your store.") }}
                            </p>
                        @endif
                    </div>
                </div>
                <div x-data="radioGroup()" class="bg-white rounded-lg shadow overflow-hidden p-4 sm:p-5">
                    <h4 class="block text-base font-medium leading-6 text-gray-900">{{ __("Product type") }}</h4>
                    <div x-ref="radiogroup" class="mt-5 space-y-4">
                        <div :class="{ 'border-gray-200': !(active === 0), 'bg-blue-50 border-blue-200 z-10': active === 0 }" class="relative border rounded-md p-4 flex bg-blue-50 border-blue-200 z-10">
                            <div class="flex items-center h-5">
                                <input wire:model="type" id="product-type-0" name="type" value="deliverable" type="radio" @click="select(0)" @keydown.space="select(0)" @keydown.arrow-up="onArrowUp(0)" @keydown.arrow-down="onArrowDown(0)" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out cursor-pointer" checked="">
                            </div>
                            <label for="product-type-0" class="ml-3 flex flex-col cursor-pointer">
                                <span :class="{ 'text-blue-900': active === 0, 'text-gray-900': !(active === 0) }" class="block text-sm leading-5 font-medium text-blue-900">
                                    {{ __("Deliverable") }}
                                </span>
                                <span :class="{ 'text-blue-700': active === 0, 'text-gray-500': !(active === 0) }" class="mt-0.5 block text-xs leading-4 text-blue-700">
                                    {{ __("This product can be delivered to a customer.") }}
                                </span>
                            </label>
                        </div>

                        <div :class="{ 'border-gray-200': !(active === 1), 'bg-blue-50 border-blue-200 z-10': active === 1 }" class="relative border rounded-md border-gray-200 p-4 flex">
                            <div class="flex items-center h-5">
                                <input wire:model="type" id="product-type-1" name="type" value="downloadable" type="radio" @click="select(1)" @keydown.space="select(1)" @keydown.arrow-up="onArrowUp(1)" @keydown.arrow-down="onArrowDown(1)" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out cursor-pointer">
                            </div>
                            <label for="product-type-1" class="ml-3 flex flex-col cursor-pointer">
                                <span :class="{ 'text-blue-900': active === 1, 'text-gray-900': !(active === 1) }" class="block text-sm leading-5 font-medium text-gray-900">
                                    {{ __("Downloadable") }}
                                </span>
                                <span :class="{ 'text-blue-700': active === 1, 'text-gray-500': !(active === 1) }" class="mt-0.5 block text-xs leading-4 text-gray-500">
                                    {{ __("This product can be downloaded by a customer.") }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow">
                    <div class="px-4 pt-4 sm:px-5 sm:pt-5">
                        <h4 class="block text-base font-medium leading-6 text-gray-900">{{ __("Product associations") }}</h4>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="p-4 sm:p-5">
                            <label for="brand_id" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Brand") }}</label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <select wire:model="brand_id" id="brand_id" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                    <option>{{ __('Select brand') }}</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" @if($loop->first) selected @endif>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="p-4 sm:p-5">
                            <label for="categories" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Categories") }}</label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <select wire:model="category_ids" id="categories" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" multiple>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="p-4 sm:p-5">
                            <label for="collections" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Collections") }}</label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <select wire:model="collection_ids" id="collections" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" multiple>
                                    @foreach($collections as $collection)
                                        <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div class="mt-6 border-t border-gray-200 pt-5">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                {{ __("Save") }}
            </x-shopper-button>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        function radioGroup() {
            return {
                active: {{ $type === 'deliverable' ? 0 : 1 }},
                onArrowUp(index) {
                    this.select(this.active - 1 < 0 ? this.$refs.radiogroup.children.length - 1 : this.active - 1);
                },
                onArrowDown(index) {
                    this.select(this.active + 1 > this.$refs.radiogroup.children.length - 1 ? 0 : this.active + 1);
                },
                select(index) {
                    this.active = index;
                },
            };
        }
    </script>
@endpush
