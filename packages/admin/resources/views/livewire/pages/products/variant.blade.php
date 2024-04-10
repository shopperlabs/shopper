<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.products.index')">
        <x-untitledui-chevron-left
            class="h-4 w-4 text-gray-300 dark:text-gray-600"
            aria-hidden="true"
        />
        <x-shopper::breadcrumb.link
            :link="route('shopper.products.edit', $product)"
            :title="$product->name"
            class="truncate"
        />
        <x-untitledui-chevron-left
            class="h-4 w-4 text-gray-300 dark:text-gray-600"
            aria-hidden="true"
        />
        <span class="text-gray-500 dark:text-gray-400 truncate">
            {{ $variant->name }}
        </span>
    </x-shopper::breadcrumb>

    <div class="z-30 pb-5 mt-5 border-b border-gray-200 dark:border-gray-700">
        <div class="space-y-3 lg:flex lg:items-center lg:justify-between lg:space-y-0">
            <div class="flex items-center flex-1 min-w-0 space-x-3">
                <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate dark:text-white font-heading">
                    {{ $variant->name }}
                </h3>
                <x-shopper::badge
                    :style="$product->is_visible ? 'success' : 'warning'"
                    :value="$product->is_visible ? __('shopper::layout.forms.label.visible'): __('shopper::layout.forms.label.invisible')"
                />
            </div>
        </div>
    </div>

    <form wire:submit="store" class="py-8">
        <div class="space-y-10">
            {{ $this->form }}
            <div class="pt-8 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                <x-shopper::buttons.primary type="submit" wire:loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="store" />
                    {{ __('shopper::pages/products.variants.actions.update') }}
                </x-shopper::buttons.primary>
            </div>
        </div>
    </form>

</x-shopper::container>
