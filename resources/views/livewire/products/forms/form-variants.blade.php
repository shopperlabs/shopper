<div>
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{ __("Products variations") }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            {{ __("All variations of your product. The variations can each have their stock and price.") }}
        </p>
    </div>

    <section aria-labelledby="products_variations_heading">
        <div class="mt-5 bg-white pt-5 shadow rounded-md">
            <div class="px-4 sm:px-5 flex items-center justify-between space-x-4">
                <div class="flex flex-1">
                    <label for="filter" class="sr-only">{{ __('Search product variations') }}</label>
                    <div class="flex flex-grow rounded-md shadow-sm">
                        <div class="relative flex-grow focus-within:z-10">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                                </svg>
                            </div>
                            <x-shopper-input.text id="filter" wire:model.debounce.300ms="search" class="rounded-none rounded-md pl-10" placeholder="{{ __('Search product variations') }}" />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg wire:loading wire:target="search" class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <span class="shadow-sm rounded-md">
                        <x-shopper-button type="button" wire:click="confirmVarianteCreating">
                            {{ __("Add new variation") }}
                        </x-shopper-button>
                    </span>
                </div>
            </div>
            <div class="mt-5">
                <div class="align-middle inline-block min-w-full">
                    <table class="min-w-full">
                        <thead>
                        <tr class="border-t border-gray-200">
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <span class="lg:pl-2">{{ __("Variant") }}</span>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{ __("SKU") }}
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{ __("Price") }}
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{ __("Current stock") }}
                            </th>
                            <th class="pr-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" x-max="1">
                            @forelse($variants as $variant)
                                <tr>
                                    <td class="px-6 py-3 max-w-xl text-sm leading-5 font-medium text-gray-900">
                                        <div class="flex items-center space-x-3 lg:pl-2">
                                            <div class="flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $variant->is_visible ? 'bg-green-600': 'bg-gray-400' }}"></div>
                                            <div class="flex items-center">
                                                @if($variant->files->count() > 0)
                                                    <img class="h-8 w-8 rounded object-cover object-center" src="{{ $variant->files->first()->file_path }}" alt="">
                                                @else
                                                    <div class="bg-gray-200 flex items-center justify-center h-8 w-8 rounded">
                                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                                <span class="ml-2 truncate">
                                                    <span>{{ $variant->name }} </span>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 table-cell whitespace-no-wrap text-sm leading-5 text-gray-500 font-medium">
                                        <span>@if($variant->sku) {{ $variant->sku }} @else &mdash; @endif</span>
                                    </td>
                                    <td class="px-6 py-3 table-cell whitespace-no-wrap text-sm leading-5 text-gray-500 font-medium">
                                        <span>@if($variant->formattedPrice) {{ $variant->formattedPrice }} @else &mdash; @endif</span>
                                    </td>
                                    <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        <div class="flex items-center">
                                            <span class="mr-2 text-xs px-1.5 inline-flex leading-5 font-semibold rounded-full {{ $variant->stock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $variant->stock }}
                                            </span>
                                            {{ __("in stock") }}
                                        </div>
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
                                                        <button type="button" class="group w-full flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" x-description="Heroicon name: pencil-alt" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                            </svg>
                                                            {{ __("Edit") }}
                                                        </button>
                                                    </div>
                                                    <div class="border-t border-gray-100"></div>
                                                    <div class="py-1">
                                                        <button wire:click="remove({{ $variant->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
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
                                            <span class="font-medium py-8 text-cool-gray-400 text-xl">{{ __("No variation found") }}...</span>
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
                    {{ $variants->links('shopper::livewire.wire-mobile-pagination-links') }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm leading-5 text-gray-700">
                            {{ __('Showing') }}
                            <span class="font-medium">{{ ($variants->currentPage() - 1) * $variants->perPage() + 1 }}</span>
                            {{ __('to') }}
                            <span class="font-medium">{{ ($variants->currentPage() - 1) * $variants->perPage() + count($variants->items()) }}</span>
                            {{ __('of') }}
                            <span class="font-medium"> {!! $variants->total() !!}</span>
                            {{ __('results') }}
                        </p>
                    </div>
                    {{ $variants->links() }}
                </div>
            </div>
        </div>
    </section>

    <x-shopper-modal wire:model="creatingModale" maxWidth="4xl">
        <div class="px-4 py-5 sm:p-6 w-full h-auto md:h-125 overflow-y-auto">
            <div class="space-y-4">
                <div class="space-y-5">
                    <div>
                        <h2 class="text-lg leading-6 font-medium text-gray-900">{{ __('About the variation') }}</h2>
                        <p class="mt-1 text-sm leading-5 text-gray-500">
                            {{ __('Variant name and price. If the price is empty, the price of the product will be applied.') }}
                        </p>
                    </div>
                    <div class="flex flex-col space-y-5 lg:flex-row lg:space-y-0 lg:space-x-6">
                        <div class="flex-grow space-y-6">
                            <x-shopper-input.group label="Name" for="name" isRequired :error="$errors->first('name')">
                                <x-shopper-input.text wire:model.lazy="name" id="name" type="text" autocomplete="off" placeholder="{{ __('Black 128Go, Blue 64Go...') }}" />
                            </x-shopper-input.group>
                            <div class="divide-y divide-gray-200">
                                <div class="grid gap-4 sm:grid-cols-6 sm:gap-4 pb-4 sm:pb-5">
                                    <div class="col-span-6 sm:col-span-3">
                                        <x-shopper-input.group label="Price amount" for="price_amount">
                                            <x-shopper-input.text wire:model.lazy="price_amount" id="price_amount" type="text" autocomplete="off" min="0" autocomplete="off" placeholder="0.00" />
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                                            </div>
                                        </x-shopper-input.group>
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <x-shopper-input.group label="Compare at price" for="old_price_amount">
                                            <x-shopper-input.text wire:model.lazy="old_price_amount" id="old_price_amount" type="text" autocomplete="off" min="0" autocomplete="off" placeholder="0.00" />
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                                            </div>
                                        </x-shopper-input.group>
                                    </div>
                                </div>
                                <div class="grid gap-4 grid-cols-6 sm:gap-6 py-4 sm:py-5">
                                    <div class="col-span-6 sm:col-span-3">
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

                        <div class="flex-grow space-y-1 lg:flex-grow-0 lg:flex-shrink-0">
                            <p class="hidden lg:block text-sm leading-5 font-medium text-gray-700 mb-2" aria-hidden="true">
                                {{ __('Product image') }}
                            </p>
                            <div class="lg:hidden">
                                <x-shopper-input.group label="Product image" for="picture" :error="$errors->first('file')" noShadow>
                                    <x-shopper-input.file-upload wire:model="file" id="picture">
                                    <span class="h-12 w-12 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                        @if($file)
                                            <img class="h-full w-full bg-cover" src="{{ $file->temporaryUrl() }}" alt="">
                                        @else
                                            <span class="h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </span>
                                        @endif
                                    </span>
                                    </x-shopper-input.file-upload>
                                </x-shopper-input.group>
                            </div>

                            <div class="hidden lg:block">
                                <div class="relative rounded-md overflow-hidden transition duration-150 ease-in-out">
                                    @if($file)
                                        <img class="relative rounded-md w-40 h-40 object-cover" src="{{ $file->temporaryUrl() }}" alt="" />
                                    @else
                                        <span class="inline-block h-40 w-40 rounded-md overflow-hidden bg-gray-100">
                                            <svg class="h-40 w-40 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                    @endif
                                    <label class="absolute inset-0 w-full h-full rounded-md bg-black bg-opacity-75 flex items-center justify-center text-sm leading-5 font-medium text-white opacity-0 hover:opacity-100 focus-within:opacity-100 transition duration-150 ease-in-out">
                                        <span>{{ __('Change') }}</span>
                                        <span class="sr-only"> {{ __('product image') }}</span>
                                        <input wire:model="file" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                                    </label>
                                </div>
                                @if($file)
                                    <div class="flex items-center mt-2">
                                        <button wire:click="removeImage" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">
                                            <svg class="h-5 w-5 mr-1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            {{ __("Remove") }}
                                        </button>
                                    </div>
                                @endif
                                @error('file')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="block text-base font-medium leading-6 text-gray-900">{{ __("Inventory") }}</h4>
                    <div class="divide-y divide-gray-200">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 py-4 sm:py-5">
                            <div class="sm:col-span-1">
                                <x-shopper-input.group label="SKU (Stock Keeping Unit)" for="sku" :error="$errors->first('sku')">
                                    <x-shopper-input.text wire:model="sku" id="sku" type="text" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                            <div class="sm:col-span-1">
                                <x-shopper-input.group label="Barcode (ISBN, UPC, GTIN, etc.)" for="barcode" :error="$errors->first('barcode')">
                                    <x-shopper-input.text wire:model="barcode" id="barcode" type="text" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 py-4 sm:py-5">
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
                            <div class="py-4 sm:py-5">
                                <div class="flex items-center justify-between">
                                    <h4 class="block text-sm font-medium leading-5 text-gray-900">{{ __("Quantity Inventory") }}</h4>
                                    <a href="{{ route('shopper.settings.inventories.index') }}" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out">{{ __("Manage Inventories") }}</a>
                                </div>
                                <div class="mt-4 divide-y divide-gray-200">
                                    <div class="grid grid-cols-3 py-4">
                                        <div class="col-span-2">
                                            <span class="text-sm leading-5 font-semibold text-gray-900 uppercase">{{ __('Inventory name') }}</span>
                                        </div>
                                        <div class="col-span-1 pl-4 flex justify-end">
                                            <span class="text-sm leading-5 font-semibold text-gray-900 uppercase">{{ __('Available') }}</span>
                                        </div>
                                    </div>
                                    @foreach($inventories as $inventory)
                                        <div class="grid grid-cols-3 py-4" wire:key="inventory-{{ $inventory->id }}">
                                            <div class="col-span-2">
                                                <span class="text-sm leading-5 text-gray-600">{{ $inventory->name }}</span>
                                            </div>
                                            <div class="col-span-1 pl-4 flex justify-end">
                                                <input wire:model="quantity.{{ $inventory->id }}" type="number" class="form-input block w-24 transition duration-150 ease-in-out sm:text-sm sm:leading-5" aria-label="{{ __("Inventory quantity") }}"  min="0" autocomplete="off" />
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
        <div class="bg-gray-50 p-4 sm:px-6 sm:flex sm:flex-row-reverse">
            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                <x-shopper-button type="button" wire:click="store" wire.loading.attr="disabled">
                    <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    {{ __('Save variant') }}
                </x-shopper-button>
            </span>
            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                <x-shopper-default-button type="button" wire:click="closeVarianteCreating">
                    {{ __('Cancel') }}
                </x-shopper-default-button>
            </span>
        </div>
    </x-shopper-modal>
</div>
