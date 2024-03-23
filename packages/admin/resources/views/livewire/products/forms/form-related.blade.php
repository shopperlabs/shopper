<x-shopper::container>
    <div class="sm:flex sm:justify-between">
        <div>
            <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white font-heading">
                {{ __('shopper::pages/products.related.title') }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-secondary-500 dark:text-secondary-400">
                {{ __('shopper::pages/products.related.description') }}
            </p>
        </div>
        @if($relatedProducts->isNotEmpty())
            <div class="mt-6 lg:mt-0">
                <x-shopper::buttons.primary type="button" wire:click="$emit('openModal', 'shopper-modals.related-list', {{ json_encode([$product->id, $this->productsIds]) }})">
                    {{ __('shopper::layout.account_dropdown.add_product') }}
                </x-shopper::buttons.primary>
            </div>
        @endif
    </div>

    <section aria-labelledby="similar_products_heading">
        <x-shopper::card class="mt-5 p-4 sm:p-5">
            <div role="list" class="text-sm font-medium text-secondary-500 divide-y divide-secondary-200 dark:text-secondary-400 dark:divide-secondary-700 lg:grid lg:grid-cols-3 lg:gap-x-8 lg:gap-y-5 lg:divide-none" wire:poll.visible.30s>
                @forelse($relatedProducts as $relatedProduct)
                    <div class="flex py-6 space-x-6 lg:py-0">
                        <img src="{{ $relatedProduct->getFirstMediaUrl(config('shopper.core.storage.collection_name')) }}" alt="{{ $relatedProduct->name }}" class="flex-none w-20 h-20 bg-secondary-100 dark:bg-secondary-900 rounded-md object-center object-cover">
                        <div class="flex-auto space-y-2">
                            <h3 class="text-secondary-900 dark:text-white">
                                <a href="{{ route('shopper.products.edit', $relatedProduct) }}">
                                    {{ $relatedProduct->name }}
                                </a>
                            </h3>
                            <p class="font-medium text-secondary-500 dark:text-secondary-400">
                                {{ $relatedProduct->getPriceAmount()?->formatted }}
                            </p>
                            <button wire:click="remove({{ $relatedProduct->id }})" type="button" class="inline-flex text-red-500 text-sm leading-5 focus:outline-none hover:underline">
                                {{ __('shopper::layout.forms.actions.remove') }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center lg:col-span-3">
                        <x-untitledui-book-open class="mx-auto h-10 w-10 text-primary-600" stroke-width="1.5" />
                        <h3 class="mt-2 text-base font-medium text-secondary-900 dark:text-white">
                            {{ __('shopper::pages/products.related.empty') }}
                        </h3>
                        <p class="mt-1 text-sm text-secondary-500 dark:text-secondary-400">
                            {{ __('shopper::pages/products.related.add_content') }}
                        </p>
                        <div class="mt-6">
                            <x-shopper::buttons.primary type="button" wire:click="$emit('openModal', 'shopper-modals.related-list', {{ json_encode([$product->id, $this->productsIds]) }})">
                                <x-untitledui-plus class="mr-2 h-5 w-5" />
                                {{ __('shopper::words.actions_label.add_new', ['name' => strtolower(__('shopper::words.product'))]) }}
                            </x-shopper::buttons.primary>
                        </div>
                    </div>
                @endforelse
            </div>
        </x-shopper::card>
    </section>
</x-shopper::container>
