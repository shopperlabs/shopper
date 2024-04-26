<x-shopper::form-slider-over
    action="store"
    :title="$discount->id ? $discount->code : __('shopper::pages/discounts.actions.create')"
    :description="__('shopper::pages/discounts.description')"
>
    {{ $this->form }}
</x-shopper::form-slider-over>
