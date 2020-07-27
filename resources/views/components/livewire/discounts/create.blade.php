<div>
    <div class="mt-4 grid gap-8 lg:grid-cols-6 lg:gap-12">
        <div class="lg:col-span-4 space-y-5">
            <div class="bg-white p-4 shadow rounded-md">
                <div class="w-full mb-3">
                    <div class="flex items-center justify-between">
                        <label for="code" class="block text-sm font-medium leading-5 text-gray-700">{{ __('Code') }}</label>
                        <button wire:click="generate" type="button" class="text-brand-600 text-sm font-medium leading-5 hover:text-brand-400 transition ease-in-out duration-150">{{ __("Generate code") }}</button>
                    </div>
                    <div class="mt-4 relative rounded-md shadow-sm">
                        <input wire:model="code" id="code" type="text" placeholder="{{ __("Eg.: NOELCMR900") }}" autocomplete="off" class="form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out @error('code') pr-10 border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red @enderror" />
                        @error('code')
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @enderror
                    </div>
                    @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <p class="text-sm text-gray-500 leading-5 mt-2">{{ __("Customers will enter this discount code at checkout.") }}</p>
            </div>
            <div class="bg-white p-4 shadow rounded-md">
                <div class="flex items-center justify-between">
                    <h4 class="text-base leading-5 text-gray-700">{{ __("Types") }}</h4>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center">
                            <input wire:model="type" id="percentage" value="percentage" name="type" type="radio" class="form-radio h-4 w-4 text-brand-600 transition duration-150 ease-in-out">
                            <label for="percentage" class="ml-3 cursor-pointer">
                                <span class="block text-sm leading-5 font-medium text-gray-500">{{ __("Percentage") }}</span>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input wire:model="type" id="amount" value="fixed_amount" name="type" type="radio" class="form-radio h-4 w-4 text-brand-600 transition duration-150 ease-in-out">
                            <label for="amount" class="ml-3 cursor-pointer">
                                <span class="block text-sm leading-5 font-medium text-gray-500">{{ __("Fixed amount") }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <label for="value" class="block text-sm font-medium leading-5 text-gray-700">{{ __('Value') }}</label>
                    <div class="mt-1 relative rounded-md shadow-sm w-full sm:w-64">
                        <input wire:model="value" id="value" type="text" autocomplete="off" class="form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out sm:w-64 @error('value') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red @enderror" />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm sm:leading-5">
                                {{ $type === 'percentage' ? '%' : shopper_currency() }}
                            </span>
                        </div>
                    </div>
                    @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        <div class="lg:col-span-2">
            <aside class="sticky top-6">
                <div class="bg-white shadow-md rounded-md mb-5 px-4 py-5 sm:px-6">
                    <h4 class="text-gray-800 font-medium text-base">{{ __('Summary') }}</h4>
                    @if($this->isEmpty())
                        <p class="text-gray-500 text-sm mt-5">{{ __('No information entered yet.') }}</p>
                    @else
                        @if($code !== '') <p class="text-base mt-5 font-bold text-gray-800 leading-6">{{ $code }}</p> @endif
                        <ul class="list-disc list-inside mt-4 space-y-1 text-sm">
                            @if($value !== '')
                                <li>
                                    {{ $type === 'percentage' ? $value . ' %' : shopper_money_format($value) }}
                                    <span>{{ __("off entire order") }}</span>
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</div>
