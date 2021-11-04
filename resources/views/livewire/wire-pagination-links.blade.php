@if ($paginator->hasPages())

    <div role="navigation">
        <span class="relative z-0 inline-flex shadow-sm">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-2 py-2 rounded-none rounded-l-md inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-medium text-gray-700 dark:text-white bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-900 opacity-50 cursor-not-allowed" aria-disabled="true" aria-label="{{ __('Previous') }}">
                    <x-heroicon-s-chevron-left class="h-5 w-5" />
                </span>
            @else
                <x-shopper-default-button type="button" class="relative inline-flex px-2 rounded-r-none rounded-l-md" wire:click="previousPage" rel="prev" aria-label="@lang('pagination.previous')">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </x-shopper-default-button>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator--}}
                @if (is_string($element))
                        <span class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 dark:text-gray-300 dark:border-gray-700 dark:bg-gray-700">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="hidden md:inline-flex -ml-px relative items-center px-4 py-2 border border-gray-300 bg-gray-100 text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none dark:bg-gray-600 dark:text-gray-300 dark:hover:text-white dark:border-gray-600">{{ $page }}</span>
                        @else
                            <x-shopper-default-button type="button" class="-ml-px relative rounded-l-none rounded-r-none focus:z-10" wire:click="gotoPage({{ $page }})">{{ $page }}</x-shopper-default-button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <x-shopper-default-button type="button" class="-ml-px relative px-2 rounded-l-none rounded-r-md" wire:click="nextPage" rel="next" aria-label="{{ __('Next') }}">
                    <x-heroicon-s-chevron-right class="h-5 w-5" />
                </x-shopper-default-button>
            @else
                <x-shopper-default-button type="button" class="-ml-px relative px-2 rounded-l-none rounded-r-md focus:z-10 opacity-50 cursor-not-allowed" aria-disabled="true" aria-label="{{ __('Next') }}">
                    <x-heroicon-s-chevron-right class="h-5 w-5" />
                </x-shopper-default-button>
            @endif
        </span>
    </div>

@endif
