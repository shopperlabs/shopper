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
    <trix-editor input="{{ $id }}" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 max-h-96 overflow-y-scroll"></trix-editor>
</div>
