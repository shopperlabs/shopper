<x-shopper::form-slider-over
    action="store"
    :title="$attribute
        ? $attribute->name
        : __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/attributes.single')])
    "
>
    {{ $this->form }}
</x-shopper::form-slider-over>
