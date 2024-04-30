<x-shopper::form-slider-over
    action="save"
    :title="$category->id ? $category->name :__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/categories.single')])"
>
    {{ $this->form }}
</x-shopper::form-slider-over>
