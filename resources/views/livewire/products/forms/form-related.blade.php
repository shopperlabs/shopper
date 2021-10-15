<div>
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ __('Similar Products') }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ __('All products that can be identified as similar or complementary to your product.') }}
        </p>
    </div>

    <section aria-labelledby="similar_products_heading">
        <div class="mt-5 bg-white dark:bg-gray-800 p-4 sm:p-6 shadow rounded-md">
            {{--@if($products->isNotEmpty())
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-5 bg-gray-100 rounded-md px-3 py-4 dark:bg-gray-900">
                        <x-laravel-blade-sortable::sortable
                            group="products"
                            name="products"
                            :allow-sort="false"
                            ghost-class="opacity-25"
                            wire:onSortOrderChange="handleOnSortOrderChanged"
                            style="min-height:20rem;"
                        >
                            @foreach($products as $product)
                                <x-laravel-blade-sortable::sortable-item
                                    sort-key="{{ $product->id }}"
                                    class="flex items-center border border-gray-300 bg-white rounded-md shadow-sm py-2 px-3 dark:border-gray-700 dark:bg-gray-800"
                                >
                                    @if($product->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
                                        <img class="h-8 w-8 rounded object-cover object-center" src="{{ $product->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')) }}" alt="" />
                                    @else
                                        <div class="bg-gray-200 dark:bg-gray-700 flex items-center justify-center h-8 w-8 rounded">
                                            <x-heroicon-o-photograph class="w-5 h-5 text-gray-400" />
                                        </div>
                                    @endif
                                    <span class="ml-3 truncate text-sm text-gray-500 dark:text-gray-400">{{ $product->name }}</span>
                                </x-laravel-blade-sortable::sortable-item>
                            @endforeach
                        </x-laravel-blade-sortable::sortable>
                    </div>

                    <div class="col-span-2 flex items-center">
                        <div class="space-y-2 flex-1">
                            <button type="button" id="multiselect_rightSelected" class="inline-flex w-full justify-center items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-blue-200 transition ease-in-out duration-150">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <button type="button" id="multiselect_leftSelected" class="inline-flex w-full justify-center items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-blue-200 transition ease-in-out duration-150">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="col-span-5 bg-gray-100 rounded-md px-3 py-4 dark:bg-gray-900">
                        <x-laravel-blade-sortable::sortable
                            group="products"
                            name="related"
                            :allow-sort="false"
                            ghost-class="opacity-25"
                            wire:onSortOrderChange="handleOnSortOrderChanged"
                            style="min-height:20rem;"
                        >
                            @foreach($relatedProducts as $related)
                                <x-laravel-blade-sortable::sortable-item
                                    sort-key="{{ $related->id }}"
                                    class="flex items-center border border-gray-300 rounded-md shadow-sm py-2 px-3 dark:border-gray-700 dark:bg-gray-800"
                                >
                                    @if($related->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
                                        <img class="h-8 w-8 rounded object-cover object-center" src="{{ $related->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')) }}" alt="" />
                                    @else
                                        <div class="bg-gray-200 dark:bg-gray-700 flex items-center justify-center h-8 w-8 rounded">
                                            <x-heroicon-o-photograph class="w-5 h-5 text-gray-400" />
                                        </div>
                                    @endif
                                    <span class="ml-3 truncate text-sm text-gray-500 dark:text-gray-400">{{ $related->name }}</span>
                                </x-laravel-blade-sortable::sortable-item>
                            @endforeach
                        </x-laravel-blade-sortable::sortable>
                    </div>
                </div>
            @endif--}}
        </div>
    </section>
</div>
