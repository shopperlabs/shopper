<div>
    <x:shopper-breadcrumb back="shopper.products.index">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.products.edit', $product)" :title="$product->name" />
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <span class="text-gray-500 dark:text-gray-400">{{ $name }}</span>
    </x:shopper-breadcrumb>
    <div class="mt-3 bg-gray-100 z-30 relative pb-5 border-b border-gray-200 dark:bg-gray-900 dark:border-gray-700">
        <div class="space-y-4">
            <div class="space-y-3 md:flex md:items-start md:justify-between md:space-y-0">
                <div class="flex-1 flex items-center min-w-0 space-x-3">
                    <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate dark:text-white">{{ $variant->name }}</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_visible ? 'bg-green-100 text-green-800': 'bg-yellow-100 text-yellow-800' }}">
                        {{ $product->is_visible ? __('Visible'): __('Not visible') }}
                    </span>
                </div>
                <div class="flex space-x-3 pt-1">
                    <span class="hidden sm:block">
                        <x-shopper-danger-button wire:click="$emit('openModal', 'shopper-modals.delete-product', {{ json_encode(['id' => $variant->id, 'type' => 'variant', 'route' => route('shopper.products.edit', $product)]) }})" type="button">
                            <x-heroicon-o-trash class="w-5 h-5 -ml-1 mr-2" />
                            {{ __('Delete variant') }}
                        </x-shopper-danger-button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white">{{ __('Variant information') }}</h3>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow rounded-md overflow-hidden">
                    <div class="px-4 py-5 sm:p-6 bg-white dark:bg-gray-800">
                        <div class="grid gap-4 sm:grid-cols-4 sm:gap-6">
                            <x-shopper-input.group label="Name" for="name" class="sm:col-span-3" :error="$errors->first('name')" isRequired>
                                <x-shopper-input.text wire:model.lazy="name" id="name" type="text" autocomplete="off" placeholder="{{ __('Black 128Go, Blue 64Go...') }}" />
                            </x-shopper-input.group>
                            <div class="sm:col-span-4">
                                <h4 class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-300">{{ __('Image') }}</h4>
                                <div class="mt-1 @if($media) grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 @endif">
                                    <div>
                                        <x-shopper-input.single-upload id="file" wire:click="removeSingleMediaPlaceholder" wire:model="file" :file="$file" :error="$errors->first('file')" />
                                    </div>
                                    @if($media)
                                        <div class="p-2 bg-gray-50 rounded-md border border-dashed border-gray-200 dark:bg-gray-700 dark:border-gray-700">
                                            <div class="flex-1 truncate">
                                                <div class="flex-shrink-0 w-40 h-32 overflow-hidden rounded-md">
                                                    <img class="h-32 w-full object-cover" src="{{ $media->getFullUrl() }}" alt="" />
                                                </div>
                                            </div>
                                            <div class="mt-1 flex items-center space-x-2">
                                                <div class="truncate">
                                                    <h4 class="text-sm leading-5 text-gray-500 truncate dark:text-gray-400">{{ $media->file_name }}</h4>
                                                    <p class="text-xs leading-4 text-gray-400 dark:text-gray-500">{{ $media->human_readable_size }}</p>
                                                </div>
                                                <button wire:click="removeMedia({{ $media->id }})" wire:loading.attr="disabled" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">
                                                    <x-shopper-loader wire:loading wire:target="removeMedia" class="text-white" />
                                                    <x-heroicon-o-trash wire:loading.remove class="h-5 w-5" />
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200 dark:border-gray-700"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="mt-2 text-lg font-bold leading-6 text-gray-900 dark:text-white">{{ __('Pricing') }}</h3>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow overflow-hidden rounded-md">
                    <div class="px-4 py-5 sm:p-6 bg-white dark:bg-gray-800">
                        <div class="grid gap-4 sm:grid-cols-4 sm:gap-6">
                            <x-shopper-input.group label="Price amount" for="price_amount" class="sm:col-span-2">
                                <x-shopper-input.text wire:model.lazy="price_amount" id="price_amount" type="text" autocomplete="off" min="0" placeholder="0.00" />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm sm:leading-5 dark:text-gray-400">{{ $currency }}</span>
                                </div>
                            </x-shopper-input.group>
                            <x-shopper-input.group label="Compare at price" for="old_price_amount" class="sm:col-span-2">
                                <x-shopper-input.text wire:model.lazy="old_price_amount" id="old_price_amount" type="text" min="0" autocomplete="off" placeholder="0.00" />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm sm:leading-5 dark:text-gray-400">{{ $currency }}</span>
                                </div>
                            </x-shopper-input.group>
                            <x-shopper-input.group label="Cost per item" for="cost_amount" class="sm:col-span-2" helpText="Customers won’t see this.">
                                <x-shopper-input.text wire:model.lazy="cost_amount" id="cost_amount" type="text" autocomplete="off" min="0" placeholder="0.00" />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                                </div>
                            </x-shopper-input.group>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200 dark:border-gray-700"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="mt-2 text-lg font-bold leading-6 text-gray-900 dark:text-white">{{ __('Inventory') }}</h3>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="bg-white shadow overflow-hidden rounded-md dark:bg-gray-800">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                                <x-shopper-input.group label="SKU (Stock Keeping Unit)" for="sku" class="sm:col-span-1" :error="$errors->first('sku')">
                                    <x-shopper-input.text wire:model.lazy="sku" id="sku" type="text" autocomplete="off" />
                                </x-shopper-input.group>
                                <x-shopper-input.group label="Barcode (ISBN, UPC, GTIN, etc.)" for="barcode" class="sm:col-span-1" :error="$errors->first('barcode')">
                                    <x-shopper-input.text wire:model.lazy="barcode" id="barcode" type="text" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 pt-4 sm:pt-5">
                                <x-shopper-input.group label="Safety Stock" for="security_stock" class="sm:col-span-1" helpText="The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock.">
                                    <x-shopper-input.text wire:model="securityStock" id="security_stock" type="number" min="1" step="1" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center justify-between">
                                <h4 class="block text-sm font-medium leading-5 text-gray-900 dark:text-white">{{ __('Quantity Inventory') }}</h4>
                                <div class="ml-4 sm:ml-0 flex items-center space-x-3">
                                    <button wire:click="$emit('openModal', 'shopper-modals.update-variant-stock', {{ json_encode([$variant->id]) }})" type="button" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-500">{{ __('Update stock') }}</button>
                                    <a href="{{ route('shopper.settings.inventories.index') }}" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-500">{{ __('Manage Inventories') }}</a>
                                </div>
                            </div>
                            <div class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
                                <div class="grid grid-cols-3 py-4">
                                    <div class="col-span-2">
                                        <span class="text-xs leading-5 font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">{{ __('Inventory name') }}</span>
                                    </div>
                                    <div class="col-span-1 pl-4 flex justify-end text-right">
                                        <span class="text-xs leading-5 font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">{{ __('Available') }}</span>
                                    </div>
                                </div>
                                @foreach($inventories as $inventory)
                                    <div class="grid grid-cols-3 py-4" wire:key="inventory-{{ $inventory->id }}">
                                        <div class="col-span-2">
                                            <span class="text-sm leading-5 text-gray-700 dark:text-gray-300">{{ $inventory->name }}</span>
                                        </div>
                                        <div class="col-span-1 pl-4 flex justify-end text-right">
                                            <span class="text-sm leading-5 text-gray-700 dark:text-gray-300">{{ $variant->stockInventory($inventory->id) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 pt-5 border-t border-gray-200 dark:border-gray-700">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper-loader wire:loading wire:target="store" />
                {{ __('Update variant') }}
            </x-shopper-button>
        </div>
    </div>

</div>
