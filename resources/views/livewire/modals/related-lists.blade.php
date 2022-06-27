<x-shopper::modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-secondary-100 dark:border-secondary-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>
    <x-slot name="title">
        {{ __('shopper::pages/products.related.modal.title') }}
    </x-slot>

    <x-slot name="content">
        <div class="py-2">
            <x-shopper::forms.search label="shopper::pages/products.related.modal.search" placeholder="shopper::pages/products.related.modal.search_placeholder" />
        </div>
        <div class="my-2 -mx-2 divide-y divide-secondary-200 h-80 overflow-auto dark:divide-secondary-700">
            @forelse($this->products as $product)
                <x-shopper::forms.label-product :product="$product" wire:key="{{ $product->id }}" />
            @empty
                <div class="p-4 h-full flex items-center justify-center">
                    <div class="text-center">
                        <x-heroicon-o-book-open class="mx-auto h-10 w-10 text-secondary-400" />
                        <p class="mt-2 text-base font-medium text-secondary-900 dark:text-white">{{ __('shopper::pages/products.related.modal.no_results') }}</p>
                    </div>
                </div>
            @endforelse
        </div>
    </x-slot>

    <x-slot name="buttons">
        @if($this->products->isNotEmpty())
            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                <x-shopper::buttons.primary wire:click="addSelectedProducts" wire.loading.attr="disabled" type="button">
                    <x-shopper::loader wire:loading wire:target="addSelectedProducts" class="text-white" />
                    {{ __('shopper::pages/collections.modal.action') }}
                </x-shopper::buttons.primary>
            </span>
        @endif
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper::buttons.default wire:click="$emit('closeModal')" type="button">
                {{ __('shopper::layout.forms.actions.cancel') }}
            </x-shopper::buttons.default>
        </span>
    </x-slot>

</x-shopper::modal>
