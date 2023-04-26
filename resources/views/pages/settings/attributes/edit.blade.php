<x-shopper::layouts.setting :title="__('pages/attributes.update', ['attribute' => $attribute->name])">

    <livewire:shopper-settings.attributes.edit :attribute="$attribute" />

</x-shopper::layouts.setting>
