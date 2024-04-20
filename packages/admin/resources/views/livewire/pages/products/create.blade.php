<div class="flex min-h-(screen-content) flex-col space-y-10">
    <x-shopper::container>
        <x-shopper::breadcrumb :back="route('shopper.products.index')">
            <x-untitledui-chevron-left class="h-4 w-4 shrink-0 text-gray-300 dark:text-gray-600" />
            <x-shopper::breadcrumb.link
                :link="route('shopper.products.index')"
                :title="__('shopper::layout.sidebar.products')"
            />
        </x-shopper::breadcrumb>

        <x-shopper::heading class="mt-5">
            <x-slot name="title">
                {{ __('shopper::words.actions_label.add_new', ['name' => __('shopper::words.product')]) }}
            </x-slot>
        </x-shopper::heading>
    </x-shopper::container>

    <form
        wire:submit="store"
        class="shopper-product-wizard flex min-h-full flex-1 flex-col border-t border-gray-200 dark:border-gray-700"
    >
        {{ $this->form }}
    </form>
</div>
