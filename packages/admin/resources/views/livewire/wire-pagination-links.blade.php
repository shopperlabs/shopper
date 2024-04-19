@if ($paginator->hasPages())
    <div role="navigation">
        <span class="relative z-0 inline-flex shadow-sm">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span
                    class="relative inline-flex cursor-not-allowed items-center rounded-none rounded-l-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 opacity-50 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:border-gray-700 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 dark:focus:ring-offset-gray-900"
                    aria-disabled="true"
                    aria-label="{{ __('Previous') }}"
                >
                    <x-untitledui-chevron-left class="h-5 w-5" aria-hidden="true" />
                </span>
            @else
                <x-shopper::buttons.default
                    type="button"
                    class="relative inline-flex rounded-l-lg rounded-r-none px-2"
                    wire:click="previousPage"
                    rel="prev"
                    aria-label="@lang('pagination.previous')"
                >
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </x-shopper::buttons.default>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span
                        class="relative -ml-px inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium leading-5 text-gray-700 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300"
                    >
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="relative -ml-px hidden items-center border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium leading-5 text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none dark:border-gray-600 dark:bg-gray-600 dark:text-gray-300 dark:hover:text-white lg:inline-flex"
                            >
                                {{ $page }}
                            </span>
                        @else
                            <x-shopper::buttons.default
                                type="button"
                                class="relative -ml-px rounded-l-none rounded-r-none focus:z-10"
                                wire:click="gotoPage({{ $page }})"
                            >
                                {{ $page }}
                            </x-shopper::buttons.default>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <x-shopper::buttons.default
                    type="button"
                    class="relative -ml-px rounded-l-none rounded-r-lg px-2"
                    wire:click="nextPage"
                    rel="next"
                    aria-label="{{ __('Next') }}"
                >
                    <x-untitledui-chevron-right class="h-5 w-5" aria-hidden="true" />
                </x-shopper::buttons.default>
            @else
                <x-shopper::buttons.default
                    type="button"
                    class="relative -ml-px cursor-not-allowed rounded-l-none rounded-r-lg px-2 opacity-50 focus:z-10"
                    aria-disabled="true"
                    aria-label="{{ __('Next') }}"
                >
                    <x-untitledui-chevron-right class="h-5 w-5" aria-hidden="true" />
                </x-shopper::buttons.default>
            @endif
        </span>
    </div>
@endif
