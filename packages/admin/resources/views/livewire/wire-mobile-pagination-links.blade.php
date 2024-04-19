@if ($paginator->hasPages())
    @if ($paginator->onFirstPage())
        <span
            aria-disabled="true"
            type="button"
            class="inline-flex cursor-not-allowed items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 opacity-50 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:border-gray-700 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 dark:focus:ring-offset-gray-900"
        >
            {{ __('pagination.previous') }}
        </span>
    @else
        <x-shopper::buttons.default
            type="button"
            class="relative"
            wire:click="previousPage"
            rel="prev"
            aria-label="{{ __('pagination.previous') }}"
        >
            {{ __('pagination.previous') }}
        </x-shopper::buttons.default>
    @endif

    @if ($paginator->hasMorePages())
        <x-shopper::buttons.default
            type="button"
            wire:click="nextPage"
            rel="next"
            class="relative ml-3"
            aria-label="{{ __('Next') }}"
        >
            {{ __('pagination.next') }}
        </x-shopper::buttons.default>
    @else
        <span
            type="button"
            aria-disabled="true"
            class="ml-3 inline-flex cursor-not-allowed items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 opacity-50 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:border-gray-700 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 dark:focus:ring-offset-gray-900"
            aria-label="{{ __('pagination.next') }}"
        >
            {{ __('pagination.next') }}
        </span>
    @endif
@endif
