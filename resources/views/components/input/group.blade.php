@props([
    'label',
    'for',
    'noShadow' => false,
    'isRequired' => false,
    'error' => false,
    'helpText' => false,
])

<div {{ $attributes }}>
    <label for="{{ $for }}" class="block text-sm font-medium leading-5 text-gray-700">
        {{ __($label) }} @if($isRequired) <span class="text-red-500">*</span> @endif
    </label>

    <div class="mt-1 relative @if(!$noShadow) rounded-md shadow-sm @endif">
        {{ $slot }}
    </div>
    @if ($error)
        <p class="mt-1 text-red-500 text-sm">{{ $error }}</p>
    @endif

    @if ($helpText)
        <p class="mt-2 text-sm text-gray-500">{{ $helpText }}</p>
    @endif
</div>
