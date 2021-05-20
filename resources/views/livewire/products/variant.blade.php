<div>
    <x:shopper-breadcrumb back="shopper.products.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.products.edit', $product) }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ $product->name }}</a>
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <span class="text-gray-500">{{ $name }}</span>
    </x:shopper-breadcrumb>

    <div class="mt-3 bg-gray-100 z-30 relative pb-5 border-b border-gray-200">
        <div class="space-y-4">
            <div class="space-y-3 md:flex md:items-start md:justify-between md:space-y-0">
                <div class="flex-1 flex items-center min-w-0 space-x-3">
                    <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">{{ $variant->name }}</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_visible ? 'bg-green-100 text-green-800': 'bg-yellow-100 text-yellow-800' }}">
                        {{ $product->is_visible ? __('Visible'): __('Not visible') }}
                    </span>
                </div>
                <div class="flex space-x-3 pt-1">
                    <span class="hidden sm:block">
                        <x-shopper-danger-button wire:click="openModal" type="button">
                            <svg class="w-5 h-5 -ml-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
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
                    <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __('Variant information') }}</h3>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow rounded-md overflow-hidden">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid gap-4 sm:grid-cols-4 sm:gap-6">
                            <div class="sm:col-span-2">
                                <x-shopper-input.group label="Name" for="name" isRequired :error="$errors->first('name')">
                                    <x-shopper-input.text wire:model.lazy="name" id="name" type="text" autocomplete="off" placeholder="{{ __('Black 128Go, Blue 64Go...') }}" />
                                </x-shopper-input.group>
                            </div>
                            <div class="sm:col-span-4">
                                <h4 class="block text-sm font-medium leading-5 text-gray-700">{{ __('Image') }}</h4>
                                <div class="mt-1 flex items-start space-x-4">
                                    <div>
                                        @if($file)
                                            <div class="flex-shrink-0 rounded-md overflow-hidden">
                                                <img class="h-32 w-32 object-cover rounded-md" src="{{ $file->temporaryUrl() }}" alt="">
                                                <div class="flex items-center mt-2">
                                                    <button wire:click="removeImage" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">
                                                        <svg class="h-5 w-5 mr-1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        {{ __('Remove') }}
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="w-full">
                                                <label for="file" class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer">
                                                    <div class="text-center">
                                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <p class="mt-1 text-sm text-gray-600">
                                                        <span class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition duration-150 ease-in-out">
                                                            {{ __('Upload a file') }}
                                                        </span>
                                                            {{ __('or drag and drop') }}
                                                        </p>
                                                        <p class="mt-1 text-xs text-gray-500">
                                                            {{ __('PNG, JPG, GIF up to 1MB') }}
                                                        </p>
                                                        <input id="file" type="file" wire:model="file" class="sr-only">
                                                    </div>
                                                </label>
                                            </div>
                                        @endif
                                        @error('file')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @if($media)
                                        <div class="p-2 bg-gray-50 rounded-md border border-dashed border-gray-200">
                                            <div class="flex-1 truncate">
                                                <div class="flex-shrink-0 w-40 h-32 overflow-hidden rounded-md">
                                                    <img class="h-32 w-full object-cover" src="{{ $media->file_path }}" alt="" />
                                                </div>
                                            </div>
                                            <div class="mt-1 flex items-center space-x-2">
                                                <div class="truncate">
                                                    <h4 class="text-sm leading-5 text-gray-500 truncate">{{ $media->file_name }}</h4>
                                                    <p class="text-xs leading-4 text-gray-400">{{ $media->file_size }}</p>
                                                </div>
                                                <button wire:click="deleteImage({{ $media->id }})" wire:loading.attr="disabled" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">
                                                    <svg wire:loading wire:target="deleteImage" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                                    </svg>
                                                    <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
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
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="mt-2 text-lg font-semibold leading-6 text-gray-900">{{ __('Pricing') }}</h3>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow overflow-hidden rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid gap-4 sm:grid-cols-4 sm:gap-6">
                            <div class="sm:col-span-2">
                                <x-shopper-input.group label="Price amount" for="price_amount">
                                    <x-shopper-input.text wire:model.lazy="price_amount" id="price_amount" type="text" autocomplete="off" min="0" autocomplete="off" placeholder="0.00" />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                                    </div>
                                </x-shopper-input.group>
                            </div>
                            <div class="sm:col-span-2">
                                <x-shopper-input.group label="Compare at price" for="old_price_amount">
                                    <x-shopper-input.text wire:model.lazy="old_price_amount" id="old_price_amount" type="text" autocomplete="off" min="0" autocomplete="off" placeholder="0.00" />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                                    </div>
                                </x-shopper-input.group>
                            </div>
                            <div class="sm:col-span-2">
                                <x-shopper-input.group label="Cost per item" for="cost_amount" helpText="Customers won’t see this.">
                                    <x-shopper-input.text wire:model.lazy="cost_amount" id="cost_amount" type="text" autocomplete="off" min="0" autocomplete="off" placeholder="0.00" />
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
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="mt-2 text-lg font-semibold leading-6 text-gray-900">{{ __('Inventory') }}</h3>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="bg-white shadow overflow-hidden rounded-md">
                    <div class="divide-y divide-gray-200">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                                <div class="sm:col-span-1">
                                    <x-shopper-input.group label="SKU (Stock Keeping Unit)" for="sku" :error="$errors->first('sku')">
                                        <x-shopper-input.text wire:model.lazy="sku" id="sku" type="text" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>
                                <div class="sm:col-span-1">
                                    <x-shopper-input.group label="Barcode (ISBN, UPC, GTIN, etc.)" for="barcode" :error="$errors->first('barcode')">
                                        <x-shopper-input.text wire:model.lazy="barcode" id="barcode" type="text" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 pt-4 sm:pt-5">
                                <div class="sm:col-span-1">
                                    <x-shopper-input.group label="Safety Stock" for="security_stock" helpText="The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock.">
                                        <x-shopper-input.text wire:model="securityStock" id="security_stock" type="number" min="1" step="1" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center justify-between">
                                <h4 class="block text-sm font-medium leading-5 text-gray-900">{{ __('Quantity Inventory') }}</h4>
                                <div class="ml-4 sm:ml-0 flex items-center space-x-3">
                                    <button wire:click="openModal('stock')" type="button" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out">{{ __("Update stock") }}</button>
                                    <a href="{{ route('shopper.settings.inventories.index') }}" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out">{{ __("Manage Inventories") }}</a>
                                </div>
                            </div>
                            <div class="mt-4 divide-y divide-gray-200">
                                <div class="grid grid-cols-3 py-4">
                                    <div class="col-span-2">
                                        <span class="text-xs leading-5 font-semibold text-gray-500 uppercase tracking-wider">{{ __('Inventory name') }}</span>
                                    </div>
                                    <div class="col-span-1 pl-4 flex justify-end text-right">
                                        <span class="text-xs leading-5 font-semibold text-gray-500 uppercase tracking-wider">{{ __('Available') }}</span>
                                    </div>
                                </div>
                                @foreach($inventories as $inventory)
                                    <div class="grid grid-cols-3 py-4" wire:key="inventory-{{ $inventory->id }}">
                                        <div class="col-span-2">
                                            <span class="text-sm leading-5 text-gray-600">{{ $inventory->name }}</span>
                                        </div>
                                        <div class="col-span-1 pl-4 flex justify-end text-right">
                                            <span class="text-sm leading-5 text-gray-600">{{ $variant->stockInventory($inventory->id) }}</span>
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

    <div class="mt-6 border-t border-gray-200 pt-5 pb-10">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper-loader wire:loading wire:target="store" />
                {{ __('Update variant') }}
            </x-shopper-button>
        </div>
    </div>

    <x-shopper-modal id="confirm-delete-modal" wire:model="confirmDeleteProduct" maxWidth="lg">
        <div class="bg-white rounded-lg px-4 pt-5 pb-4 text-left">
            <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                <button @click="show = false;" type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150" aria-label="Close">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                        {{ __('Delete this variant') }}
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm leading-5 text-gray-500">
                            {{ __('Are you sure you want to delete this variant? All information associated will be deleted.') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <x-shopper-danger-button wire:click="destroy" type="button">
                        <svg wire:loading wire:target="destroy" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        {{ __('Confirm') }}
                    </x-shopper-danger-button>
                </span>
                <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                    <x-shopper-default-button wire:click="closeModal" type="button">
                        {{ __('Cancel') }}
                    </x-shopper-default-button>
                </span>
            </div>
        </div>
    </x-shopper-modal>

    <x-shopper-modal id="stock-management-modal" wire:model="showModalInventories" maxWidth="4xl">
        <div>
            <div class="sm:flex sm:items-start px-4 sm:px-6 py-4">
                <div class="text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ __('Stock management for this variation') }}
                    </h3>
                </div>
            </div>
            <div class="border-t border-gray-200 h-96 overflow-y-scroll">
                <livewire:shopper-products.variant-stock :variant="$variant" />
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                    <button wire:click="closeModal('stock')" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        {{ __('Close') }}
                    </button>
                </span>
            </div>
        </div>
    </x-shopper-modal>
</div>
