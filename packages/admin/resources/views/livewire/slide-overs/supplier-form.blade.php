<x-shopper::form-slider-over
    action="save"
    :title="$supplier->id ? $supplier->name : __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/suppliers.single')])"
>
    {{ $this->form }}
</x-shopper::form-slider-over>
