@php
    $record = $getRecord();
    $swatch = $record->getFirstMediaUrl('swatch');
@endphp

<div>
    @if ($record->attribute_value_id)
        @php
            $attributeValue = $record->value;
        @endphp

        <span class="text-sh-fg-muted inline-flex items-center gap-2 text-sm leading-6">
            @if ($swatch)
                <img
                    src="{{ $swatch }}"
                    alt="{{ $attributeValue->value }}"
                    class="ring-sh-border size-6 rounded-md object-cover ring-1 ring-inset"
                />
            @elseif ($attributeValue->attribute->type === \Shopper\Core\Enum\FieldType::ColorPicker)
                <span class="ring-sh-border inline-flex items-center rounded-full p-1 ring-1 ring-inset">
                    <x-shopper::icons.contrast
                        class="size-5"
                        style="color: {{ $attributeValue->key }}"
                        aria-hidden="true"
                    />
                </span>
            @endif

            {{ $attributeValue->value }}
        </span>
    @elseif ($swatch)
        <span class="text-sh-fg-muted inline-flex items-center gap-2 text-sm leading-6">
            <img
                src="{{ $swatch }}"
                alt="{{ $record->attribute_custom_value }}"
                class="ring-sh-border size-6 rounded-md object-cover ring-1 ring-inset"
            />
            {{ $record->attribute_custom_value }}
        </span>
    @else
        <span class="text-sh-fg-muted">--</span>
    @endif
</div>
