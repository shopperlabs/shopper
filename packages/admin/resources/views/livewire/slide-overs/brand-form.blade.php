<x-shopper::form-slider-over
    action="save"
    :title="$brand->id ? $brand->name : __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/brands.single')])"
>
    {{ $this->form }}
</x-shopper::form-slider-over>
