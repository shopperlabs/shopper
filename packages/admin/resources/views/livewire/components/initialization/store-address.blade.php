<div class="flex flex-col justify-between space-y-10 lg:space-y-20">
    @include('shopper::livewire.components.initialization.steps')

    <form wire:submit="save" class="flex-1">
        <div>
            <div>
                <div class="flex items-center space-x-4">
                    <x-untitledui-globe-05
                        class="h-6 w-6 text-gray-400 dark:text-gray-500"
                        aria-hidden="true"
                        stroke-width="1"
                    />
                    <span class="text-sm font-medium text-primary-600 dark:text-primary-500">
                        {{ __('shopper::pages/settings.initialization.step_2') }}
                    </span>
                </div>
                <div class="mt-3">
                    <h2 class="font-heading text-2xl font-medium text-gray-900 dark:text-white">
                        {{ __('shopper::pages/settings.initialization.address_description') }}
                    </h2>
                    <p class="mt-3 text-sm leading-6 text-gray-500 dark:text-gray-300 lg:max-w-2xl">
                        {{ __('shopper::pages/settings.initialization.step_2_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-10">
                <div class="grid grid-cols-3 gap-6">
                    <x-shopper::forms.group
                        :label="__('shopper::layout.forms.label.street_address')"
                        for="street_address"
                        class="col-span-2"
                        isRequired
                    >
                        <x-shopper::forms.input
                            wire:model="street_address"
                            id="street_address"
                            type="text"
                            placeholder="Akwa Avenue 34..."
                            required
                        />
                    </x-shopper::forms.group>
                    <x-shopper::forms.group
                        :label="__('shopper::layout.forms.label.postal_code')"
                        for="postal_code"
                        isRequired
                    >
                        <x-shopper::forms.input
                            wire:model="postal_code"
                            id="postal_code"
                            type="text"
                            placeholder="00237"
                            required
                        />
                    </x-shopper::forms.group>
                    <x-shopper::forms.group
                        :label="__('shopper::layout.forms.label.city')"
                        for="city"
                        :is-required="true"
                    >
                        <x-shopper::forms.input wire:model="city" id="city" type="text" required />
                    </x-shopper::forms.group>
                    <div class="col-span-2" x-data="internationalNumber('#phone_number')" wire:ignore>
                        <x-shopper::label for="phone_number" :value="__('shopper::layout.forms.label.phone_number')" />
                        <div class="mt-1 max-w-lg">
                            <x-shopper::forms.input
                                wire:model="phone_number"
                                id="phone_number"
                                type="tel"
                                class="w-full"
                                autocomplete="off"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8 border-t border-dashed border-gray-200 pt-10 dark:border-gray-700">
            <div class="flex items-center justify-between space-x-4">
                <x-shopper::buttons.default type="button" wire:click="previousStep">
                    {{ __('shopper::layout.forms.actions.back') }}
                </x-shopper::buttons.default>
                <x-shopper::buttons.primary type="submit" wire:loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="save" class="text-white" aria-hidden="true" />
                    {{ __('shopper::layout.forms.actions.next') }}
                </x-shopper::buttons.primary>
            </div>
        </div>
    </form>
</div>
