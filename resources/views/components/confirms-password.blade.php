@props(['title' => __('Confirm Password'), 'content' => __('For your security, please confirm your password to continue.'), 'button' => __('Confirm')])

@php
    $confirmableId = md5($attributes->wire('then'));
@endphp

<span
    {{ $attributes->wire('then') }}
    x-data
    x-ref="span"
    x-on:click="$wire.startConfirmingPassword('{{ $confirmableId }}')"
    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ $confirmableId }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);"
>
    {{ $slot }}
</span>

@once
    <x-shopper-dialog-modal wire:model="confirmingPassword" maxWidth="lg">
        <x-slot name="title">
            {{ $title }}
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-gray-500 leading-5">{{ $content }}</p>

            <div class="mt-4" x-data="{}" x-on:confirming-password.window="setTimeout(() => $refs.confirmable_password.focus(), 250)">
                <input
                    x-ref="confirmable_password"
                    wire:model.defer="confirmablePassword"
                    wire:keydown.enter="confirmPassword"
                    id="confirmable_password"
                    type="password"
                    class="form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                    placeholder="{{ __("Enter your password") }}"
                    aria-label="{{ __("Password") }}"
                />

                @error('confirmable_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </x-slot>

        <x-slot name="footer">
            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                <button wire:click="confirmPassword" wire:loading.attr="disabled" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-gray-800 text-base leading-6 font-medium text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:border-gray-600 focus:shadow-outline-gray transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                    <svg wire:loading wire:target="confirmPassword" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    {{ $button }}
                </button>
            </span>

            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                <button wire:click="stopConfirmingPassword" wire:loading.attr="disabled" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                    {{ __('Nevermind') }}
                </button>
            </span>
        </x-slot>
    </x-shopper-dialog-modal>
@endonce
