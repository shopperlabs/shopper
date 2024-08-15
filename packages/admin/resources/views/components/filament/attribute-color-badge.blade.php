@props([
    'key',
    'value' => null,
])

<span
    class="inline-flex items-center gap-x-2 rounded-full px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-200 dark:text-gray-300 dark:ring-white/10"
>
    <x-shopper::icons.contrast class="size-5" style="color: {{ $key }}" aria-hidden="true" />
    @if ($value)
        <kb>{{ $value }}</kb>
    @endif
</span>
