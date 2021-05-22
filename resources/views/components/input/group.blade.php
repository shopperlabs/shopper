@props([
    'label' => false,
    'for' => false,
    'noShadow' => false,
    'isRequired' => false,
    'error' => false,
    'helpText' => false,
])

<div {{ $attributes }}>
    @if($label)
        <label for="{{ $for }}" class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-300">
            {{ __($label) }} @if($isRequired) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <div class="@if($label) mt-1 @endif relative @if(!$noShadow) rounded-md shadow-sm @endif">
        {{ $slot }}
    </div>
    @if ($error)
        <p class="mt-1 text-red-500 text-sm">{{ $error }}</p>
    @endif

    @if ($helpText)
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __($helpText) }}</p>
    @endif
</div>
