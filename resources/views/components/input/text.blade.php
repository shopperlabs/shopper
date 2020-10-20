@props([
    'leadingAddOn' => false,
])

<div class="flex rounded-md shadow-sm">
    @if ($leadingAddOn)
        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
            {{ $leadingAddOn }}
        </span>
    @endif

    <input {{ $attributes->merge(['class' => 'form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5' . ($leadingAddOn ? ' rounded-none rounded-r-md' : '')]) }} />
</div>
