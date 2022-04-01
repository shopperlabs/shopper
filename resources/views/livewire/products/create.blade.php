<div>
    <x-shopper-breadcrumb back="shopper.products.index">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
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
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow p-4 sm:p-5">
                <div>
                    <x-shopper-forms.group label="Name" for="name" isRequired :error="$errors->first('name')">
                        <x-shopper-forms.input wire:model.defer="name" id="name" type="text" autocomplete="off" placeholder="{{ __('Apple, Nike, Samsung...') }}" />
                    </x-shopper-forms.group>
                </div>
                <div class="mt-5 border-t border-secondary-200 dark:border-secondary-700 pt-4">
                    <x-shopper-forms.group label="Description" for="description">
                        <livewire:shopper-forms.trix :value="$description" />
                    </x-shopper-forms.group>
                </div>
            </div>
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow p-4 sm:p-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('Product Media') }}</h4>
                <div class="mt-4">
                    <livewire:shopper-forms.uploads.multiple />
                </div>
            </div>
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow pt-4 sm:pt-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white px-4 sm:px-5">{{ __('Pricing') }}</h4>
                <div class="divide-y divide-secondary-200 dark:divide-secondary-700">
                    <div class="grid gap-4 sm:grid-cols-6 sm:gap-6 p-4 sm:p-5">
                        <div class="col-span-6 sm:col-span-3">
                            <x-inputs.currency
                                :label="__('Price amount')"
                                placeholder="0.00"
                                wire:model.defer="price_amount"
                                class="dark:bg-secondary-700 dark:border-transparent dark:focus:border-primary-500 dark:text-white"
                                min="0"
                                thousands=","
                                decimal="."
                                :suffix="$currency"
                            />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-inputs.currency
                                :label="__('Compare at price')"
                                placeholder="0.00"
                                wire:model.defer="old_price_amount"
                                class="dark:bg-secondary-700 dark:border-transparent dark:focus:border-primary-500 dark:text-white"
                                min="0"
                                thousands=","
                                decimal="."
                                :suffix="$currency"
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 p-4 sm:p-5">
                        <div class="col-span-6 sm:col-span-3">
                            <x-inputs.currency
                                :label="__('Cost per item')"
                                placeholder="0.00"
                                wire:model.defer="cost_amount"
                                class="dark:bg-secondary-700 dark:border-transparent dark:focus:border-primary-500 dark:text-white"
                                min="0"
                                thousands=","
                                decimal="."
                                :suffix="$currency"
                                :hint="__('Customers won’t see this.')"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow pt-4 sm:pt-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white px-4 sm:px-5">{{ __('Inventory') }}</h4>
                <div class="divide-y divide-secondary-200 dark:divide-secondary-700">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 p-4 sm:p-5">
                        <div class="sm:col-span-1">
                            <x-shopper-forms.group label="SKU (Stock Keeping Unit)" for="sku" :error="$errors->first('sku')">
                                <x-shopper-forms.input wire:model.defer="sku" id="sku" type="text" autocomplete="off" />
                            </x-shopper-forms.group>
                        </div>
                        <div class="sm:col-span-1">
                            <x-shopper-forms.group label="Barcode (ISBN, UPC, GTIN, etc.)" for="barcode" :error="$errors->first('barcode')">
                                <x-shopper-forms.input wire:model.defer="barcode" id="barcode" type="text" autocomplete="off" />
                            </x-shopper-forms.group>
                            @if($barcodeImage)
                                <div class="mt-2 rounded-sm w-auto shrink-0">
                                    {!! $barcodeImage !!}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 p-4 sm:p-5">
                        @if($inventories->count() <= 1)
                            <div class="sm:col-span-1">
                                <x-shopper-forms.group label="Quantity" for="quantity">
                                    <x-shopper-forms.input wire:model.defer="quantity.{{ $inventories->first()->id }}" id="quantity" type="number" min="0" autocomplete="off" />
                                </x-shopper-forms.group>
                            </div>
                        @endif
                        <div class="sm:col-span-1">
                            <x-shopper-forms.group label="Safety Stock" for="security_stock" helpText="The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock.">
                                <x-shopper-forms.input wire:model.defer="securityStock" id="security_stock" type="number" min="1" step="1" autocomplete="off" />
                            </x-shopper-forms.group>
                        </div>
                    </div>
                    @if($inventories->count() > 1)
                        <div class="p-4 sm:p-5">
                            <div class="flex items-center justify-between">
                                <h4 class="block text-sm font-medium leading-5 text-secondary-900 dark:text-white">{{ __('Quantity Inventory') }}</h4>
                                <a href="{{ route('shopper.settings.inventories.index') }}" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-primary-600 hover:text-primary-800 transition duration-150 ease-in-out">{{ __("Manage Inventories") }}</a>
                            </div>
                            <div class="mt-4 divide-y divide-secondary-200 dark:divide-secondary-700">
                                <div class="grid grid-cols-3 py-4">
                                    <div class="col-span-2">
                                        <span class="text-sm leading-5 font-semibold text-secondary-900 dark:text-white uppercase">{{ __('Inventory name') }}</span>
                                    </div>
                                    <div class="col-span-1 pl-4 flex justify-end">
                                        <span class="text-sm leading-5 font-semibold text-secondary-900 dark:text-white uppercase">{{ __('Available') }}</span>
                                    </div>
                                </div>
                                @foreach($inventories as $inventory)
                                    <div class="grid grid-cols-3 py-4" wire:key="inventory-{{ $inventory->id }}">
                                        <div class="col-span-2">
                                            <span class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ $inventory->name }}</span>
                                        </div>
                                        <div class="col-span-1 pl-4 flex justify-end">
                                            <x-shopper-forms.input wire:model.defer="quantity.{{ $inventory->id }}" wire:key="inventory-{{ $inventory->id }}" type="number" class="block w-24" aria-label="{{ __('Inventory quantity') }}"  min="0" autocomplete="off" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow overflow-hidden divide-y divide-secondary-200 dark:divide-secondary-700">
                <div class="p-4 sm:p-5">
                    <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('Shipping') }}</h4>
                    <div class="mt-5 space-y-4">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <x-shopper-forms.checkbox wire:model.defer="backorder" id="backorder" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <x-shopper-label for="backorder" :value="__('This product can be returned')" />
                                <p class="text-secondary-500 dark:text-secondary-400">{{ __('Users have the option of returning this product if there is a problem or dissatisfaction.') }}</p>
                            </div>
                        </div>
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <x-shopper-forms.checkbox wire:model.lazy="requiresShipping" id="required_shipping" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <x-shopper-label for="required_shipping" :value="__('This product will be shipped')" />
                                <p class="text-secondary-500 dark:text-secondary-400">{{ __('Reassure to fill in the information concerning the shipment of the product.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @if($requiresShipping)
                    <div class="p-4 sm:p-5">
                        <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('Weight and Dimension') }}</h4>
                        <p class="text-sm text-secondary-500 dark:text-secondary-400 leading-5">{{ __('Used to calculate shipping charges during checkout and to label prices during order processing.') }}</p>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2 sm:gap-6 sm:gap-y-4">
                            <x-shopper-forms.group class="sm:col-span-1" label="Width">
                                <x-shopper-forms.input wire:model.defer="widthValue" id="WidthValue" type="text" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper-forms.select wire:model.defer="WidthUnit" aria-label="{{ __('Width Unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="cm">cm</option>
                                        <option value="m">m</option>
                                    </x-shopper-forms.select>
                                </div>
                            </x-shopper-forms.group>
                            <x-shopper-forms.group class="sm:col-span-1" label="Height">
                                <x-shopper-forms.input wire:model.defer="heightValue" id="heightValue" type="text" class="pl-3 pr-12" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper-forms.select wire:model.defer="heightUnit" aria-label="{{ __('Height Unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="cm">cm</option>
                                        <option value="m">m</option>
                                    </x-shopper-forms.select>
                                </div>
                            </x-shopper-forms.group>
                            <x-shopper-forms.group class="sm:col-span-1" label="Weight">
                                <x-shopper-forms.input wire:model.defer="weightValue" id="weightValue" type="text" class="block w-full pl-3 pr-12" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper-forms.select wire:model.defer="weightUnit" aria-label="{{ __('Weight Unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="kg">kg</option>
                                        <option value="g">g</option>
                                    </x-shopper-forms.select>
                                </div>
                            </x-shopper-forms.group>
                            <x-shopper-forms.group class="sm:col-span-1" label="Volume">
                                <x-shopper-forms.input wire:model.defer="volumeValue" id="VolumeValue" type="text" class="block w-full pl-3 pr-12" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper-forms.select wire:model.defer="VolumeUnit" aria-label="{{ __('Volume Unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="l">l</option>
                                        <option value="ml">ml</option>
                                    </x-shopper-forms.select>
                                </div>
                            </x-shopper-forms.group>
                        </div>
                    </div>
                @endif
            </div>

            <x-shopper-forms.seo
                slug="products"
                :title="$seoTitle"
                :url="str_slug($name)"
                :description="$seoDescription"
                :canUpdate="$updateSeo"
            />
        </div>
        <div class="sm:col-span-2">
            <aside class="space-y-5">
                <div class="bg-white dark:bg-secondary-800 rounded-lg shadow divide-y divide-secondary-200 dark:divide-secondary-700">
                    <div class="p-4 sm:p-5">
                        <x-shopper-label value="{{ __('Product status') }}" />
                        <div class="mt-4 px-3 py-2.5 bg-primary-500 bg-opacity-10 rounded-md text-primary-600 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="h-8 w-8 flex items-center justify-center rounded-md bg-primary-600 shrink-0">
                                    <x-heroicon-o-eye class="h-5 w-5 text-white" />
                                </span>
                                <span class="font-semibold ml-3 text-sm">{{ __('Visible') }}</span>
                            </div>
                            <div>
                                <span wire:model="isVisible" x-data="{ on: @entangle('isVisible') }" role="checkbox" tabindex="0" @click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" :class="{ 'bg-secondary-200 dark:bg-secondary-600': !on, 'bg-primary-600': on }" class="relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-primary bg-secondary-200">
                                    <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                    <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200 translate-x-0"></span>
                                </span>
                            </div>
                        </div>
                        <p class="mt-2 text-secondary-500 dark:text-secondary-400 leading-5 text-sm">
                            {{ __('This product will be hidden from all sales channels.') }}
                        </p>
                    </div>
                    <div class="p-4 sm:p-5">
                        <x-datetime-picker
                            :label="__('Product availability')"
                            :placeholder="__('Pick a date')"
                            wire:model="publishedAt"
                            parse-format="YYYY-MM-DD HH:mm"
                            time-format="24"
                            without-timezone
                            class="dark:bg-secondary-700"
                        />
                        @if($publishedAt)
                            <div class="mt-2 flex items-start">
                                <div class="mt-1 shrink-0 w-2.5 h-2.5 rounded-full bg-primary-600"></div>
                                <p class="ml-2.5 text-sm text-secondary-500 leading-5">
                                    {{ __('Will be published on:') }} <br>
                                    {{ $publishedAtFormatted }}
                                </p>
                            </div>
                        @else
                            <p class="mt-2 text-sm leading-5 text-secondary-500">
                                {{ __('Specify a publication date so that your product are scheduled on your store.') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="bg-white dark:bg-secondary-800 rounded-lg shadow">
                    <div class="px-4 pt-4 sm:px-5 sm:pt-5">
                        <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('Product associations') }}</h4>
                    </div>
                    <div class="divide-y divide-secondary-200 dark:divide-secondary-700">
                        <x-shopper-forms.group class="p-4 sm:p-5" label="Brand" for="brand_id">
                            <x-shopper-forms.select wire:model.defer="brand_id" id="brand_id">
                                <option value="0">{{ __('No brand') }}</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </x-shopper-forms.select>
                        </x-shopper-forms.group>
                        <div class="p-4 sm:p-5">
                            <x-select
                                :label="__('Collections')"
                                placeholder="Select collections"
                                wire:model.defer="collection_ids"
                                class="dark:bg-secondary-700"
                                multiselect
                            >
                                @foreach($collections as $collection)
                                    <x-select.user-option
                                        :img="$collection->getFirstMediaUrl(config('shopper.system.storage.disks.uploads'))"
                                        :label="$collection->name"
                                        :value="$collection->id"
                                    />
                                @endforeach
                            </x-select>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-secondary-800 rounded-lg shadow">
                    <div class="px-4 pt-4 sm:px-5 sm:pt-5">
                        <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('Product categories') }}</h4>
                    </div>
                    <div class="px-4 py-3">
                        <div class="space-y-3 p-2 max-h-96 border border-secondary-200 rounded-md shadow-sm overflow-scroll hide-scroll dark:border-secondary-700">
                            @foreach($categories as $category)
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <x-shopper-forms.checkbox id="category-{{ $category->id }}" wire:model.defer="category_ids" value="{{ $category->id }}" />
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="category-{{ $category->id }}" class="font-medium text-secondary-700 dark:text-secondary-300">{{ $category->name }}</label>
                                    </div>
                                </div>

                                @if($category->children->isNotEmpty())
                                    <div class="ml-4 space-y-3">
                                        @foreach($category->children as $child)
                                            @include('shopper::components.forms.checkbox-category', ['parent' => $category->parent_id, 'category' => $child])
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div class="mt-6 border-t border-secondary-200 dark:border-secondary-700 pt-5 pb-10">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper-loader wire:loading wire:target="store" />
                {{ __('Save') }}
            </x-shopper-button>
        </div>
    </div>
</div>
