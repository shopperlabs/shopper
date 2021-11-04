<div class="p-4 sm:p-6 sm:pb-4">
    <div class="relative z-20 flex items-center space-x-4">
        <x-shopper-input.search label="Search product" placeholder="Search product by name" />
        <div x-data="{ show: false }" @keydown.window.escape="show = false" @click.away="show = false" class="relative inline-block text-left">
            <x-shopper-default-button @click="show = !show" type="button" id="options-filters" aria-haspopup="true" aria-expanded="true" x-bind:aria-expanded="show">
                <x-heroicon-o-filter class="-ml-1 mr-2 h-5 w-5" />
                {{ __('More Filters') }}
            </x-shopper-default-button>
            <div x-cloak x-show="show" @keydown.window.escape="show = false;" class="fixed z-40 inset-0 overflow-hidden">
                <div class="absolute inset-0 top-16 overflow-hidden">
                    <section @click.away="show = false;" class="absolute inset-y-0 pl-16 max-w-full right-0 flex" aria-labelledby="slide-over-heading">
                        <div class="w-screen max-w-md"
                             x-show="show"
                             x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                             x-transition:enter-start="translate-x-full"
                             x-transition:enter-end="translate-x-0"
                             x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                             x-transition:leave-start="translate-x-0"
                             x-transition:leave-end="translate-x-full">
                            <form class="h-full divide-y divide-gray-200 dark:divide-gray-600 flex flex-col bg-white dark:bg-gray-800 shadow-xl">
                                <div class="flex-1 h-0 overflow-y-auto">
                                    <div class="py-6 px-4 bg-primary-700 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <h2 id="slide-over-heading" class="text-lg font-medium text-white">
                                                {{ __('Products Filters') }}
                                            </h2>
                                            <div class="ml-3 h-7 flex items-center">
                                                <button @click="show = false;" type="button" class="bg-primary-700 rounded-md text-primary-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                                    <span class="sr-only">{{ __('Close panel') }}</span>
                                                    <x-heroicon-o-x class="h-6 w-6" />
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mt-1">
                                            <p class="text-sm text-primary-300">
                                                {{ __('Apply deeper filters to your products to display those that interest you.') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex flex-col justify-between">
                                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                            <div class="pt-6 divide-y divide-gray-200 dark:divide-gray-700">
                                                <div class="px-4 sm:px-6 pb-5">
                                                    <div class="space-y-6">
                                                        <div>
                                                            <x-shopper-label for="brand_id" class="text-gray-900 dark:text-white">
                                                                {{ __('Brands') }}
                                                            </x-shopper-label>
                                                            <div class="mt-1">
                                                                <div class="mt-1 rounded-md shadow-sm">
                                                                    <x-shopper-input.select wire:model="brand_id" id="brand_id">
                                                                        <option value="0">{{ __('No Brand') }}</option>
                                                                        @foreach($brands as $brand)
                                                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                                        @endforeach
                                                                    </x-shopper-input.select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <x-shopper-label for="category_id" class="text-gray-900 dark:text-white">
                                                                {{ __('Categories') }}
                                                            </x-shopper-label>
                                                            <div class="mt-1">
                                                                <div class="mt-1 rounded-md shadow-sm">
                                                                    <x-shopper-input.select wire:model="category_id" id="category_id">
                                                                        <option value="0">{{ __('No Category') }}</option>
                                                                        @foreach($categories as $category)
                                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                        @endforeach
                                                                    </x-shopper-input.select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <x-shopper-label for="collection_id" class="text-gray-900 dark:text-white">
                                                                {{ __('Collections') }}
                                                            </x-shopper-label>
                                                            <div class="mt-1">
                                                                <div class="mt-1 rounded-md shadow-sm">
                                                                    <x-shopper-input.select wire:model="collection_id" id="collection_id">
                                                                        <option value="0">{{ __('No Collection') }}</option>
                                                                        @foreach($collections as $collection)
                                                                            <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                                                                        @endforeach
                                                                    </x-shopper-input.select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="p-4 sm:px-6 sm:py-5">
                                                    <fieldset>
                                                        <div class="flex items-center justify-between">
                                                            <legend class="text-sm font-medium text-gray-900 dark:text-white">
                                                                {{ __('Status') }}
                                                            </legend>
                                                            <button wire:click="resetStatusFilter" type="button" class="block px-4 py-2 text-sm text-right leading-5 text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-600 hover:underline">{{ __('Clear') }}</button>
                                                        </div>
                                                        <div class="mt-2 space-y-5">
                                                            <div class="relative flex items-start">
                                                                <div class="absolute flex items-center h-5">
                                                                    <x-shopper-input.radio wire:model="isVisible" id="status_public" value="1" aria-describedby="product_status_visible" name="status" />
                                                                </div>
                                                                <div class="pl-7 text-sm">
                                                                    <x-shopper-label for="status_public" class="text-gray-900 dark:text-white">
                                                                        {{ __('Visible') }}
                                                                    </x-shopper-label>
                                                                    <p id="privacy_public_description" class="text-gray-500 dark:text-gray-400">
                                                                        {{ __('All store customers will be able to view these products.') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="relative flex items-start">
                                                                    <div class="absolute flex items-center h-5">
                                                                        <x-shopper-input.radio wire:model.lazy="isVisible" id="status_private" value="0" aria-describedby="product_status_invisible" name="status" />
                                                                    </div>
                                                                    <div class="pl-7 text-sm">
                                                                        <x-shopper-label for="status_private" class="text-gray-900 dark:text-white">
                                                                            {{ __('Not visible') }}
                                                                        </x-shopper-label>
                                                                        <p id="privacy_private-to-project_description" class="text-gray-500 dark:text-gray-400">
                                                                            {{ __('Products that have not yet been released to customers.') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 px-4 py-4 flex justify-end">
                                    <x-shopper-default-button @click="show = false;" type="button">
                                        {{ __('Close') }}
                                    </x-shopper-default-button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
