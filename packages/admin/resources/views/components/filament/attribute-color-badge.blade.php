@blaze

@props([
    'key',
    'value' => null,
])

<span
    class="text-sh-fg-secondary ring-sh-border inline-flex items-center gap-x-2 rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset"
>
    <x-shopper::icons.contrast class="size-5" style="color: {{ $key }}" aria-hidden="true" />
    @if ($value)
        <kb>{{ $value }}</kb>
    @endif
</span>
