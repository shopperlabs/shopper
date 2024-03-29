<x-shopper::form-slider-over
    action="save"
    :title="$category->id ? $category->name :__('shopper::words.actions_label.add_new', ['name' => strtolower(__('shopper::words.category'))])"
>
    {{ $this->form }}
</x-shopper::form-slider-over>
