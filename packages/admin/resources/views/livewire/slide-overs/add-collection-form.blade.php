<x-shopper::form-slider-over
    :title="__('shopper::words.actions_label.add_new', ['name' => strtolower(__('shopper::words.collection'))])"
    action="store"
>
    {{ $this->form }}
</x-shopper::form-slider-over>
