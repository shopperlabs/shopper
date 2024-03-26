<x-shopper::card class="overflow-hidden">
    <div class="bg-gray-50 dark:bg-gray-900 p-4 sm:py-5 sm:px-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h3 class="text-base text-gray-900 leading-6 font-medium dark:text-white font-heading">
                {{ __('shopper::layout.sidebar.products') }}
            </h3>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                @if($collection->isManual())
                    <span class="inline-flex rounded-md shadow-sm">
                        <x-shopper::buttons.default wire:click="$emit('openModal', 'shopper-modals.products-lists', {{ json_encode([$collection->id, $this->productsIds]) }})" type="button">
                            {{ __('shopper::layout.forms.label.browse') }}
                        </x-shopper::buttons.default>
                    </span>
                @endif
                <div class="relative">
                    <x-shopper::label for="sort" class="sr-only" :value="__('Sort products by')" />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-sm text-gray-500 leading-5 dark:text-gray-400">
                            {{ __('shopper::layout.forms.label.sort_by') }}
                        </span>
                    </div>
                    <x-shopper::forms.select wire:model.lazy="sortBy" id="sort" class="pl-18 pr-10 py-2">
                        <option value="alpha_asc">{{ __('shopper::pages/collections.order.alpha_asc') }}</option>
                        <option value="alpha_desc">{{ __('shopper::pages/collections.order.alpha_desc') }}</option>
                        <option value="price_asc">{{ __('shopper::pages/collections.order.price_asc') }}</option>
                        <option value="price_desc">{{ __('shopper::pages/collections.order.price_desc') }}</option>
                        <option value="created_asc">{{ __('shopper::pages/collections.order.created_asc') }}</option>
                        <option value="created_desc">{{ __('shopper::pages/collections.order.created_desc') }}</option>
                    </x-shopper::forms.select>
                </div>
            </div>
        </div>
    </div>
    <div class="border-t border-gray-200 p-4 sm:px-6 sm:py-5 dark:border-gray-700" wire:poll.visible>
        @if($products->isNotEmpty())
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($products as $product)
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center">
                            <span class="shrink-0 h-10 w-10 rounded-md overflow-hidden">
                                <img class="object-cover object-center w-full h-full block" src="{{ $product->getFirstMediaUrl(config('shopper.core.storage.collection_name')) }}" alt="{{ $product->name }}" />
                            </span>
                            <p class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ $product->name }}
                            </p>
                        </div>
                        <button wire:key="product_{{ $loop->index }}" wire:click="removeProduct({{ $product->id }})" type="button" class="text-gray-500 text-sm font-medium inline-flex items-center dark:text-gray-400">
                            <x-untitledui-x class="h-5 w-5"/>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-5 w-full max-w-xs mx-auto flex flex-col items-center justify-center">
                <span class="shrink-0 w-10 h-10">
                    <x-untitledui-book-open class="w-full h-full text-gray-500" stroke-width="1.5" />
                </span>
                <p class="mt-2.5 text-sm text-gray-500 leading-5 text-center dark:text-gray-400">
                    {{ __('shopper::pages/collections.empty_collections') }}
                </p>
                @if($collection->isAutomatic())
                    <div class="relative mt-3">
                        <span class="inline-flex rounded-md shadow-sm">
                            <x-shopper::buttons.default type="button">
                                {{ __('shopper::pages/collections.conditions.update') }}
                                <span class="ml-1.5 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                  {{ __('shopper::layout.soon') }}
                                </span>
                            </x-shopper::buttons.default>
                        </span>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-shopper::card>
