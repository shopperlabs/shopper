<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/tags.menu')">
        <x-slot name="action">
            @can('tags.create')
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
