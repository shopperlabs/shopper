@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

<div>
    <x-shopper-breadcrumb back="shopper.products.index">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.products.index')" title="Products" />
    </x-shopper-breadcrumb>

    <x-shopper-heading class="mt-3">
        <x-slot name="title">
            {{ __('Create product') }}
        </x-slot>

        <x-slot name="action">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                {{ __('Save') }}
            </x-shopper-button>
        </x-slot>
    </x-shopper-heading>

    <div class="mt-6 grid sm:grid-cols-6 gap-4 lg:gap-6">
        <div class="sm:col-span-4 space-y-5">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-5">
                <div>
                    <x-shopper-input.group label="Name" for="name" isRequired :error="$errors->first('name')">
                        <x-shopper-input.text wire:model="name" id="name" type="text" autocomplete="off" placeholder="{{ __('Apple, Nike, Samsung...') }}" />
                    </x-shopper-input.group>
                </div>
                <div class="mt-5 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <x-shopper-input.group label="Description" for="description">
                        <x-shopper-input.rich-text wire:model.lazy="description" id="description" />
                    </x-shopper-input.group>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white">{{ __('Product Media') }}</h4>
                <div class="mt-4">
                    <x-shopper-input.drag-upload id="file" wire:click="removeImage" wire:model="file" :file="$file" :error="$errors->first('file')" />
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow pt-4 sm:pt-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white px-4 sm:px-5">{{ __('Pricing') }}</h4>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="grid gap-4 sm:grid-cols-6 sm:gap-6 p-4 sm:p-5">
                        <x-shopper-input.group for="price_amount" label="Price amount" class="col-span-6 sm:col-span-3">
                            <x-shopper-input.text wire:model="price_amount" id="price_amount" type="number" min="0" autocomplete="off" placeholder="0.00" />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                            </div>
                        </x-shopper-input.group>
                        <x-shopper-input.group for="old_price_amount" label="Compare at price" class="col-span-6 sm:col-span-3">
                            <x-shopper-input.text wire:model="old_price_amount" id="old_price_amount" type="number" min="0" autocomplete="off" placeholder="0.00" />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                            </div>
                        </x-shopper-input.group>
                    </div>
                    <div class="grid grid-cols-6 gap-6 p-4 sm:p-5">
                        <x-shopper-input.group for="cost_amount" label="Cost per item" class="col-span-6 sm:col-span-3" helpText="Customers won’t see this.">
                            <x-shopper-input.text wire:model="cost_amount" id="cost_amount" type="number" min="0" autocomplete="off" placeholder="0.00" />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                            </div>
                        </x-shopper-input.group>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow pt-4 sm:pt-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white px-4 sm:px-5">{{ __('Inventory') }}</h4>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
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
                            @if($barcodeImage)
                                <div class="mt-2 rounded-sm w-auto flex-shrink-0">
                                    {!! $barcodeImage !!}
                                </div>
                            @endif
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
                                <h4 class="block text-sm font-medium leading-5 text-gray-900 dark:text-white">{{ __('Quantity Inventory') }}</h4>
                                <a href="{{ route('shopper.settings.inventories.index') }}" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out">{{ __("Manage Inventories") }}</a>
                            </div>
                            <div class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
                                <div class="grid grid-cols-3 py-4">
                                    <div class="col-span-2">
                                        <span class="text-sm leading-5 font-semibold text-gray-900 dark:text-white uppercase">{{ __('Inventory name') }}</span>
                                    </div>
                                    <div class="col-span-1 pl-4 flex justify-end">
                                        <span class="text-sm leading-5 font-semibold text-gray-900 dark:text-white uppercase">{{ __('Available') }}</span>
                                    </div>
                                </div>
                                @foreach($inventories as $inventory)
                                    <div class="grid grid-cols-3 py-4" wire:key="inventory-{{ $inventory->id }}">
                                        <div class="col-span-2">
                                            <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">{{ $inventory->name }}</span>
                                        </div>
                                        <div class="col-span-1 pl-4 flex justify-end">
                                            <x-shopper-input.text wire:model="quantity.{{ $inventory->id }}" wire:key="inventory-{{ $inventory->id }}" type="number" class="block w-24" aria-label="{{ __('Inventory quantity') }}"  min="0" autocomplete="off" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden divide-y divide-gray-200 dark:divide-gray-700">
                <div class="p-4 sm:p-5">
                    <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white">{{ __('Shipping') }}</h4>
                    <div class="mt-5 space-y-4">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <x-shopper-input.checkbox wire:model.lazy="backorder" id="backorder" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <x-shopper-label for="backorder" :value="__('This product can be returned')" />
                                <p class="text-gray-500 dark:text-gray-400">{{ __('Users have the option of returning this product if there is a problem or dissatisfaction.') }}</p>
                            </div>
                        </div>
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <x-shopper-input.checkbox wire:model.lazy="requiresShipping" id="required_shipping" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <x-shopper-label for="required_shipping" :value="__('This product will be shipped')" />
                                <p class="text-gray-500 dark:text-gray-400">{{ __('Reassure to fill in the information concerning the shipment of the product.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @if($requiresShipping)
                    <div class="p-4 sm:p-5">
                        <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white">{{ __('Weight and Dimension') }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-5">{{ __('Used to calculate shipping charges during checkout and to label prices during order processing.') }}</p>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2 sm:gap-6 sm:gap-y-4">
                            <x-shopper-input.group class="sm:col-span-1" label="Width">
                                <x-shopper-input.text wire:model="widthValue" id="WidthValue" type="text" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper-input.select wire:model="WidthUnit" aria-label="{{ __('Width Unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="cm">cm</option>
                                        <option value="m">m</option>
                                    </x-shopper-input.select>
                                </div>
                            </x-shopper-input.group>
                            <x-shopper-input.group class="sm:col-span-1" label="Height">
                                <x-shopper-input.text wire:model="heightValue" id="heightValue" type="text" class="pl-3 pr-12" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper-input.select wire:model="heightUnit" aria-label="{{ __('Height Unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="cm">cm</option>
                                        <option value="m">m</option>
                                    </x-shopper-input.select>
                                </div>
                            </x-shopper-input.group>
                            <x-shopper-input.group class="sm:col-span-1" label="Weight">
                                <x-shopper-input.text wire:model="weightValue" id="weightValue" type="text" class="block w-full pl-3 pr-12" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper-input.select wire:model="weightUnit" aria-label="{{ __('Weight Unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="kg">kg</option>
                                        <option value="g">g</option>
                                    </x-shopper-input.select>
                                </div>
                            </x-shopper-input.group>
                            <x-shopper-input.group class="sm:col-span-1" label="Volume">
                                <x-shopper-input.text wire:model="volumeValue" id="VolumeValue" type="text" class="block w-full pl-3 pr-12" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper-input.select wire:model="VolumeUnit" aria-label="{{ __('Volume Unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="l">l</option>
                                        <option value="ml">ml</option>
                                    </x-shopper-input.select>
                                </div>
                            </x-shopper-input.group>
                        </div>
                    </div>
                @endif
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md divide-y divide-gray-200 dark:divide-gray-700">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('Search engine listing preview') }}</h3>
                        @if(! $updateSeo)
                            <button wire:click="updateSeo" type="button" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out">{{ __('Edit SEO preview') }}</button>
                        @endif
                    </div>
                    <div class="mt-4">
                        @if(! $updateSeo)
                            <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">{{ __('Add a title and description to see how this product might appear in a search engine listing.') }}</p>
                        @else
                            <div class="flex flex-col">
                                <h3 class="text-base text-blue-800 font-medium leading-6">{{ $seoTitle }}</h3>
                                <span class="mt-1 text-green-600 text-sm leading-5">{{ env('APP_URL') }}/products/{{ str_slug($name) }}</span>
                                <p class="mt-1 text-gray-500 dark:text-gray-400 text-sm leading-5">{{ str_limit($seoDescription, 160) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @if($updateSeo)
                    <div class="px-4 py-5 sm:px-6 space-y-5">
                        <x-shopper-input.group for="seo_title" label="Title">
                            <x-shopper-input.text wire:model="seoTitle" id="seo_title" type="text" autocomplete="off" />
                        </x-shopper-input.group>
                        <div>
                            <div class="flex items-center justify-between">
                                <x-shopper-label for="seo_description" :value="__('Description')" />
                                <span class="ml-4 text-sm leading-5 text-gray-500 dark:text-gray-400">{{ __('160 characters') }}</span>
                            </div>
                            <div class="mt-1 rounded-md shadow-sm">
                                <x-shopper-input.textarea wire:model.lazy="seoDescription" id="seo_description" />
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="sm:col-span-2">
            <aside class="space-y-5">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="p-4 sm:p-5">
                        <x-shopper-label value="{{ __('Product status') }}" />
                        <div class="mt-4 px-3 py-2.5 bg-blue-50 rounded-md text-blue-600 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="h-8 w-8 flex items-center justify-center rounded-md bg-blue-600 flex-shrink-0">
                                    <x-heroicon-o-eye class="h-5 w-5 text-white" />
                                </span>
                                <span class="font-semibold ml-3 text-sm">{{ __('Visible') }}</span>
                            </div>
                            <div>
                                <span wire:model="isVisible" x-data="{ on: @entangle('isVisible') }" role="checkbox" tabindex="0"x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': !on, 'bg-blue-600': on }" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-blue bg-gray-200">
                                    <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                    <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200 translate-x-0"></span>
                                </span>
                            </div>
                        </div>
                        <p class="mt-2 text-gray-500 dark:text-gray-400 leading-5 text-sm">
                            {{ __('This product will be hidden from all sales channels.') }}
                        </p>
                    </div>
                    <div
                        x-data
                        x-init="flatpickr($refs.input, {dateFormat: 'Y-m-d'});"
                        class="p-4 sm:p-5"
                    >
                        <x-shopper-label for="date" :value="__('Product availability')" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-o-calendar class="h-5 w-5 text-gray-400" />
                            </div>
                            <input wire:model="publishedAt" x-ref="input" id="date" type="text" class="w-full pl-10 block w-full dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 rounded-md shadow-sm border-gray-300 dark:border-gray-700 focus:border-blue-300 focus:ring focus:ring-blue-300 dark:focus:ring-offset-gray-900 focus:ring-opacity-50 sm:text-sm" placeholder="{{ __('Choose a date') }}" readonly />
                        </div>
                        @if($publishedAt)
                            <div class="mt-2 flex items-start">
                                <div class="mt-1 flex-shrink-0 w-2.5 h-2.5 rounded-full bg-blue-600"></div>
                                <p class="ml-2.5 text-sm text-gray-500 leading-5">
                                    {{ __('Will be published on:') }} <br>
                                    {{ $publishedAtFormatted }}
                                </p>
                            </div>
                        @else
                            <p class="mt-2 text-sm leading-5 text-gray-500">
                                {{ __('Specify a publication date so that your product are scheduled on your store.') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="px-4 pt-4 sm:px-5 sm:pt-5">
                        <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white">{{ __('Product associations') }}</h4>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <x-shopper-input.group class="p-4 sm:p-5" label="Brand" for="brand_id">
                            <x-shopper-input.select wire:model.lazy="brand_id" id="brand_id">
                                <option>{{ __('Select brand') }}</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </x-shopper-input.select>
                        </x-shopper-input.group>
                        <x-shopper-input.group class="p-4 sm:p-5" label="Categories" for="categories">
                            <x-shopper-input.select wire:model.lazy="category_ids" id="categories" :items="$categories" multiple />
                        </x-shopper-input.group>
                        <x-shopper-input.group class="p-4 sm:p-5" label="Collections" for="collections">
                            <x-shopper-input.select wire:model.lazy="collection_ids" id="collections" :items="$collections" multiple />
                        </x-shopper-input.group>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-5 pb-10">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper-loader wire:loading wire:target="store" />
                {{ __('Save') }}
            </x-shopper-button>
        </div>
    </div>
</div>
