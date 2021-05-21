<div>
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{ __('Products variations') }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            {{ __('All variations of your product. The variations can each have their stock and price.') }}
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
                                <x-shopper-loader wire:loading wire:target="search" class="text-blue-600" />
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <span class="shadow-sm rounded-md">
                        <x-shopper-button type="button" wire:click="$emit('openModal', 'shopper-modals.add-variant', {{ json_encode([$product->id, $currency]) }})">
                            {{ __('Add variant') }}
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
                                    <span class="lg:pl-2">{{ __('Variant') }}</span>
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('SKU') }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Price') }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Current stock') }}
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
                                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                            <span class="mr-2 text-xs px-1.5 inline-flex leading-5 font-medium rounded-full {{ $variant->stock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $variant->stock }}
                                            </span>
                                            {{ __('in stock') }}
                                        </div>
                                    </td>
                                    <td class="pr-6">
                                        <div x-data="{ open: false }" x-on:item-removed.window="open = false" @keydown.escape="open = false" @click.away="open = false" class="relative flex justify-end items-center">
                                            <button id="project-options-menu-0" aria-has-popup="true" :aria-expanded="open" type="button" @click="open = !open" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="mx-3 origin-top-right absolute right-7 top-0 w-48 mt-1 rounded-md shadow-lg" style="display: none;">
                                                <div class="relative z-10 rounded-md bg-white shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="project-options-menu-0">
                                                    <div class="py-1">
                                                        <a href="{{ route('shopper.products.variant', ['product' => $product->id, 'id' => $variant->id]) }}" class="group w-full flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" x-description="Heroicon name: pencil-alt" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                            </svg>
                                                            {{ __('Edit') }}
                                                        </a>
                                                    </div>
                                                    <div class="border-t border-gray-100"></div>
                                                    <div class="py-1">
                                                        <button wire:click="remove({{ $variant->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                            {{ __('Delete') }}
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
                                            <span class="font-medium py-8 text-cool-gray-400 text-xl">{{ __('No variation found') }}...</span>
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

</div>
