<x-shopper::container class="py-5">
    <x-shopper::breadcrumb :back="route('shopper.products.index')">
        <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.products.index')"
            :title="__('shopper::pages/products.menu')"
        />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-5" :title="__('shopper::pages/tags.menu')">
        <x-slot name="action">
            @can('add_tags')
                {{ $this->createAction }}
            @endcan
        </x-slot>
    </x-shopper::heading>

    {{ shopper()->getRenderHook(\Shopper\View\CatalogRenderHook::TAGS_TABLE_BEFORE) }}

    <div class="mt-8">
        {{ $this->table }}
    </div>

    {{ shopper()->getRenderHook(\Shopper\View\CatalogRenderHook::TAGS_TABLE_AFTER) }}
</x-shopper::container>
