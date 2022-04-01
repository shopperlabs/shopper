<x-shopper-modal
    contentClasses="relative p-4 sm:px-5"
    footerClasses="border-t border-secondary-200 dark:border-secondary-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>
    <x-slot name="content">
        <div class="px-1 py-5 w-full h-auto md:h-125 overflow-y-auto hide-scroll">
            <div class="space-y-5">
                <div class="space-y-5">
                    <div>
                        <h2 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">{{ __('About the variation') }}</h2>
                        <p class="mt-1 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                            {{ __('Variant name and price. If the price is empty, the price of the product will be applied.') }}
                        </p>
                    </div>
                    <div>
                        <div class="space-y-5">
                            <x-shopper-forms.group label="Name" for="name_variant" isRequired :error="$errors->first('name')">
                                <x-shopper-forms.input wire:model.defer="name" id="name_variant" type="text" autocomplete="off" placeholder="{{ __('Black 128Go, primary 64Go...') }}" />
                            </x-shopper-forms.group>
                            <div class="grid gap-4 sm:grid-cols-6 sm:gap-4">
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
                            <div class="grid gap-4 grid-cols-6 sm:gap-6">
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

                        <div class="mt-5 shrink-0">
                            <p class="text-sm leading-5 font-medium text-secondary-700 dark:text-secondary-400 mb-2" aria-hidden="true">
                                {{ __('Variant images') }}
                            </p>

                            <div class="block">
                                <livewire:shopper-forms.uploads.multiple />
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('Inventory') }}</h4>
                    <div class="divide-y divide-secondary-200 dark:divide-secondary-700">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 py-4 sm:py-5">
                            <div class="sm:col-span-1">
                                <x-shopper-forms.group label="SKU (Stock Keeping Unit)" for="sku_variant" :error="$errors->first('sku')">
                                    <x-shopper-forms.input wire:model.defer="sku" id="sku_variant" type="text" autocomplete="off" />
                                </x-shopper-forms.group>
                            </div>
                            <div class="sm:col-span-1">
                                <x-shopper-forms.group label="Barcode (ISBN, UPC, GTIN, etc.)" for="barcode_variant" :error="$errors->first('barcode')">
                                    <x-shopper-forms.input wire:model.defer="barcode" id="barcode_variant" type="text" autocomplete="off" />
                                </x-shopper-forms.group>
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 py-4 sm:py-5">
                            @if($inventories->count() <= 1)
                                <div class="sm:col-span-1">
                                    <x-shopper-forms.group label="Quantity" for="quantity_variant">
                                        <x-shopper-forms.input wire:model.defer="quantity.{{ $inventories->first()->id }}" id="quantity_variant" type="number" min="0" autocomplete="off" />
                                    </x-shopper-forms.group>
                                </div>
                            @endif
                            <div class="sm:col-span-1">
                                <x-shopper-forms.group label="Safety Stock" for="security_stock_variant" helpText="The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock.">
                                    <x-shopper-forms.input wire:model.defer="securityStock" id="security_stock_variant" type="number" min="1" step="1" autocomplete="off" />
                                </x-shopper-forms.group>
                            </div>
                        </div>
                        @if($inventories->count() > 1)
                            <div class="py-4 sm:py-5">
                                <div class="flex items-center justify-between">
                                    <h4 class="block text-sm font-medium leading-5 text-secondary-900 dark:text-white">{{ __('Quantity Inventory') }}</h4>
                                    <a href="{{ route('shopper.settings.inventories.index') }}" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-primary-600 hover:text-primary-800 transition duration-150 ease-in-out">{{ __('Manage Inventories') }}</a>
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
                                                <x-shopper-forms.input wire:model.defer="quantity.{{ $inventory->id }}" wire:key="inventory-{{ $inventory->id }}" type="number" class="w-24" aria-label="{{ __('Inventory quantity') }}"  min="0" autocomplete="off" />
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
