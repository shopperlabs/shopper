<x-shopper::layouts.app :title="__('Update category :name', ['name' => $category->name])">

    <livewire:shopper-categories.edit :category="$category" />

</x-shopper::layouts.app>
