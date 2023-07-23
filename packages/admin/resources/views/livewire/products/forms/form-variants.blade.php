<x-shopper::container>
    <div>
        <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white font-display">
            {{ __('shopper::pages/products.variants.title') }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-secondary-500">
            {{ __('shopper::pages/products.variants.description') }}
        </p>
    </div>

    <section aria-labelledby="products_variations_heading">
        <div class="mt-5">
            <div class="flex items-center justify-between space-x-4 lg:max-w-xl">
                <x-shopper::forms.search :label="__('shopper::pages/products.variants.search_label')" :placeholder="__('shopper::pages/products.variants.search_placeholder')" />
                <div>
                    <span class="shadow-sm rounded-md">
                        <x-shopper::buttons.primary wire:click="$emit('openModal', 'shopper-modals.add-variant', {{ json_encode([$product->id, $currency]) }})" type="button">
                            {{ __('shopper::pages/products.variants.add') }}
                        </x-shopper::buttons.primary>
                    </span>
                </div>
            </div>
            <x-shopper::card class="mt-5 overflow-x-auto">
                <div class="align-middle inline-block min-w-full border-b border-secondary-200 dark:border-secondary-700">
                    <table class="min-w-full">
                        <thead class="rounded-t-lg bg-secondary-50 dark:bg-secondary-700">
                            <tr>
                                <th class="px-6 py-3 border-b border-secondary-200 dark:border-secondary-700 text-left text-xs leading-4 font-medium text-secondary-500 whitespace-nowrap dark:text-secondary-400 uppercase tracking-wider">
                                    <span class="lg:pl-2">{{ __('shopper::words.variant') }}</span>
                                </th>
                                <th class="hidden px-6 py-3 border-b border-secondary-200 dark:border-secondary-700 text-left text-xs leading-4 font-medium text-secondary-500 whitespace-nowrap dark:text-secondary-400 uppercase tracking-wider lg:table-cell">
                                    {{ __('shopper::layout.tables.sku') }}
                                </th>
                                <th class="px-6 py-3 border-b border-secondary-200 dark:border-secondary-700 text-left text-xs leading-4 font-medium text-secondary-500 whitespace-nowrap dark:text-secondary-400 uppercase tracking-wider">
                                    {{ __('shopper::words.price') }}
                                </th>
                                <th class="hidden px-6 py-3 border-b border-secondary-200 dark:border-secondary-700 text-left text-xs leading-4 font-medium text-secondary-500 whitespace-nowrap dark:text-secondary-400 uppercase tracking-wider lg:table-cell">
                                    {{ __('shopper::layout.tables.current_stock') }}
                                </th>
                                <th class="pr-6 py-3 border-b border-secondary-200 dark:border-secondary-700 text-right text-xs leading-4 font-medium text-secondary-500 whitespace-nowrap dark:text-secondary-400 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary-200 dark:divide-secondary-700">
                            @forelse($variants as $variant)
                                <tr>
                                    <td class="px-6 py-3 text-sm leading-5 font-medium text-secondary-900 dark:text-white whitespace-nowrap">
                                        <div class="flex items-center space-x-3 lg:pl-2">
                                            <div class="shrink-0 w-2.5 h-2.5 rounded-full {{ $variant->is_visible ? 'bg-green-600': 'bg-secondary-400' }}"></div>
                                            <div class="truncate flex items-center">
                                                <img class="h-8 w-8 rounded object-cover object-center" src="{{ $variant->getFirstMediaUrl(config('shopper.core.storage.collection_name')) }}" alt="" />
                                                <span class="ml-2 truncate">
                                                    <span>{{ $variant->name }} </span>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="hidden px-6 py-3 lg:table-cell whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400 font-medium">
                                        <span>@if($variant->sku) {{ $variant->sku }} @else &mdash; @endif</span>
                                    </td>
                                    <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400 font-medium">
                                        <span>@if($variant->price_amount) {{ $variant->getPriceAmount()->formatted }} @else &mdash; @endif</span>
                                    </td>
                                    <td class="hidden px-6 py-3 lg:table-cell whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                        <div class="flex items-center">
                                            <x-shopper::stock-badge :stock="$variant->stock" />
                                            {{ __('shopper::words.in_stock') }}
                                        </div>
                                    </td>
                                    <td class="pr-6 py-3 flex items-center justify-end">
                                        <x-shopper::dropdown customAlignmentClasses="right-6 bottom-2">
                                            <x-slot name="trigger">
                                                <button id="variant-options-menu" aria-has-popup="true" :aria-expanded="open" type="button" class="w-8 h-8 inline-flex items-center justify-center text-secondary-400 rounded-full bg-transparent hover:text-secondary-500 focus:outline-none focus:text-secondary-500 focus:bg-secondary-100 dark:focus:bg-secondary-700 transition ease-in-out duration-150">
                                                    <x-untitledui-dots-vertical class="w-5 h-5" />
                                                </button>
                                            </x-slot>

                                            <x-slot name="content">
                                                <div class="py-1">
                                                    <a href="{{ route('shopper.products.variant', ['product' => $product->id, 'id' => $variant->id]) }}" class="group flex items-center px-4 py-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400 hover:bg-secondary-100 dark:hover:bg-secondary-600 hover:text-secondary-900 dark:hover:text-white" role="menuitem">
                                                        <x-untitledui-edit-04 class="mr-2 h-5 w-5" />
                                                        {{ __('shopper::layout.forms.actions.edit') }}
                                                    </a>
                                                </div>
                                                <div class="border-t border-secondary-100 dark:border-secondary-600"></div>
                                                <div class="py-1">
                                                    <button wire:click="remove({{ $variant->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400 dark:hover:bg-secondary-600 hover:bg-secondary-100 dark:hover:text-white hover:text-secondary-700 focus:outline-none focus:bg-secondary-100 dark:focus:bg-secondary-700 dark:focus:text-white focus:text-secondary-900" role="menuitem">
                                                        <x-untitledui-trash-03 class="mr-2 h-5 w-5" />
                                                        {{ __('shopper::layout.forms.actions.delete') }}
                                                    </button>
                                                </div>
                                            </x-slot>
                                        </x-shopper::dropdown>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-secondary-900">
                                        <div class="py-6 flex flex-col space-y-2 justify-center items-center">
                                            <x-untitledui-book-open class="h-8 w-8 text-primary-500" />
                                            <span class="font-medium text-secondary-500 dark:text-secondary-400 text-xl">
                                                {{ __('shopper::pages/products.variants.empty') }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 flex items-center justify-between sm:px-6">
                    <div class="flex-1 flex justify-between lg:hidden">
                        {{ $variants->links('shopper::livewire.wire-mobile-pagination-links') }}
                    </div>
                    <div class="hidden lg:flex-1 lg:flex lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm leading-5 text-secondary-700 dark:text-secondary-300">
                                {{ __('shopper::words.showing') }}
                                <span class="font-medium">{{ ($variants->currentPage() - 1) * $variants->perPage() + 1 }}</span>
                                {{ __('shopper::words.to') }}
                                <span class="font-medium">{{ ($variants->currentPage() - 1) * $variants->perPage() + count($variants->items()) }}</span>
                                {{ __('shopper::words.of') }}
                                <span class="font-medium"> {!! $variants->total() !!}</span>
                                {{ __('shopper::words.results') }}
                            </p>
                        </div>
                        {{ $variants->links() }}
                    </div>
                </div>
            </x-shopper::card>
        </div>
    </section>

</x-shopper::container>
