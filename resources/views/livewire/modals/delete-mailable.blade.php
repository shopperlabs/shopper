<x-shopper-modal footerClasses="px-4 pb-5 sm:px-6 sm:flex sm:flex-row-reverse">
    <x-slot name="content">
        <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" x-description="Heroicon name: exclamation" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                    {{ __('Delete :class Mailable', ['class' => $item]) }}
                </h3>
                <div class="mt-2">
                    <p class="text-sm leading-5 text-gray-500">
                        {{ __('Are you sure you want to delete this Mailable class? If this class is used in your project this action will create a bug in your site.') }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
            <x-shopper-danger-button wire:click="delete" type="button">
                <x-shopper-loader wire:loading wire:target="delete" class="text-white" />
                {{ __("Confirm") }}
            </x-shopper-danger-button>
        </span>
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper-default-button wire:click="$emit('closeModal')" type="button">
                {{ __("Cancel") }}
            </x-shopper-default-button>
        </span>
    </x-slot>
</x-shopper-modal>
