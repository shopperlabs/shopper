@php
    $record = $getRecord();
@endphp

<div>
    @if ($record->attribute_value_id)
        @php
            $attributeValue = $record->value;
        @endphp

        <span class="inline-flex items-center gap-2 text-sm leading-6 text-sh-fg-muted">
            @if ($attributeValue->attribute->type === \Shopper\Core\Enum\FieldType::ColorPicker)
                <span
                    class="inline-flex items-center rounded-full p-1 ring-1 ring-sh-border ring-inset"
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
        <span class="text-sh-fg-muted">--</span>
    @endif
</div>
