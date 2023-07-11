<x-shopper::layouts.app :title="__('pages/attributes.update', ['attribute' => $attribute->name])">
    <livewire:shopper-attributes.edit :attribute="$attribute" />
</x-shopper::layouts.app>
