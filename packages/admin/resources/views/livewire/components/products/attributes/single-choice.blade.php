<x-shopper::attribute-card :activated="$activated" :attribute="$attribute">
    <x-slot:action>
        <div class="flex items-center gap-2">
            @if ($model)
                {{ ($this->removeAction)(['id' => $model->id]) }}
            @endif

            {{ ($this->saveAction)(['column' => 'value']) }}
        </div>
    </x-slot>

    <ul role="list" class="space-y-2">
        @foreach ($attribute->values as $value)
            <li class="flex items-center gap-2">
                <x-shopper::forms.radio
                    wire:model.live.debounce="value"
                    id="attribute_single_value_{{ $value->id }}"
                    value="{{ $value->id }}"
                />
                <x-shopper::label
                    for="attribute_single_value_{{ $value->id }}"
                    :value="$value->value"
                    class="cursor-pointer"
                />
            </li>
        @endforeach
    </ul>
</x-shopper::attribute-card>
