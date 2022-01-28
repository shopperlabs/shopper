<div>
    <div>
        <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">
            {{ __('Products variations') }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-secondary-500">
            {{ __('All variations of your product. The variations can each have their stock and price.') }}
        </p>
    </div>

    <section aria-labelledby="products_variations_heading">
        <div class="mt-5 bg-white dark:bg-secondary-800 pt-5 shadow rounded-md">
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
                            <tr class="border-t border-secondary-200 dark:border-secondary-700">
                                <th class="px-6 py-3 border-b border-secondary-200 dark:border-secondary-700 bg-secondary-50 dark:bg-secondary-700 text-left text-xs leading-4 font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">
                                    <span class="lg:pl-2">{{ __('Variant') }}</span>
                                </th>
                                <th class="px-6 py-3 border-b border-secondary-200 dark:border-secondary-700 bg-secondary-50 dark:bg-secondary-700 text-left text-xs leading-4 font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">
                                    {{ __('SKU') }}
                                </th>
                                <th class="px-6 py-3 border-b border-secondary-200 dark:border-secondary-700 bg-secondary-50 dark:bg-secondary-700 text-left text-xs leading-4 font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">
                                    {{ __('Price') }}
                                </th>
                                <th class="px-6 py-3 border-b border-secondary-200 dark:border-secondary-700 bg-secondary-50 dark:bg-secondary-700 text-left text-xs leading-4 font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider">
                                    {{ __('Current stock') }}
                                </th>
                                <th class="pr-6 py-3 border-b border-secondary-200 dark:border-secondary-700 bg-secondary-50 dark:bg-secondary-700 text-right text-xs leading-4 font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary-200 dark:divide-secondary-700" x-max="1">
                            @forelse($variants as $variant)
                                <tr>
                                    <td class="px-6 py-3 max-w-xl text-sm leading-5 font-medium text-secondary-900 dark:text-white">
                                        <div class="flex items-center space-x-3 lg:pl-2">
                                            <div class="shrink-0 w-2.5 h-2.5 rounded-full {{ $variant->is_visible ? 'bg-green-600': 'bg-secondary-400' }}"></div>
                                            <div class="flex items-center">
                                                @if($variant->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
                                                    <img class="h-8 w-8 rounded object-cover object-center" src="{{ $variant->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')) }}" alt="" />
                                                @else
                                                    <div class="bg-secondary-200 dark:bg-secondary-700 flex items-center justify-center h-8 w-8 rounded">
                                                        <x-heroicon-o-photograph class="w-5 h-5 text-secondary-400" />
                                                    </div>
                                                @endif
                                                <span class="ml-2 truncate">
                                                    <span>{{ $variant->name }} </span>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 table-cell whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400 font-medium">
                                        <span>@if($variant->sku) {{ $variant->sku }} @else &mdash; @endif</span>
                                    </td>
                                    <td class="px-6 py-3 table-cell whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400 font-medium">
                                        <span>@if($variant->formattedPrice) {{ $variant->formattedPrice }} @else &mdash; @endif</span>
                                    </td>
                                    <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400">
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
                                                <button id="variant-options-menu" aria-has-popup="true" :aria-expanded="open" type="button" class="w-8 h-8 inline-flex items-center justify-center text-secondary-400 rounded-full bg-transparent hover:text-secondary-500 focus:outline-none focus:text-secondary-500 focus:bg-secondary-100 dark:focus:bg-secondary-700 transition ease-in-out duration-150">
                                                    <x-heroicon-s-dots-vertical class="w-5 h-5" />
                                                </button>
                                            </x-slot>

                                            <x-slot name="content">
                                                <div class="py-1">
                                                    <a href="{{ route('shopper.products.variant', ['product' => $product->id, 'id' => $variant->id]) }}" class="group flex items-center px-4 py-2 text-sm leading-5 text-secondary-700 dark:text-secondary-400 hover:bg-secondary-100 dark:hover:bg-secondary-600 hover:text-secondary-900 dark:hover:text-white" role="menuitem">
                                                        <x-heroicon-s-pencil-alt class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500" />
                                                        {{ __('Edit') }}
                                                    </a>
                                                </div>
                                                <div class="border-t border-secondary-100 dark:border-secondary-600"></div>
                                                <div class="py-1">
                                                    <button wire:click="remove({{ $variant->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-secondary-700 dark:text-secondary-400 dark:hover:bg-secondary-700 hover:bg-secondary-100 dark:hover:text-white hover:text-secondary-900 focus:outline-none focus:bg-secondary-100 dark:focus:bg-secondary-700 dark:focus:text-white focus:text-secondary-900" role="menuitem">
                                                        <x-heroicon-s-pencil-alt class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500" />
                                                        {{ __('Delete') }}
                                                    </button>
                                                </div>
                                            </x-slot>
                                        </x-shopper-dropdown>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-secondary-900">
                                        <div class="flex justify-center items-center space-x-2">
                                            <x-heroicon-o-book-open class="h-8 w-8 text-secondary-400" />
                                            <span class="font-medium py-8 text-secondary-400 text-xl">{{ __('No variant found') }}...</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white dark:bg-secondary-800 px-4 py-3 flex items-center rounded-b-md justify-between border-t border-secondary-200 dark:border-secondary-700 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $variants->links('shopper::livewire.wire-mobile-pagination-links') }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm leading-5 text-secondary-700 dark:text-secondary-300">
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
