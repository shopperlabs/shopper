<x-shopper::layouts.app :title="__('shopper::messages.actions_label.edit', ['name' => $brand->name])">

    <livewire:shopper-brands.edit :brand="$brand" />

</x-shopper::layouts.app>
