@props([
    'initialValue' => '',
    'id' => 'x'
])

<div
    class="rounded-md shadow-sm"
    wire:ignore
    {{ $attributes }}
    x-data
    @trix-blur="$dispatch('change', $event.target.value)"
>
    <input id="{{ $id }}" value="{{ $initialValue }}" type="hidden" class="sr-only">
    <trix-editor input="{{ $id }}" class="block w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm max-h-96 overflow-y-scroll"></trix-editor>
</div>
