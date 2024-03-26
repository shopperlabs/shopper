<div
    wire:ignore
    x-data
    @trix-change="$dispatch('change', @this.set('value', $event.target.value))"
>
    <input id="{{ $trixId }}" value="{{ $value }}" type="hidden" class="sr-only">
    <trix-editor input="{{ $trixId }}" class="block w-full rounded-lg shadow-sm border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-700 focus:ring-1 focus:border-primary-500 focus:ring-primary-500 sm:text-sm max-h-96 overflow-y-scroll"></trix-editor>
</div>
