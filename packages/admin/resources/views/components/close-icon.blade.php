<div class="flex items-center gap-2 h-7">
    <template x-if="closeOnEscape">
        <x-shopper::escape />
    </template>

    <button
        type="button"
        class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none dark:bg-gray-900 dark:text-gray-500 dark:hover:text-gray-300"
        wire:click="$dispatch('closePanel')"
    >
        <span class="sr-only">{{ __('Close panel') }}</span>
        <x-untitledui-x class="size-6" stroke-width="1.5" aria-hidden="true" />
    </button>
</div>
