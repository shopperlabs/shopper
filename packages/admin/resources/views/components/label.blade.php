@props(['value', 'isRequired' => false])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-secondary-700 dark:text-secondary-300']) }}>
    {{ $value ?? $slot }} @if($isRequired) <span class="text-red-500">*</span> @endif
</label>
