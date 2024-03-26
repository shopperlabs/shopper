<section
    x-data="{ open: @entangle('open') }"
    @keydown.window.escape="open = false"
    x-show="open"
    x-cloak
    class="relative z-50"
    aria-labelledby="slide-over-{{ $uniqueId }}-title"
    x-ref="dialog"
    aria-modal="true"
>

    <div
        x-show="open"
        x-cloak
        x-transition:enter="ease-in-out duration-500"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in-out duration-500"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-700 backdrop-blur-sm bg-opacity-75 transition-opacity"
    ></div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div
                    x-show="open"
                    x-cloak
                    x-transition:enter="transform transition ease-in-out duration-500"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-500"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-xl"
                    @click.away="open = false"
                >
                    <div class="flex h-full flex-col overflow-y-scroll scrolling bg-white py-6 shadow-xl dark:bg-gray-900">
                        <header class="px-4 sm:px-6">
                            <div class="flex items-start justify-between">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-white" id="slide-over{{ $uniqueId }}-title">
                                    {{ $title }}
                                </h2>
                                <div class="ml-3 flex h-7 items-center">
                                    <button
                                        type="button"
                                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 dark:bg-gray-900 dark:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        @click="open = false"
                                    >
                                        <span class="sr-only">Close panel</span>
                                        <x-untitledui-x class="h-6 w-6" stroke-width="1.5" aria-hidden="true" />
                                    </button>
                                </div>
                            </div>
                        </header>
                        <div class="relative mt-6 flex-1">
                            @if ($component)
                                @livewire($component)
                            @else
                                <div class="absolute inset-0 px-4 sm:px-6">
                                    <div class="h-full border-2 border-dashed border-gray-200 dark:border-gray-700" aria-hidden="true"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
