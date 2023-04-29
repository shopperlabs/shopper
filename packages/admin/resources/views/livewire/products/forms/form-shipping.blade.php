<div>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-semibold leading-6 text-secondary-900 dark:text-white">
                    {{ __('shopper::words.shipping') }}
                </h3>
                <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('shopper::pages/products.shipping.description') }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-5 bg-white dark:bg-secondary-800 sm:px-5 space-y-4">
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <x-shopper::forms.checkbox wire:model.defer="backorder" id="backorder" />
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <x-shopper::label for="backorder" :value="__('shopper::pages/products.product_can_returned')" />
                            <p class="text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::pages/products.product_can_returned_help_text') }}
                            </p>
                        </div>
                    </div>
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <x-shopper::forms.checkbox wire:model.lazy="requiresShipping" id="required_shipping" />
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <x-shopper::label for="required_shipping" :value="__('shopper::pages/products.product_shipped')" />
                            <p class="text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::pages/products.product_shipped_help_text') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($requiresShipping)
        <x-shopper::separator />

        <div class="mt-10 sm:mt-0">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-semibold leading-6 text-secondary-900 dark:text-white">
                            {{ __('shopper::pages/products.shipping.package_dimension') }}
                        </h3>
                        <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                            {{ __('shopper::pages/products.shipping.package_dimension_description') }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="bg-white dark:bg-secondary-800 shadow sm:rounded-md sm:overflow-hidden p-4 sm:p-5">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 sm:gap-y-4">
                            <x-shopper::forms.group class="sm:col-span-1" :label="__('shopper::layout.forms.label.width')">
                                <x-shopper::forms.input wire:model.defer="widthValue" id="WidthValue" type="text" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper::forms.select wire:model.defer="WidthUnit" aria-label="{{ __('shopper::layout.forms.label.width_unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="cm">{{ __('shopper::words.unity.cm') }}</option>
                                        <option value="m">{{ __('shopper::words.unity.m') }}</option>
                                    </x-shopper::forms.select>
                                </div>
                            </x-shopper::forms.group>
                            <x-shopper::forms.group class="sm:col-span-1" :label="__('shopper::layout.forms.label.height')">
                                <x-shopper::forms.input wire:model.defer="heightValue" id="heightValue" type="text" class="pl-3 pr-12" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper::forms.select wire:model.defer="heightUnit" aria-label="{{ __('shopper::layout.forms.label.height_unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="cm">{{ __('shopper::words.unity.cm') }}</option>
                                        <option value="m">{{ __('shopper::words.unity.m') }}</option>
                                    </x-shopper::forms.select>
                                </div>
                            </x-shopper::forms.group>
                            <x-shopper::forms.group class="sm:col-span-1" :label="__('shopper::layout.forms.label.weight')">
                                <x-shopper::forms.input wire:model.defer="weightValue" id="weightValue" type="text" class="block w-full pl-3 pr-12" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper::forms.select wire:model.defer="weightUnit" aria-label="{{ __('shopper::layout.forms.label.weight_unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="kg">{{ __('shopper::words.unity.kg') }}</option>
                                        <option value="g">{{ __('shopper::words.unity.g') }}</option>
                                    </x-shopper::forms.select>
                                </div>
                            </x-shopper::forms.group>
                            <x-shopper::forms.group class="sm:col-span-1" :label="__('shopper::layout.forms.label.volume')">
                                <x-shopper::forms.input wire:model.defer="volumeValue" id="VolumeValue" type="text" class="block w-full pl-3 pr-12" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper::forms.select wire:model.defer="VolumeUnit" aria-label="{{ __('shopper::layout.forms.label.volume_unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="l">{{ __('shopper::words.unity.l') }}</option>
                                        <option value="ml">{{ __('shopper::words.unity.ml') }}</option>
                                    </x-shopper::forms.select>
                                </div>
                            </x-shopper::forms.group>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-6 pt-5 border-t border-secondary-200 dark:border-secondary-700">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.update') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</div>
