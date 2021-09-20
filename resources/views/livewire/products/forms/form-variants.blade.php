<div>
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ __('Products variations') }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            {{ __('All variations of your product. The variations can each have their stock and price.') }}
        </p>
    </div>

    <section aria-labelledby="products_variations_heading">
        <div class="mt-5 bg-white dark:bg-gray-800 pt-5 shadow rounded-md">
            <div class="px-4 sm:px-5 flex items-center justify-between space-x-4">
                <x-shopper-input.search label="Search variant" placeholder="Search product variant" />
                <div>
                    <span class="shadow-sm rounded-md">
                        <x-shopper-button wire:click="$emit('openModal', 'shopper-modals.add-variant', {{ json_encode([$product->id, $currency]) }})" type="button">
                            {{ __('Add variant') }}
                        </x-shopper-button>
                    </span>
                </div>
            </div>
            <div class="mt-5 block overflow-x-auto">
                <div class="align-middle inline-block min-w-full overflow-hidden">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <span class="lg:pl-2">{{ __('Variant') }}</span>
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('SKU') }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Price') }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Current stock') }}
                                </th>
                                <th class="pr-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-right text-xs leading-4 font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700" x-max="1">
                            @forelse($variants as $variant)
                                <tr>
                                    <td class="px-6 py-3 max-w-xl text-sm leading-5 font-medium text-gray-900 dark:text-white">
                                        <div class="flex items-center space-x-3 lg:pl-2">
                                            <div class="flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $variant->is_visible ? 'bg-green-600': 'bg-gray-400' }}"></div>
                                            <div class="flex items-center">
                                                @if($variant->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
                                                    <img class="h-8 w-8 rounded object-cover object-center" src="{{ $variant->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')) }}" alt="" />
                                                @else
                                                    <div class="bg-gray-200 dark:bg-gray-700 flex items-center justify-center h-8 w-8 rounded">
                                                        <x-heroicon-o-photograph class="w-5 h-5 text-gray-400" />
                                                    </div>
                                                @endif
                                                <span class="ml-2 truncate">
                                                    <span>{{ $variant->name }} </span>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 table-cell whitespace-no-wrap text-sm leading-5 text-gray-500 dark:text-gray-400 font-medium">
                                        <span>@if($variant->sku) {{ $variant->sku }} @else &mdash; @endif</span>
                                    </td>
                                    <td class="px-6 py-3 table-cell whitespace-no-wrap text-sm leading-5 text-gray-500 dark:text-gray-400 font-medium">
                                        <span>@if($variant->formattedPrice) {{ $variant->formattedPrice }} @else &mdash; @endif</span>
                                    </td>
                                    <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center">
                                            <span class="mr-2 text-xs px-1.5 inline-flex leading-5 font-medium rounded-full {{ $variant->stock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $variant->stock }}
                                            </span>
                                            {{ __('in stock') }}
                                        </div>
                                    </td>
                                    <td class="pr-6">
                                        <x-shopper-dropdown customAlignmentClasses="right-12 -bottom-1">
                                            <x-slot name="trigger">
                                                <button id="variant-options-menu" aria-has-popup="true" :aria-expanded="open" type="button" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 dark:focus:bg-gray-700 transition ease-in-out duration-150">
                                                    <x-heroicon-s-dots-vertical class="w-5 h-5" />
                                                </button>
                                            </x-slot>

                                            <x-slot name="content">
                                                <div class="py-1">
                                                    <a href="{{ route('shopper.products.variant', ['product' => $product->id, 'id' => $variant->id]) }}" class="group flex items-center px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white" role="menuitem">
                                                        <x-heroicon-s-pencil-alt class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" />
                                                        {{ __('Edit') }}
                                                    </a>
                                                </div>
                                                <div class="border-t border-gray-100 dark:border-gray-600"></div>
                                                <div class="py-1">
                                                    <button wire:click="remove({{ $variant->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 hover:bg-gray-100 dark:hover:text-white hover:text-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 dark:focus:text-white focus:text-gray-900" role="menuitem">
                                                        <x-heroicon-s-pencil-alt class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" />
                                                        {{ __('Delete') }}
                                                    </button>
                                                </div>
                                            </x-slot>
                                        </x-shopper-dropdown>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                        <div class="flex justify-center items-center space-x-2">
                                            <x-heroicon-o-book-open class="h-8 w-8 text-gray-400" />
                                            <span class="font-medium py-8 text-gray-400 text-xl">{{ __('No variant found') }}...</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center rounded-b-md justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $variants->links('shopper::livewire.wire-mobile-pagination-links') }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm leading-5 text-gray-700 dark:text-gray-300">
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
