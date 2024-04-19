@props([
    'value',
    'isRequired' => false,
])

<label {{ $attributes->twMerge(['class' => 'block font-medium text-sm text-gray-700 dark:text-gray-300']) }}>
    {{ $value ?? $slot }}
    @if ($isRequired)
        <span class="text-red-500">*</span>
    @endif
</label>
