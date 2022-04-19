<x-shopper::layouts.app :title="__('shopper::messages.actions_label.edit', ['name' => $category->name])">

    <livewire:shopper-categories.edit :category="$category" />

</x-shopper::layouts.app>
