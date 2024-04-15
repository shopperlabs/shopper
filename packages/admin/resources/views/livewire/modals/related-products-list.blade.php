<x-shopper::modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-gray-100 dark:border-gray-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>
    <x-slot name="title">
        {{ __('shopper::pages/products.related.modal.title') }}
    </x-slot>

    <x-slot name="content">
        <div class="py-2">
            <x-shopper::forms.search
                :label="__('shopper::pages/products.related.modal.search')"
                :placeholder="__('shopper::pages/products.related.modal.search_placeholder')"
            />
        </div>
        <div class="my-2 -mx-2 divide-y divide-gray-200 h-80 overflow-auto dark:divide-gray-700">
            @forelse($this->products as $product)
                <x-shopper::forms.label-product :product="$product" wire:key="{{ $product->id }}" />
            @empty
                <div class="p-4 h-full flex items-center justify-center">
                    <div class="text-center">
                        <x-untitledui-book-open
                            class="mx-auto h-8 w-8 text-gray-400 dark:text-gray-500"
                            stroke-width="1.5"
                            aria-hidden="true"
                        />
                        <p class="mt-2 font-medium text-gray-900 dark:text-white">
                            {{ __('shopper::pages/products.related.modal.no_results') }}
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </x-slot>

    <x-slot name="buttons">
        @if($this->products->isNotEmpty())
            <x-shopper::buttons.primary
                wire:click="addSelectedProducts"
                wire.loading.attr="disabled"
                :disabled="count($selectedProducts) <= 0"
                type="button"
                class="w-full sm:ml-3 sm:w-auto"
            >
                <x-shopper::loader wire:loading wire:target="addSelectedProducts" class="text-white" />
                {{ __('shopper::pages/collections.modal.action') }}
            </x-shopper::buttons.primary>
        @endif
        <x-shopper::buttons.default wire:click="$dispatch('closeModal')" type="button" class="mt-3 w-full sm:mt-0 sm:w-auto">
            {{ __('shopper::layout.forms.actions.cancel') }}
        </x-shopper::buttons.default>
    </x-slot>

</x-shopper::modal>
