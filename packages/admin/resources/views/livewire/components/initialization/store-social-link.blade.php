<div class="flex flex-col justify-between space-y-10 lg:space-y-20">
    @include('shopper::livewire.components.initialization.steps')

    <form wire:submit="save" class="flex-1">
        <div class="space-y-10">
            <div>
                <div class="flex items-center space-x-4">
                    <x-untitledui-share-07
                        class="size-6 text-gray-400 dark:text-gray-500"
                        aria-hidden="true"
                        stroke-width="1"
                    />
                    <span class="text-sm font-medium text-primary-600 dark:text-primary-500">
                        {{ __('shopper::pages/onboarding.step_3') }}
                    </span>
                </div>
                <h2 class="font-heading text-2xl font-medium text-gray-900 dark:text-white">
                    {{ __('shopper::pages/onboarding.social_description') }}
                    <span class="font-normal text-gray-500 dark:text-gray-400">
                        ({{ __('shopper::forms.label.optional') }})
                    </span>
                </h2>
                <p class="text-sm leading-6 text-gray-500 dark:text-gray-300 lg:max-w-2xl">
                    {{ __('shopper::pages/onboarding.step_3_description') }}
                </p>
            </div>
            <div class="mt-10">
                {{ $this->form }}
            </div>
        </div>
        <div class="mt-8 border-t border-dashed border-gray-200 pt-10 dark:border-white/10">
            <div class="flex items-center justify-between space-x-4">
                <x-shopper::buttons.default type="button" wire:click="previousStep">
                    {{ __('shopper::forms.actions.back') }}
                </x-shopper::buttons.default>
                <x-shopper::buttons.primary type="submit" wire:loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="save" class="text-white" aria-hidden="true" />
                    {{ __('shopper::pages/onboarding.action') }}
                </x-shopper::buttons.primary>
            </div>
        </div>
    </form>
</div>
