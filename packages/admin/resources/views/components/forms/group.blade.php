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
            <label for="{{ $for }}" class="text-sh-fg-secondary block text-sm font-medium">
                {{ $label }}
                @if ($isRequired)
                    <span class="text-danger-500">*</span>
                @endif
            </label>
            @if ($optional)
                <span class="text-sh-fg-muted text-sm">
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
        <p class="text-sh-fg-muted mt-2 text-sm">
            {{ $helpText }}
        </p>
    @endif
</div>
