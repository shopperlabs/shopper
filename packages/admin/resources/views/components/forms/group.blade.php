@props([
    'label' => false,
    'for' => false,
    'noShadow' => false,
    'isRequired' => false,
    'error' => false,
    'helpText' => false,
    'optional' => false,
])

<div {{ $attributes }}>
    @if ($label)
        <div class="flex items-center justify-between">
            <label for="{{ $for }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ $label }}
                @if ($isRequired)
                    <span class="text-danger-500">*</span>
                @endif
            </label>
            @if ($optional)
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('shopper::forms.label.optional') }}
                </span>
            @endif
        </div>
    @endif

    <div
        @class([
            'relative',
            'mt-1' => $label,
            'rounded-md shadow-sm' => ! $noShadow,
        ])
    >
        {{ $slot }}
    </div>
    @if ($error)
        <p class="text-danger-500 mt-1 text-sm">
            {{ $error }}
        </p>
    @endif

    @if ($helpText)
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            {{ $helpText }}
        </p>
    @endif
</div>
