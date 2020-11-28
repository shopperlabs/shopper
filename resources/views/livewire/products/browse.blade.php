<div>

    <div class="mt-4 pb-5 border-b border-gray-200 space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0">
        <h2 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">{{ __('Products') }}</h2>
        @if($total > 0)
            @can('add_products')
                <div class="flex space-x-3">
                    <span class="shadow-sm rounded-md">
                        <a href="{{ route('shopper.products.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                            {{ __("Create") }}
                        </a>
                    </span>
                </div>
            @endcan
        @endif
    </div>

    @if($total === 0)
        <x-shopper-empty-state
            :title="__('Manage Catalog')"
            :content="__('Get closer to your first sale by adding and manage products.')"
            :button="__('Add product')"
            permission="add_products"
            :url="route('shopper.products.create')"
        >
            <div class="flex-shrink-0">
            </div>
        </x-shopper-empty-state>
    @else
        <div class="mt-6 bg-white shadow sm:rounded-md">
            <div class="p-4 sm:p-6 sm:pb-4">
                <div class="relative z-20 flex items-center space-x-4">
                    <div class="flex flex-1">
                        <label for="filter" class="sr-only">{{ __('Search products') }}</label>
                        <div class="flex flex-grow rounded-md shadow-sm">
                            <div class="relative flex-grow focus-within:z-10">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                                    </svg>
                                </div>
                                <input id="filter" wire:model.debounce.300ms="search" class="form-input block w-full rounded-none rounded-md pl-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5" placeholder="{{ __('Search product by name') }}" />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg wire:loading wire:target="search" class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
                        <div>
                            <x-shopper-default-button @click="open = !open" type="button" id="options-menu" aria-haspopup="true" aria-expanded="true" x-bind:aria-expanded="open">
                                {{ __("Status") }}
                                <svg class="-mr-1 ml-2 h-5 w-5" x-description="Heroicon name: chevron-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </x-shopper-default-button>
                        </div>
                        <div x-cloak x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                            <div class="rounded-md bg-white shadow-xs">
                                <div class="py-1">
                                    <div class="flex items-center py-2 px-4">
                                        <input wire:model="is_visible" id="visible" name="is_visible" type="radio" value="1" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                        <label for="visible" class="cursor-pointer ml-3">
                                            <span class="block text-sm leading-5 font-medium text-gray-700">{{ __("Visible") }}</span>
                                        </label>
                                    </div>
                                    <div class="flex items-center py-2 px-4">
                                        <input wire:model="is_visible" id="invisible" name="is_visible" type="radio" value="0" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                        <label for="invisible" class="cursor-pointer ml-3">
                                            <span class="block text-sm leading-5 font-medium text-gray-700">{{ __("Not visible") }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="py-1">
                                    <button wire:click="resetStatusFilter" type="button" class="block px-4 py-2 text-sm text-left leading-5 text-gray-500 hover:text-blue-600">{{ __("Clear") }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div x-data="{ show: false }" @keydown.window.escape="show = false" @click.away="show = false" class="relative inline-block text-left">
                        <x-shopper-default-button @click="show = !show" type="button" id="options-filters" aria-haspopup="true" aria-expanded="true" x-bind:aria-expanded="show">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            {{ __("More Filters") }}
                        </x-shopper-default-button>
                        <div x-cloak x-show="show" @keydown.window.escape="show = false;" class="fixed z-50 inset-0 overflow-hidden">
                            <div class="absolute inset-0 overflow-hidden">
                                <section @click.away="show = false;" class="absolute inset-y-0 pl-16 max-w-full right-0 flex" aria-labelledby="slide-over-heading">
                                    <div class="w-screen max-w-md" x-description="Slide-over panel, show/hide based on slide-over state." x-show="show" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
                                        <form class="h-full divide-y divide-gray-200 flex flex-col bg-white shadow-xl">
                                            <div class="flex-1 h-0 overflow-y-auto">
                                                <div class="py-6 px-4 bg-blue-700 sm:px-6">
                                                    <div class="flex items-center justify-between">
                                                        <h2 id="slide-over-heading" class="text-lg font-medium text-white">
                                                            {{ __("Products Filters") }}
                                                        </h2>
                                                        <div class="ml-3 h-7 flex items-center">
                                                            <button @click="show = false;" class="bg-blue-700 rounded-md text-blue-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                                                <span class="sr-only">{{ __("Close panel") }}</span>
                                                                <svg class="h-6 w-6" x-description="Heroicon name: x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="mt-1">
                                                        <p class="text-sm text-blue-300">
                                                            {{ __("Apply deeper filters to your products to display those that interest you.") }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex-1 flex flex-col justify-between">
                                                    <div class="divide-y divide-gray-200">
                                                        <div class="pt-6 divide-y divide-gray-200">
                                                            <div class="px-4 sm:px-6 pb-5">
                                                                <div class="space-y-6">
                                                                    <div>
                                                                        <label for="brand_id" class="block text-sm font-medium text-gray-900">
                                                                            {{ __("Brands") }}
                                                                        </label>
                                                                        <div class="mt-1">
                                                                            <div class="mt-1 rounded-md shadow-sm">
                                                                                <select wire:model="brand_id" id="brand_id" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                                                                    <option value="0">{{ __("No Brand") }}</option>
                                                                                    @foreach($brands as $brand)
                                                                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <label for="category_id" class="block text-sm font-medium text-gray-900">
                                                                            {{ __("Categories") }}
                                                                        </label>
                                                                        <div class="mt-1">
                                                                            <div class="mt-1 rounded-md shadow-sm">
                                                                                <select wire:model="category_id" id="category_id" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                                                                    <option value="0">{{ __("No Category") }}</option>
                                                                                    @foreach($categories as $category)
                                                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <label for="collection_id" class="block text-sm font-medium text-gray-900">
                                                                            {{ __("Collections") }}
                                                                        </label>
                                                                        <div class="mt-1">
                                                                            <div class="mt-1 rounded-md shadow-sm">
                                                                                <select wire:model="collection_id" id="collection_id" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                                                                    <option value="0">{{ __("No Collection") }}</option>
                                                                                    @foreach($collections as $collection)
                                                                                        <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="p-4 sm:px-6 sm:py-5">
                                                                <fieldset>
                                                                    <div class="flex items-center justify-between">
                                                                        <legend class="text-sm font-medium text-gray-900">
                                                                            {{ __("Status") }}
                                                                        </legend>
                                                                        <button wire:click="resetStatusFilter" type="button" class="block px-4 py-2 text-sm text-left leading-5 text-gray-500 hover:text-blue-600 hover:underline">{{ __("Clear") }}</button>
                                                                    </div>
                                                                    <div class="mt-2 space-y-5">
                                                                        <div class="relative flex items-start">
                                                                            <div class="absolute flex items-center h-5">
                                                                                <input wire:model="is_visible" id="status_public" value="1" aria-describedby="product_status_visible" type="radio" name="status" class="focus:shadow-blue-500 h-4 w-4 text-blue-600 border-gray-300" />
                                                                            </div>
                                                                            <div class="pl-7 text-sm">
                                                                                <label for="status_public" class="font-medium text-gray-900">
                                                                                    {{ __("Visible") }}
                                                                                </label>
                                                                                <p id="privacy_public_description" class="text-gray-500">
                                                                                    {{ __("All store customers will be able to view this product.") }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <div class="relative flex items-start">
                                                                                <div class="absolute flex items-center h-5">
                                                                                    <input wire:model="is_visible" id="status_private" value="0" aria-describedby="product_status_invisible" type="radio" name="status" class="focus:shadow-blue-500 h-4 w-4 text-blue-600 border-gray-300" />
                                                                                </div>
                                                                                <div class="pl-7 text-sm">
                                                                                    <label for="status_private" class="font-medium text-gray-900">
                                                                                        {{ __("Not visible") }}
                                                                                    </label>
                                                                                    <p id="privacy_private-to-project_description" class="text-gray-500">
                                                                                        {{ __("Products that have not yet been released to customers") }}
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
                                                    {{ __("Close") }}
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
            <div class="hidden sm:block">
                <div class="align-middle inline-block min-w-full">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-t border-gray-200">
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    <span class="lg:pl-2">{{ __("Product") }}</span>
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Sku") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Brand") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Stock") }}
                                </th>
                                <th class="hidden md:table-cell px-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Published At") }}
                                </th>
                                <th class="pr-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100" x-max="1">
                            @forelse($products as $product)
                                <tr>
                                    <td class="px-6 py-3 max-w-0 w-full whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                        <div class="flex items-center space-x-3 lg:pl-2">
                                            <div class="flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $product->is_visible ? 'bg-green-600': 'bg-gray-400' }}"></div>
                                            <div class="flex items-center">
                                                @if($product->files->count() > 0)
                                                    <img class="h-8 w-8 rounded object-cover object-center" src="{{ $product->files->first()->file_path }}" alt="">
                                                @else
                                                    <div class="bg-gray-200 flex items-center justify-center h-8 w-8 rounded">
                                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                                <a href="{{ route('shopper.products.edit', $product) }}" class="ml-2 truncate hover:text-gray-600">
                                                    <span>{{ $product->name }} </span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 table-cell whitespace-no-wrap text-sm leading-5 text-gray-500 font-medium">
                                        <span>@if($product->sku) {{ $product->sku }} @else &mdash; @endif</span>
                                    </td>
                                    <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        @if($product->brand)
                                            <a href="{{ route('shopper.brands.edit', $product->brand) }}" class="inline-flex items-center border-b border-dashed border-bue-400 text-blue-500 hover:text-blue-400 hover:border-blue-300 font-medium text-sm leading-5">
                                                <span>{{ $product->brand->name }}</span>
                                            </a>
                                        @else
                                            <span class="inline-flex text-gray-700">&mdash;</span>
                                        @endif
                                    </td>
                                    <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        <div class="flex items-center">
                                            <span class="mr-2 text-xs px-1.5 inline-flex leading-5 font-semibold rounded-full {{ $product->stock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $product->stock }}
                                            </span>
                                            {{ __("in stock") }}
                                        </div>
                                    </td>
                                    <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500 text-right">
                                        @if($product->published_at)
                                            <time datetime="{{ $product->published_at->format('Y-m-d') }}" class="capitalize">{{ $product->published_at->formatLocalized('%d %B, %Y') }}</time>
                                        @endif
                                    </td>
                                    <td class="pr-6">
                                        <div x-data="{ open: false }" x-on:item-removed.window="open = false" @keydown.escape="open = false" @click.away="open = false" class="relative flex justify-end items-center">
                                            <button id="project-options-menu-0" aria-has-popup="true" :aria-expanded="open" type="button" @click="open = !open" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                <svg class="w-5 h-5" x-description="Heroicon name: dots-vertical" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                            <div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="mx-3 origin-top-right absolute right-7 top-0 w-48 mt-1 rounded-md shadow-lg" style="display: none;">
                                                <div class="relative z-10 rounded-md bg-white shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="project-options-menu-0">
                                                    <div class="py-1">
                                                        <a href="{{ route('shopper.products.edit', $product) }}" class="group flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" x-description="Heroicon name: pencil-alt" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                            </svg>
                                                            {{ __("Edit") }}
                                                        </a>
                                                    </div>
                                                    <div class="border-t border-gray-100"></div>
                                                    <div class="py-1">
                                                        <button wire:click="remove({{ $product->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" x-description="Heroicon name: trash" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                            {{ __("Delete") }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                        <div class="flex justify-center items-center space-x-2">
                                            <svg class="h-8 w-8 text-cool-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            <span class="font-medium py-8 text-cool-gray-400 text-xl">{{ __("No products found") }}...</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white px-4 py-3 flex items-center rounded-b-md justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $products->links('shopper::livewire.wire-mobile-pagination-links') }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm leading-5 text-gray-700">
                            {{ __('Showing') }}
                            <span class="font-medium">{{ ($products->currentPage() - 1) * $products->perPage() + 1 }}</span>
                            {{ __('to') }}
                            <span class="font-medium">{{ ($products->currentPage() - 1) * $products->perPage() + count($products->items()) }}</span>
                            {{ __('of') }}
                            <span class="font-medium"> {!! $products->total() !!}</span>
                            {{ __('results') }}
                        </p>
                    </div>
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    @endif

    <x-shopper-learn-more name="products" link="#" />

</div>
