<div>
    <div class="my-6 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0 flex flex-row items-center justify-between md:flex-col md:items-start">
            <h2 class="text-2xl font-bold leading-7 text-secondary-600 sm:text-3xl sm:leading-9 sm:truncate">
                {{ __('shopper::words.location') }}
            </h2>
            <div class="md:mt-2 ml-4 md:ml-0">
                <button type="button" class="text-secondary-400 text-sm inline-flex items-center hover:text-secondary-500 focus:text-secondary-600 leading-5 transition duration-150 ease-in-out">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="w-5 h-5 mr-2">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ __('shopper::layout.forms.actions.export') }}</span>
                </button>
            </div>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('shopper.products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-600 focus:outline-none focus:border-brand-700 focus:shadow-outline-brand active:bg-brand-700 transition ease-in-out duration-150">{{ __('View products') }}</a>
        </div>
    </div>

    <div
        x-data="{
            options: ['all'],
            words: {'all': '{{ __('shopper::words.all') }}'},
            currentTab: 'all'
        }"
        class="bg-white shadow overflow-hidden sm:rounded-md"
    >
        <div class="bg-white border-b border-secondary-200">
            <div class="sm:hidden p-4">
                <select x-model="currentTab" aria-label="{{ __('shopper::words.selected_tab') }}" class="form-select form-select-shopper block w-full pl-3 pr-10 py-2 text-base leading-6 sm:text-sm sm:leading-5 transition ease-in-out duration-150">
                    <template x-for="option in options" :key="option">
                        <option
                            x-bind:value="option"
                            x-text="words[option]"
                            x-bind:selected="option === currentTab"
                        ></option>
                    </template>
                </select>
            </div>
            <div class="hidden sm:block">
                <div class="flex items-center justify-between">
                    <nav class="-mb-px flex">
                        <button x-on:click="currentTab = all" type="button" class="whitespace-no-wrap ml-8 py-4 px-3 border-b-2 border-brand-500 font-medium text-sm leading-5 text-brand-400 focus:outline-none focus:text-brand-500 focus:border-brand-500">
                            {{ __('shopper::words.all') }}
                        </button>
                    </nav>
                    @if($inventories->count() > 1)
                        <div class="px-6">
                            <div class="relative z-20 inline-flex shadow-sm rounded-md">
                                <span class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-secondary-300 bg-white text-sm leading-5 font-medium text-secondary-700 hover:text-secondary-500 focus:z-10 focus:outline-none focus:border-primary-300 focus:shadow-outline-primary active:bg-secondary-100 active:text-secondary-700 transition ease-in-out duration-150">
                                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="ml-2">{{ $name }}</span>
                                </span>
                                <div x-data="{ open: false }" class="-ml-px relative block">
                                    <button @click="open = !open" @click.away="open = false" type="button" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-secondary-300 bg-white text-sm leading-5 font-medium text-secondary-500 hover:text-secondary-400 focus:z-10 focus:outline-none focus:border-primary-300 focus:shadow-outline-primary active:bg-secondary-100 active:text-secondary-500 transition ease-in-out duration-150" aria-label="Expand">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                    <div
                                        x-cloak
                                        x-show="open"
                                        x-description="Dropdown panel, show/hide based on dropdown state."
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="origin-top-right absolute right-0 mt-2 -mr-1 w-56 rounded-md shadow-lg"
                                    >
                                        <div class="rounded-md bg-white shadow-xs">
                                            <div class="py-1">
                                                @foreach($inventories as $inventory)
                                                    <button wire:click="setInventory({{ $inventory->id }}, '{{ $inventory->name }}')" type="button" class="flex w-full px-4 py-2 text-sm leading-5 text-secondary-700 hover:bg-secondary-100 hover:text-secondary-900 focus:outline-none focus:bg-secondary-100 focus:text-secondary-900">
                                                        {{ $inventory->name }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="px-4 py-4 sm:px-6">
            <label for="filter" class="sr-only">{{ __('Search products') }}</label>
            <div class="flex rounded-md shadow-sm">
                <div class="relative grow focus-within:z-10">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                        </svg>
                    </div>
                    <input id="filter" wire:model.debounce.350ms="search" class="form-input block w-full rounded-none rounded-l-md pl-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5" placeholder="{{ __('Search products') }}" />
                    <span wire:loading wire:target="search" class="spinner right-0 top-0 mt-5 mr-6"></span>
                </div>
                <button wire:click="sort('{{ $direction }}')" class="-ml-px relative inline-flex items-center px-4 py-2 border border-secondary-300 text-sm leading-5 font-medium rounded-r-md text-secondary-700 bg-secondary-50 hover:text-secondary-500 hover:bg-white focus:outline-none focus:shadow-outline-primary focus:border-primary-300 active:bg-secondary-100 active:text-secondary-700 transition ease-in-out duration-150">
                    <svg class="h-5 w-5 text-secondary-400" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"/>
                    </svg>
                    <span class="ml-2">{{ __('Sort') }}</span>
                </button>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full overflow-hidden">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-secondary-200 text-left text-sm font-medium leading-4 text-secondary-700 tracking-wider">
                                    {{ __('shopper::layout.forms.label.name') }}
                                </th>
                                <th class="px-6 py-3 border-b border-secondary-200 text-left text-sm font-medium leading-4 text-secondary-700 tracking-wider">
                                    {{ __('shopper::layout.forms.label.sku') }}
                                </th>
                                <th class="px-6 py-3 border-b border-secondary-200 text-center text-sm font-medium leading-4 text-secondary-700 tracking-wider">
                                    {{ __('Available') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($products as $product)
                                <tr class="hover:bg-secondary-50">
                                    <td class="px-6 py-4 border-b border-secondary-200">
                                        <a href="{{ route('shopper.products.edit', $product) }}" class="flex items-center">
                                            <div class="shrink-0 h-10 w-10">
                                                @if($product->preview_image_link !== null)
                                                    <img class="h-10 w-10 rounded-md object-cover" src="{{ $product->preview_image_link }}" alt="{{ $product->id }}" />
                                                @else
                                                    <span class="flex items-center justify-center h-10 w-10 bg-secondary-100 text-secondary-300 rounded-md">
                                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6">
                                                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm leading-5 font-medium text-secondary-900">{{ $product->name }}</div>
                                                <div class="text-sm leading-5 text-secondary-500">{{ $product->sku }}</div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 border-b border-secondary-200 text-sm leading-5 text-secondary-500">
                                        {{ $product->sku ?? __('No Sku') }}
                                    </td>
                                    <td class="px-6 py-4 border-b border-secondary-200 text-center">
                                        <x-shopper::stock-badge :stock="$product->stock" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 whitespace-no-wrap">
                                        <h3 class="text-lg text-center font-medium leading-6 text-secondary-700">
                                            {{ __('No inventory available.') }}
                                        </h3>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="rounded-b-md bg-white px-4 py-3 flex items-center justify-between sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                {{ $products->links('shopper::components.livewire.wire-mobile-pagination-links') }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm leading-5 text-secondary-700">
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
</div>
