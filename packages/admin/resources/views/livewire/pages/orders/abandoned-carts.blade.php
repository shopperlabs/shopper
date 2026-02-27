<x-shopper::container class="py-5">
    <x-shopper::breadcrumb :back="route('shopper.orders.index')">
        <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.orders.index')"
            :title="__('shopper::pages/orders.menu')"
        />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-5" :title="__('shopper::pages/orders.abandoned_carts.title')" />

    <div class="mt-8">
        {{ $this->table }}
    </div>
</x-shopper::container>
