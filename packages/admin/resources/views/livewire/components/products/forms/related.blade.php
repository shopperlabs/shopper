<x-shopper::container>
    <div class="sm:flex sm:justify-between">
        <x-shopper::section-heading
            :title="__('shopper::pages/products.related.title')"
            :description="__('shopper::pages/products.related.description')"
        />

        @if ($relatedProducts->isNotEmpty())
            <div class="mt-6 lg:mt-0">
                <x-filament::button
                    type="button"
                    wire:click="$dispatch('openModal', {
                        component: 'shopper-modals.related-products-list',
                        arguments: { product: {{ $product }}, ids: {{ json_encode($this->productsIds) }} },
                    })"
                >
                    {{ __('shopper::layout.account_dropdown.add_product') }}
                </x-filament::button>
            </div>
        @endif
    </div>

    <x-shopper::card class="mt-10 p-5">
        @if ($relatedProducts->isNotEmpty())
            <div role="list" class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-5 lg:gap-x-8">
                @foreach ($relatedProducts as $relatedProduct)
                    <div wire:key="{{ $relatedProduct->slug }}" class="group relative">
                        <div class="relative">
                            <div class="overflow-hidden bg-gray-100 dark:bg-gray-800">
                                <img
                                    src="{{ $relatedProduct->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection')) }}"
                                    alt="{{ $relatedProduct->name }} Thumbnail"
                                    class="h-40 w-full max-w-none rounded-lg object-cover object-center"
                                />
                            </div>
                            <div class="absolute top-4 right-4">
                                <x-filament-actions::group
                                    :actions="[
                                        ($this->removeAction)(['id' => $relatedProduct->id]),
                                    ]"
                                    icon="untitledui-dots-vertical"
                                    color="gray"
                                    size="md"
                                    dropdown-placement="bottom-start"
                                />

                                <x-filament-actions::modals />
                            </div>
                        </div>
                        <div class="mt-4 flex items-start justify-between">
                            <div>
                                <h3 class="text-sm">
                                    <x-shopper::link
                                        class="font-semibold text-gray-950 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white"
                                        :href="route('shopper.products.edit', $relatedProduct)"
                                    >
                                        {{ $relatedProduct->name }}
                                    </x-shopper::link>
                                </h3>
                            </div>
                            <div class="flex shrink-0 items-center">
                                <div
                                    @class([
                                        'h-1.5 w-1.5 self-center rounded-full',
                                        'bg-success-500' => $relatedProduct->is_visible,
                                        'bg-rose-500' => ! $relatedProduct->is_visible,
                                    ])
                                ></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-shopper::empty-card
                icon="untitledui-book-open"
                :heading="__('shopper::pages/products.related.empty')"
                :description="__('shopper::pages/products.related.add_content')"
            >
                <x-slot:action>
                    <x-filament::button
                        type="button"
                        wire:click="$dispatch('openModal', {
                            component: 'shopper-modals.related-products-list',
                            arguments: { product: {{ $product }}, ids: {{ json_encode($this->productsIds) }} },
                        })"
                    >
                        <x-untitledui-plus class="mr-2 size-5" stroke-width="1.5" aria-hidden="true" />
                        {{ __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/products.single')]) }}
                    </x-filament::button>
                </x-slot>
            </x-shopper::empty-card>
        @endif
    </x-shopper::card>
</x-shopper::container>
