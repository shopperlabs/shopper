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
                <x-shopper-button wire:click="confirmPassword" wire:loading.attr="disabled" type="button">
                    <x-shopper-loader wire:loading wire:target="confirmPassword" />
                    {{ $button }}
                </x-shopper-button>
            </span>

            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                <x-shopper-default-button wire:click="stopConfirmingPassword" wire:loading.attr="disabled" type="button">
                    {{ __('Nevermind') }}
                </x-shopper-default-button>
            </span>
        </x-slot>
    </x-shopper-dialog-modal>
@endonce
