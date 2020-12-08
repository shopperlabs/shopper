<div>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("Shipping") }}</h3>
                <p class="mt-4 text-sm leading-5 text-gray-600">
                    {{ __("Product information about return product or define if product can be shipping to the customer.") }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-5 bg-white sm:p-5 space-y-4">
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <input wire:model="backorder" id="backorder" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out" />
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <label for="backorder" class="font-medium text-gray-700 cursor-pointer">{{ __("This product can be returned") }}</label>
                            <p class="text-gray-500">{{ __("Users have the option of returning this product if there is a problem or dissatisfaction.") }}</p>
                        </div>
                    </div>
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <input wire:model="requiresShipping" id="required_shipping" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out" />
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <label for="required_shipping" class="font-medium text-gray-700 cursor-pointer">{{ __("This product will be shipped") }}</label>
                            <p class="text-gray-500">{{ __("Reassure to fill in the information concerning the shipment of the product.") }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($requiresShipping)
        <div class="hidden sm:block">
            <div class="py-5">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>

        <div class="mt-10 sm:mt-0">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("Package dimension") }}</h3>
                        <p class="mt-4 text-sm leading-5 text-gray-600">
                            {{ __("Charge additional shipping costs based on packet dimensions covered here.") }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="bg-white shadow sm:rounded-md sm:overflow-hidden p-4 sm:p-5">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 sm:gap-y-4">
                            <div class="sm:col-span-1">
                                <label for="weightValue" class="block text-sm leading-5 font-medium text-gray-700">{{ __("Weight") }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input wire:model="weightValue" id="weightValue" class="form-input block w-full pl-3 pr-12 sm:text-sm sm:leading-5" placeholder="0.00" />
                                    <div class="absolute inset-y-0 right-0 flex items-center">
                                        <select wire:model="weightUnit" aria-label="{{ __("weight Unit") }}" class="form-select h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm sm:leading-5">
                                            <option value="kg">kg</option>
                                            <option value="g">g</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="sm:col-span-1">
                                <label for="heightValue" class="block text-sm leading-5 font-medium text-gray-700">{{ __("Height") }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input wire:model="heightValue" id="heightValue" class="form-input block w-full pl-3 pr-12 sm:text-sm sm:leading-5" placeholder="0.00" />
                                    <div class="absolute inset-y-0 right-0 flex items-center">
                                        <select wire:model="heightUnit" aria-label="{{ __("height Unit") }}" class="form-select h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm sm:leading-5">
                                            <option value="cm">cm</option>
                                            <option value="m">m</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="sm:col-span-1">
                                <label for="WidthValue" class="block text-sm leading-5 font-medium text-gray-700">{{ __("Width") }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input wire:model="widthValue" id="WidthValue" class="form-input block w-full pl-3 pr-12 sm:text-sm sm:leading-5" placeholder="0.00" />
                                    <div class="absolute inset-y-0 right-0 flex items-center">
                                        <select wire:model="WidthUnit" aria-label="{{ __("Width Unit") }}" class="form-select h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm sm:leading-5">
                                            <option value="cm">cm</option>
                                            <option value="m">m</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="sm:col-span-1">
                                <label for="VolumeValue" class="block text-sm leading-5 font-medium text-gray-700">{{ __("Volume") }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input wire:model="volumeValue" id="VolumeValue" class="form-input block w-full pl-3 pr-12 sm:text-sm sm:leading-5" placeholder="0.00" />
                                    <div class="absolute inset-y-0 right-0 flex items-center">
                                        <select wire:model="VolumeUnit" aria-label="{{ __("Volume Unit") }}" class="form-select h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm sm:leading-5">
                                            <option value="l">l</option>
                                            <option value="ml">ml</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-6 border-t border-gray-200 pt-5">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                {{ __("Update") }}
            </x-shopper-button>
        </div>
    </div>
</div>
