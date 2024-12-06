<x-shopper::modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-gray-100 dark:border-white/10"
    contentClasses="relative h-full max-h-96 overflow-y-scroll scrolling lg:max-h-[580px]"
    footerClasses="px-4 py-3 border-t border-gray-200 dark:border-white/10 sm:px-6 sm:flex sm:flex-row-reverse"
    form-action="save"
>
    <x-slot name="title">
        @if ($this->paymentId)
            {{ __('shopper::pages/settings/payments.update_title') }}
        @else
            {{ __('shopper::pages/settings/payments.add_payment') }}
        @endif
    </x-slot>

    <x-slot name="content">
        <div class="p-4 sm:p-6">
            {{ $this->form }}
        </div>
    </x-slot>

    <x-slot name="buttons">
        <x-shopper::buttons.primary wire:click="save" type="submit" class="sm:ml-3 sm:w-auto">
            <x-shopper::loader wire:loading wire:target="save" class="text-white" />
            {{ __('shopper::forms.actions.save') }}
        </x-shopper::buttons.primary>
        <x-shopper::buttons.default wire:click="$dispatch('closeModal')" type="button" class="mt-3 sm:mt-0 sm:w-auto">
            {{ __('shopper::forms.actions.cancel') }}
        </x-shopper::buttons.default>
    </x-slot>
</x-shopper::modal>
