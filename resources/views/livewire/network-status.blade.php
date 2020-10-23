<div wire:offline class="fixed inset-0 left-4 z-50 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-end sm:justify-start">
    <div class="max-w-md w-full bg-white shadow-lg rounded-lg pointer-events-auto border-t-4 border-red-600">
        <div class="relative rounded-lg shadow-xs overflow-hidden">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="inline-flex items-center bg-gradient-to-r from-red-600 to-red-800 p-2 text-white text-sm rounded-full flex-shrink-0">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="x w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="ml-6 w-0 flex-1">
                        <p class="text-base leading-5 font-medium text-red-600">
                            {{ __("Network Not Found") }}
                        </p>
                        <p class="mt-1 text-sm leading-5 text-gray-500">
                            {{ __("Connection could not be established.") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
