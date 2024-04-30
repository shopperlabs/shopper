<x-shopper::form-slider-over
    action="store"
    :title="$discount->id ? $discount->code : __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/discounts.single')])"
    :description="__('shopper::pages/discounts.description')"
>
    {{ $this->form }}
</x-shopper::form-slider-over>
