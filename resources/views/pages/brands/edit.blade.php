<x-shopper::layouts.app :title="__('Update brand :name', ['name' => $brand->name])">

    <livewire:shopper-brands.edit :brand="$brand" />

</x-shopper::layouts.app>
