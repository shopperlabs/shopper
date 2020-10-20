@props([
    'label',
    'for',
    'isRequired' => false,
    'error' => false,
    'helpText' => false,
])

<div {{ $attributes }}>
    <label for="{{ $for }}" class="block text-sm font-medium leading-5 text-gray-700">
        {{ $label }} @if($isRequired) <span class="text-red-500">*</span> @endif
    </label>

    <div class="mt-1 relative rounded-md shadow-sm">
        {{ $slot }}
    </div>
    @if ($error)
        <p class="mt-1 text-red-500 text-sm">{{ $error }}</p>
    @endif

    @if ($helpText)
        <p class="mt-2 text-sm text-gray-500">{{ $helpText }}</p>
    @endif
</div>
