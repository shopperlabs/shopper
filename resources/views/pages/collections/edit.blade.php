<x-shopper::layouts.app :title="__('Update collection :name', ['name' => $collection->name])">

    <livewire:shopper-collections.edit :collection="$collection" />

</x-shopper::layouts.app>
