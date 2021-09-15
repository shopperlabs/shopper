@if ($paginator->hasPages())

    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span aria-disabled="true" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-white bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 opacity-50 cursor-not-allowed">
            {{ __('Previous') }}
        </span>
    @else
        <x-shopper-default-button type="button" class="relative" wire:click="previousPage" rel="prev" aria-label="{{ __('Previous') }}">
            {{ __('Previous') }}
        </x-shopper-default-button>
    @endif

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <x-shopper-default-button type="button" wire:click="nextPage" rel="next" class="ml-3 relative" aria-label="{{ __('Next') }}">
            {{ __('Next') }}
        </x-shopper-default-button>
    @else
        <span type="button" aria-disabled="true" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-white bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 opacity-50 cursor-not-allowed" aria-label="{{ __('Next') }}">
            {{ __('Next') }}
        </span>
    @endif

@endif
