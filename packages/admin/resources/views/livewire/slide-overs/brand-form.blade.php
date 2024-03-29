<x-shopper::form-slider-over
    action="save"
    :title="$brand->id ? $brand->name : __('shopper::words.actions_label.add_new', ['name' => strtolower(__('shopper::layout.forms.label.brand'))])"
>
    {{ $this->form }}
</x-shopper::form-slider-over>
