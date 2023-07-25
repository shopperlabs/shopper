<div
    wire:ignore
    x-data
    @trix-change="$dispatch('change', @this.set('value', $event.target.value))"
>
    <input id="{{ $trixId }}" value="{{ $value }}" type="hidden" class="sr-only">
    <trix-editor input="{{ $trixId }}" class="block w-full rounded-md shadow-sm border-secondary-300 dark:bg-secondary-700 dark:text-white dark:border-secondary-700 focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 sm:text-sm max-h-96 overflow-y-scroll"></trix-editor>
</div>
