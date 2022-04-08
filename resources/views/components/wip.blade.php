<div x-cloak x-show="modalDemo" class="fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div
            x-cloak
            x-show="modalDemo"
            x-description="Background overlay, show/hide based on modal state."
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity"
        >
            <div class="absolute z-50 inset-0 bg-secondary-500 opacity-75"></div>
        </div>
        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
        <div
            x-cloak
            x-show="modalDemo"
            x-description="Modal panel, show/hide based on modal state."
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:bg-secondary-800"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-headline"
        >
            <div class="divide-y divide-secondary-200 dark:divide-secondary-700">
                <x-shopper::wip-placeholder />
                <div class="px-4 sm:px-6 py-5">
                    <span class="flex w-full rounded-md shadow-sm">
                        <x-shopper::default-button @click="modalDemo = false" class="w-full block justify-center" type="button">
                            {{ __('Close') }}
                        </x-shopper::default-button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
