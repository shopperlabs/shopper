<div class="flex flex-col justify-between space-y-10 lg:space-y-20">
    @include('shopper::livewire.components.initialization.steps')

    <form wire:submit="save" class="flex-1">
        <div class="space-y-10">
            <div class="space-y-3">
                <div class="flex items-center space-x-4">
                    <x-untitledui-heading-02
                        class="size-6 text-gray-400 dark:text-gray-500"
                        aria-hidden="true"
                        stroke-width="1"
                    />
                    <span class="text-xs font-medium text-primary-600 dark:text-primary-500">
                        {{ __('shopper::pages/onboarding.step_1') }}
                    </span>
                </div>
                <h2 class="font-heading text-2xl font-medium text-gray-900 dark:text-white">
                    {{ __('shopper::pages/onboarding.tell_about') }}
                </h2>
                <p class="text-sm leading-6 text-gray-500 dark:text-gray-300 lg:max-w-2xl">
                    {{ __('shopper::pages/onboarding.step_1_description') }}
                </p>
            </div>
            <div>
                {{ $this->form }}
            </div>
        </div>
        <div class="mt-8 border-t border-dashed border-gray-200 pt-10 dark:border-white/10">
            <div class="flex justify-end">
                <x-shopper::buttons.primary type="submit" wire:loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="save" class="text-white" aria-hidden="true" />
                    {{ __('shopper::forms.actions.next') }}
                </x-shopper::buttons.primary>
            </div>
        </div>
    </form>
</div>
