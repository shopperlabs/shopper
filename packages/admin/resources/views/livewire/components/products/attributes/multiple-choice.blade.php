<x-shopper::attribute-card :activated="$activated" :attribute="$attribute">
    <x-slot:action>
        {{ $this->saveAction }}
    </x-slot>

    <ul role="list" class="space-y-2">
        @foreach ($attribute->values as $value)
            <li class="flex items-center gap-2">
                <x-shopper::forms.checkbox
                    wire:model.live.debounce="selected"
                    id="attribute_value_{{ $value->id }}"
                    value="{{ $value->id }}"
                />
                <x-shopper::label
                    for="attribute_value_{{ $value->id }}"
                    :value="$value->value"
                    class="cursor-pointer"
                />
                @if ($attribute->type === \Shopper\Core\Enum\FieldType::ColorPicker)
                    <span
                        class="inline-flex items-center rounded-full p-1 ring-1 ring-inset ring-gray-200 dark:text-gray-300 dark:ring-gray-700"
                    >
                        <x-shopper::icons.contrast
                            class="h-5 w-5"
                            style="color: {{ $value->key }}"
                            aria-hidden="true"
                        />
                    </span>
                @endif
            </li>
        @endforeach
    </ul>
</x-shopper::attribute-card>
