<x-shopper::form-slider-over
    action="store"
    :title="$attribute
        ? $attribute->name
        : __('shopper::words.actions_label.add_new', ['name' => strtolower(__('shopper::words.attribute'))])
    "
>
    {{ $this->form }}
</x-shopper::form-slider-over>
