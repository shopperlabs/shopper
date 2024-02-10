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
    @if($label)
        <div class="flex items-center justify-between">
            <label for="{{ $for }}" class="block text-sm font-medium leading-5 text-secondary-700 dark:text-secondary-300">
                {{ $label }} @if($isRequired) <span class="text-danger-500">*</span> @endif
            </label>
            @if($optional)
                <span class="text-secondary-500 text-sm leading-5 dark:text-secondary-400">
                    {{ __('shopper::layout.forms.label.optional') }}
                </span>
            @endif
        </div>
    @endif

    <div @class([
        'relative',
        'mt-1' => $label,
        'rounded-md shadow-sm' => !$noShadow
    ])>
        {{ $slot }}
    </div>
    @if ($error)
        <p class="mt-1 text-sm text-danger-500">
            {{ $error }}
        </p>
    @endif

    @if ($helpText)
        <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">
            {{ $helpText }}
        </p>
    @endif
</div>
