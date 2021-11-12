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
                {{ __($label) }} @if($isRequired) <span class="text-red-500">*</span> @endif
            </label>
            @if($optional)
                <span class="text-secondary-500 text-sm leading-5 dark:text-secondary-400">{{ __('Optional') }}</span>
            @endif
        </div>
    @endif

    <div class="@if($label) mt-1 @endif relative @if(!$noShadow) rounded-md shadow-sm @endif">
        {{ $slot }}
    </div>
    @if ($error)
        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $error }}</p>
    @endif

    @if ($helpText)
        <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">{{ __($helpText) }}</p>
    @endif
</div>
