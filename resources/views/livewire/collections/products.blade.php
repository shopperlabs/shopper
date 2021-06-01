<div>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="sm:flex sm:items-center sm:justify-between p-4 sm:py-5 sm:px-6">
            <h3 class="text-base text-gray-900 leading-6 font-medium">{{ __("Products") }}</h3>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                @if($collection->type === 'manual')
                    <span class="inline-flex rounded-md shadow-sm">
                        <x-shopper-default-button wire:click="launchModal" type="button">
                            {{ __("Browse") }}
                        </x-shopper-default-button>
                    </span>
                @endif
                <div class="relative">
                    <x-shopper-label for="sort" class="sr-only">{{ __("Sort products by") }}</x-shopper-label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-sm text-gray-500 leading-5">
                            {{ __("Sort by:") }}
                        </span>
                    </div>
                    <x-shopper-input.select wire:model="sortBy" id="sort" class="pl-18 pr-10 py-2">
                        <option value="alpha_asc">{{ __("Alpha Asc") }}</option>
                        <option value="alpha_desc">{{ __("Alpha Desc") }}</option>
                        <option value="price_desc">{{ __("Price Desc") }}</option>
                        <option value="price_asc">{{ __("Price ASC") }}</option>
                        <option value="created_desc">{{ __("Created Desc") }}</option>
                        <option value="created_asc">{{ __("Created Asc") }}</option>
                    </x-shopper-input.select>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200 p-4 sm:px-6 sm:py-5">
            @if($collectionProducts->isNotEmpty())
                <div class="divide-y divide-gray-100">
                    @foreach($collectionProducts as $collectionProduct)
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center">
                                @if($collectionProduct->getFirstImage())
                                    <span class="flex-shrink-0 h-10 w-10 rounded-md overflow-hidden">
                                        <img class="object-cover object-center w-full h-full block" src="{{ $collectionProduct->getFirstImage()->file_path }}" alt="" />
                                    </span>
                                @else
                                    <span class="flex items-center justify-center h-10 w-10 bg-gray-100 text-gray-300 rounded-md">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                @endif
                                <p class="ml-4 text-sm font-medium text-gray-500">
                                    {{ $collectionProduct->name }}
                                </p>
                            </div>
                            <button wire:key="product_{{ $loop->index }}" wire:click="removeProduct({{ $collectionProduct->id }})" type="button" class="text-gray-500 text-sm font-medium inline-flex items-center">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-5 w-full max-w-xs mx-auto flex flex-col items-center justify-center">
                    <span class="flex-shrink-0 w-10 h-10">
                        <svg class="w-full h-full text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </span>
                    <p class="mt-2.5 text-sm text-gray-500 leading-5 text-center">
                        {{ __("There are no products in this collection. Add or change conditions to dynamically add products.") }}
                    </p>
                    @if($collection->type === 'auto')
                        <div class="relative mt-3">
                            <span class="inline-flex rounded-md shadow-sm">
                                <x-shopper-default-button type="button">
                                    {{ __("Update conditions") }}
                                    <span class="ml-1.5 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                      {{ __('Soon') }}
                                    </span>
                                </x-shopper-default-button>
                            </span>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <x-shopper-modal id="products-list" wire:model="browsingProductsModal" maxWidth="2xl">
        <div class="bg-white">
            <div class="sm:flex sm:items-start px-4 sm:px-6 py-4">
                <div class="text-center sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-700">{{ __("Add Products to collection") }}</h3>
                </div>
            </div>
            <div class="py-2 px-4 sm:px-6 border-t border-gray-100">
                <label for="search" class="sr-only">{{ __("Search product") }}</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input id="search" wire:model.debounce.350ms="search" autocomplete="off" class="form-input block w-full pl-10 pr-6 sm:text-sm sm:leading-5" placeholder="{{ __("Search product by name") }}" />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg wire:loading wire:target="search" class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="my-2 divide-y divide-gray-200 h-80 overflow-auto">
                @foreach($products as $product)
                    <label for="product_{{ $product->id }}" class="flex items-center py-3 cursor-pointer hover:bg-gray-50 px-4 sm:px-6 focus:bg-gray-50">
                        <span class="mr-4">
                            <input id="product_{{ $product->id }}" aria-label="{{ __("Product") }}" wire:model="selectedProducts" value="{{ $product->id }}" type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 transition duration-150 ease-in-out" />
                        </span>
                        <span class="flex flex-1 items-center justify-between">
                            <span class="block font-medium text-sm text-gray-700">{{ $product->name }}</span>
                            <span class="flex items-center space-x-2">
                                <span class="text-sm leading-5 text-gray-500">{{ $product->stock }} {{ __("available") }}</span>
                                <span class="text-sm leading-5 text-gray-500">{{ $product->formattedPrice }}</span>
                            </span>
                        </span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                <x-shopper-button wire:click="addProducts" wire.loading.attr="disabled" type="button">
                    <svg wire:loading wire:target="addProducts" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    {{ __('Add Selected Products') }}
                </x-shopper-button>
            </span>
            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                <x-shopper-default-button wire:click="closeModal" type="button">
                    {{ __('Cancel') }}
                </x-shopper-default-button>
            </span>
        </div>
    </x-shopper-modal>
</div>
