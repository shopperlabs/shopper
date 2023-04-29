<x-shopper::layouts.app :title="__('shopper::words.actions_label.edit', ['name' => $category->name])">

    <livewire:shopper-categories.edit :category="$category" />

</x-shopper::layouts.app>
