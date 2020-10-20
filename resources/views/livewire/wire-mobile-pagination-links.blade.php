@if ($paginator->hasPages())

    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span aria-disabled="true" type="button" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 opacity-50 cursor-not-allowed">
            {{ __('Previous') }}
        </span>
    @else
        <button type="button" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" wire:click="previousPage" rel="prev" aria-label="{{ __('Previous') }}">
            {{ __('Previous') }}
        </button>
    @endif

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <button type="button" wire:click="nextPage" rel="next" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" aria-label="{{ __('Next') }}">
            {{ __('Next') }}
        </button>
    @else
        <span type="button" aria-disabled="true" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 opacity-50 cursor-not-allowed" aria-label="{{ __('Next') }}">
            {{ __('Next') }}
        </span>
    @endif

@endif
