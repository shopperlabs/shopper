@php
    $record = $getRecord();
@endphp

<div>
    @if($record->attribute_value_id)
        @php
            $attributeValue = $record->value;
        @endphp

        <span class="inline-flex items-center text-sm leading-6 gap-2 text-gray-500 dark:text-gray-400">
            @if($attributeValue->attribute->type === \Shopper\Core\Enum\FieldType::ColorPicker)
                <span
                    class="inline-flex items-center rounded-full p-1 ring-1 ring-inset ring-gray-200 dark:text-gray-300 dark:ring-white/10"
                >
                    <x-shopper::icons.contrast
                        class="size-5"
                        style="color: {{ $attributeValue->key }}"
                        aria-hidden="true"
                    />
                </span>
            @endif
            {{ $attributeValue->value }}
        </span>
    @else
        <span class="text-gray-500 dark:text-gray-400">--</span>
    @endif
</div>
