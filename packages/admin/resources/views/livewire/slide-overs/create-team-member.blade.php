<form wire:submit="store">
    <div class="px-4 sm:px-6">
        {{ $this->form }}

        <div class="mt-6 rounded-lg bg-yellow-50 p-4">
            <div class="flex">
                <div class="shrink-0">
                    <x-untitledui-alert-triangle class="h-6 w-6 text-yellow-400" aria-hidden="true" />
                </div>
                <div class="ml-3">
                    <h3 class="text-sm leading-5 font-medium text-yellow-800">
                        {{ __('shopper::words.attention_needed') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-yellow-700">
                        {{ __('shopper::words.attention_description') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
        <div class="flex justify-end px-4 sm:px-6">
            <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.save') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</form>
