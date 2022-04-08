<x-shopper::layouts.setting :title="__('Update attribute :attribute', ['attribute' => $attribute->name])">

    <livewire:shopper-settings.attributes.edit :attribute="$attribute" />

</x-shopper::layouts.setting>
