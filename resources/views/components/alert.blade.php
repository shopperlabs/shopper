@if(session()->has('success'))
    <div class="fixed inset-0 z-50 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end">
        <div
            x-data="{ show: @if(session()->has('success')) true @else false @endif }"
            x-init="setTimeout(() => { show = true }, 500); setTimeout(() => { show: false }, 5500)"
            x-show="show"
            x-description="Notification panel, show/hide based on alert state."
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="max-w-sm w-full bg-white dark:bg-secondary-800 shadow-lg rounded-lg pointer-events-auto"
        >
            <div class="relative rounded-lg shadow-xs overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="inline-flex items-center text-white text-2xl rounded-full flex-shrink-0">
                            <span>👍</span>
                        </div>
                        <div class="ml-6 w-0 flex-1">
                            <p class="text-base leading-5 font-medium capitalize text-green-600">
                                {{ __("Success") }}
                            </p>
                            <p class="mt-1 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                {{ session()->get('success') }}
                            </p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="show = false;" class="inline-flex text-secondary-500 dark:text-secondary-400">
                                <x-heroicon-s-x class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
