<x-shopper-modal
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="border-t border-gray-200 dark:border-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>

    <x-slot name="content">
        <div class="px-4 py-5 sm:p-6 w-full h-auto md:h-125 overflow-y-auto hide-scroll">
            <div class="space-y-5">
                <div class="space-y-5">
                    <div>
                        <h2 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('About the variation') }}</h2>
                        <p class="mt-1 text-sm leading-5 text-gray-500 dark:text-gray-400">
                            {{ __('Variant name and price. If the price is empty, the price of the product will be applied.') }}
                        </p>
                    </div>
                    <div>
                        <div class="space-y-5">
                            <x-shopper-input.group label="Name" for="name_variant" isRequired :error="$errors->first('name')">
                                <x-shopper-input.text wire:model.lazy="name" id="name_variant" type="text" autocomplete="off" placeholder="{{ __('Black 128Go, primary 64Go...') }}" />
                            </x-shopper-input.group>
                            <div class="grid gap-4 sm:grid-cols-6 sm:gap-4">
                                <div class="col-span-6 sm:col-span-3">
                                    <x-shopper-input.group label="Price amount" for="price_amount_variant">
                                        <x-shopper-input.text wire:model.lazy="price_amount" id="price_amount_variant" type="text" autocomplete="off" min="0" autocomplete="off" placeholder="0.00" />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                                        </div>
                                    </x-shopper-input.group>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <x-shopper-input.group label="Compare at price" for="old_price_amount_variant">
                                        <x-shopper-input.text wire:model.lazy="old_price_amount" id="old_price_amount_variant" type="text" autocomplete="off" min="0" autocomplete="off" placeholder="0.00" />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                                        </div>
                                    </x-shopper-input.group>
                                </div>
                            </div>
                            <div class="grid gap-4 grid-cols-6 sm:gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <x-shopper-input.group label="Cost per item" for="cost_amount_variant" helpText="Customers won’t see this.">
                                        <x-shopper-input.text wire:model.lazy="cost_amount" id="cost_amount_variant" type="text" autocomplete="off" min="0" autocomplete="off" placeholder="0.00" />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                                        </div>
                                    </x-shopper-input.group>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex-shrink-0">
                            <p class="text-sm leading-5 font-medium text-gray-700 dark:text-gray-400 mb-2" aria-hidden="true">
                                {{ __('Variant images') }}
                            </p>

                            <div class="block">
                                <livewire:shopper-forms.uploads.multiple />
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white">{{ __('Inventory') }}</h4>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 py-4 sm:py-5">
                            <div class="sm:col-span-1">
                                <x-shopper-input.group label="SKU (Stock Keeping Unit)" for="sku_variant" :error="$errors->first('sku')">
                                    <x-shopper-input.text wire:model="sku" id="sku_variant" type="text" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                            <div class="sm:col-span-1">
                                <x-shopper-input.group label="Barcode (ISBN, UPC, GTIN, etc.)" for="barcode_variant" :error="$errors->first('barcode')">
                                    <x-shopper-input.text wire:model="barcode" id="barcode_variant" type="text" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 py-4 sm:py-5">
                            @if($inventories->count() <= 1)
                                <div class="sm:col-span-1">
                                    <x-shopper-input.group label="Quantity" for="quantity_variant">
                                        <x-shopper-input.text wire:model="quantity.{{ $inventories->first()->id }}" id="quantity_variant" type="number" min="0" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>
                            @endif
                            <div class="sm:col-span-1">
                                <x-shopper-input.group label="Safety Stock" for="security_stock_variant" helpText="The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock.">
                                    <x-shopper-input.text wire:model="securityStock" id="security_stock_variant" type="number" min="1" step="1" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                        </div>
                        @if($inventories->count() > 1)
                            <div class="py-4 sm:py-5">
                                <div class="flex items-center justify-between">
                                    <h4 class="block text-sm font-medium leading-5 text-gray-900 dark:text-white">{{ __('Quantity Inventory') }}</h4>
                                    <a href="{{ route('shopper.settings.inventories.index') }}" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-primary-600 hover:text-primary-800 transition duration-150 ease-in-out">{{ __('Manage Inventories') }}</a>
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
                                                <x-shopper-input.text wire:model="quantity.{{ $inventory->id }}" wire:key="inventory-{{ $inventory->id }}" type="number" class="w-24" aria-label="{{ __('Inventory quantity') }}"  min="0" autocomplete="off" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
            <x-shopper-button type="button" wire:click="save" wire.loading.attr="disabled">
                <x-shopper-loader wire:loading wire:target="save" class="text-white" />
                {{ __('Save') }}
            </x-shopper-button>
        </span>
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper-default-button type="button" wire:click="$emit('closeModal')">
                {{ __('Cancel') }}
            </x-shopper-default-button>
        </span>
    </x-slot>

</x-shopper-modal>
