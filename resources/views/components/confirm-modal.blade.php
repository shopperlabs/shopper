@props([
    'method' => 'destroy'
])

<div
    {{ $attributes }}
    x-data="{ confirm: @entangle($attributes->wire('model')), }"
    x-on:modal-close.window="confirm = false"
    x-on:modal-open.window="confirm = true"
    x-show="confirm"
    class="fixed z-50 inset-0 overflow-y-auto"
    style="display: none;"
>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div
            x-cloak
            x-show="confirm"
            x-description="Background overlay, show/hide based on modal state."
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity"
        >
            <div class="absolute inset-0 bg-secondary-500 opacity-75"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
        <div
            x-cloak
            x-show="confirm"
            x-description="Modal panel, show/hide based on modal state."
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 sm:p-6 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:bg-secondary-800"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-headline"
        >
            <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                <button @click="confirm = false;" type="button" class="text-secondary-400 hover:text-secondary-500 focus:outline-none focus:text-secondary-500 transition ease-in-out duration-150" aria-label="Close">
                    <svg class="h-6 w-6" x-description="Heroicon name: x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            {{ $slot }}
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <x-shopper-button wire:click="{{ $method }}" wire:loading.attr="disabled" type="button">
                        <x-shopper-loader wire:loading wire:target="{{ $method }}" />
                        {{ __('Confirm') }}
                    </x-shopper-button>
                </span>
                <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                    <x-shopper-default-button @click="confirm = false;" type="button">
                        {{ __('Cancel') }}
                    </x-shopper-default-button>
                </span>
            </div>
        </div>
    </div>
</div>
