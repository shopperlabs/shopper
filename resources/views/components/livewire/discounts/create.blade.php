<div
    x-data="{ modal: false, show: false, on: false }"
    x-init="
        flatpickr('.date', {minDate: 'today'});
        flatpickr('.time', {
            enableTime: true,
            noCalendar: true,
            dateFormat: 'H:i',
            time_24hr: true
        });
    "
>
    <div class="mt-4 grid gap-8 lg:grid-cols-6 lg:gap-10">
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
            <div class="bg-white divide-y divide-gray-200 shadow rounded-md">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base leading-5 font-medium text-gray-800">{{ __("Types") }}</h4>
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
                        @error('value')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base leading-5 font-medium text-gray-800">{{ __("Applies To") }}</h4>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center">
                                <input wire:model="apply" id="order" value="order" name="apply" type="radio" class="form-radio h-4 w-4 text-brand-600 transition duration-150 ease-in-out">
                                <label for="order" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-gray-500">{{ __("Entire order") }}</span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input wire:model="apply" id="product" value="products" name="apply" type="radio" class="form-radio h-4 w-4 text-brand-600 transition duration-150 ease-in-out">
                                <label for="product" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-gray-500">{{ __("Specific products") }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @if($apply === 'products')
                        <div class="mt-4">
                            <span class="inline-flex rounded-md shadow-sm">
                                <button @click="modal = true" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-brand-300 focus:shadow-outline-brand active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                                    {{ __("Select products") }}
                                </button>
                            </span>
                        </div>
                        @if(count($productsDetails) > 0)
                            <div class="mt-2 divide-y divide-gray-100">
                                @foreach($productsDetails as $key => $productDetail)
                                    <div class="flex items-center justify-between py-2">
                                        <div class="flex items-center">
                                            @if($productDetail['image'] !== null)
                                                <span class="flex-shrink-0 h-10 w-10 rounded-md overflow-hidden">
                                                    <img class="object-cover object-center w-full h-full block" src="{{ $productDetail['image'] }}" alt="" />
                                                </span>
                                            @else
                                                <span class="flex items-center justify-center h-10 w-10 bg-gray-100 text-gray-300 rounded-md">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </span>
                                            @endif
                                            <p class="ml-4 text-sm font-medium text-gray-500">
                                                {{ $productDetail['name'] }}
                                            </p>
                                        </div>
                                        <button wire:key="product_{{ $loop->index }}" wire:click="removeProduct({{ $key }}, {{ $productDetail['id'] }})" type="button" class="text-gray-500 text-sm font-medium inline-flex items-center">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
                <div class="p-4">
                    <div>
                        <h4 class="text-base leading-5 font-medium text-gray-800">{{ __("Minimum requirements") }}</h4>
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center">
                                <input wire:model="minRequired" id="none" value="none" name="min" type="radio" class="form-radio h-4 w-4 text-brand-600 transition duration-150 ease-in-out">
                                <label for="none" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 text-gray-500">{{ __("None") }}</span>
                                </label>
                            </div>
                            <div>
                                <div class="flex items-center">
                                    <input wire:model="minRequired" id="price" value="price" name="min" type="radio" class="form-radio h-4 w-4 text-brand-600 transition duration-150 ease-in-out">
                                    <label for="price" class="ml-3 cursor-pointer">
                                        <span class="block text-sm leading-5 text-gray-500">{{ __("Minimum purchase amount") }} ({{ shopper_currency() }})</span>
                                    </label>
                                </div>
                                @if($minRequired === 'price')
                                    <div class="mt-2 relative rounded-md shadow-sm w-full sm:w-64">
                                        <input wire:model="minRequiredValue" aria-label="{{ __("Min Required Value") }}" type="text" autocomplete="off" class="form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out sm:w-64 @error('minRequiredValue') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red @enderror" />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm sm:leading-5">
                                                {{ shopper_currency() }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-400">{{ __("Applies only to selected products.") }}</p>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center">
                                    <input wire:model="minRequired" id="quantity" value="quantity" name="min" type="radio" class="form-radio h-4 w-4 text-brand-600 transition duration-150 ease-in-out">
                                    <label for="quantity" class="ml-3 cursor-pointer">
                                        <span class="block text-sm leading-5 text-gray-500">{{ __("Minimum quantity of items") }}</span>
                                    </label>
                                </div>
                                @if($minRequired === 'quantity')
                                    <div class="mt-2 relative rounded-md shadow-sm w-full sm:w-64">
                                        <input wire:model="minRequiredValue" aria-label="{{ __("Min Required Value") }}" type="number" autocomplete="off" class="form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out sm:w-64 @error('minRequiredValue') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red @enderror" />
                                    </div>
                                    <p class="mt-1 text-sm text-gray-400">{{ __("Applies only to selected products.") }}</p>
                                @endif
                            </div>
                        </div>
                        @error('minRequiredValue')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base leading-5 font-medium text-gray-800">{{ __("Customer eligibility") }}</h4>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center">
                                <input wire:model="eligibility" id="everyone" value="everyone" type="radio" class="form-radio h-4 w-4 text-brand-600 transition duration-150 ease-in-out">
                                <label for="everyone" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-gray-500">{{ __("Everyone") }}</span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input wire:model="eligibility" id="customer" value="customers" type="radio" class="form-radio h-4 w-4 text-brand-600 transition duration-150 ease-in-out">
                                <label for="customer" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-gray-500">{{ __("Specific customers") }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @if($eligibility === 'customers')
                        <div class="mt-4">
                            <span class="inline-flex rounded-md shadow-sm">
                                <button @click="show = true" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-brand-300 focus:shadow-outline-brand active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                                    {{ __("Select customers") }}
                                </button>
                            </span>
                        </div>
                        @if(count($customersDetails) > 0)
                            <div class="mt-2 divide-y divide-gray-100">
                                @foreach($customersDetails as $key => $customerDetail)
                                    <div class="flex items-center justify-between py-2">
                                        <div class="flex items-center space-x-3">
                                            <span class="text-sm font-medium text-gray-800">{{ $customerDetail['name'] }}</span>
                                            <span class="text-sm font-normal text-gray-500">{{ $customerDetail['email'] }}</span>
                                        </div>
                                        <button wire:key="customer_{{ $loop->index }}" wire:click="removeCustomer({{ $key }}, {{ $customerDetail['id'] }})" type="button" class="text-gray-500 text-sm font-medium inline-flex items-center">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <div class="bg-white p-4 shadow rounded-md">
                <h4 class="text-base leading-6 font-medium text-gray-800">{{ __("Usage limits") }}</h4>
                <div class="mt-5 space-y-4">
                    <div>
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input wire:model="usage_number" id="usage_number" value="TOTAL_USAGE_LIMIT" type="checkbox" class="form-checkbox h-4 w-4 text-brand-600 transition duration-150 ease-in-out">
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <label for="usage_number" class="text-gray-500 cursor-pointer">{{ __("Limit number of times this discount can be used in total") }}</label>
                            </div>
                        </div>
                        @if($usage_number === 'TOTAL_USAGE_LIMIT')
                            <div class="mt-2">
                                <div class="relative rounded-md shadow-sm w-full sm:w-64">
                                    <input wire:model="usage_limit" aria-label="{{ __("Usage limit value") }}" type="number" autocomplete="off" class="form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out sm:w-64 @error('usage_limit') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red @enderror" />
                                </div>
                                @error('usage_limit')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input wire:model="usage_limit_per_user" id="usage_limit_per_user" value="ONCE_PER_CUSTOMER_LIMIT" type="checkbox" class="form-checkbox h-4 w-4 text-brand-600 transition duration-150 ease-in-out">
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <label for="usage_limit_per_user" class="text-gray-500 cursor-pointer">{{ __("Limit to one use per customer") }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 shadow rounded-md">
                <h4 class="text-base leading-6 font-medium text-gray-800">{{ __("Active dates") }}</h4>
                <div class="space-y-4 mt-4">
                    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                        <div>
                            <label for="dateStart" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Start date") }}</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input wire:model="dateStart" id="dateStart" class="form-input date block w-full pl-10 sm:text-sm sm:leading-5">
                            </div>
                        </div>
                        <div>
                            <label for="timeStart" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Start time") }}</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <input wire:model="timeStart" id="timeStart" class="form-input time block w-full pl-10 sm:text-sm sm:leading-5">
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input wire:model="set_end_date" id="set_end_date" value="active" type="checkbox" class="form-checkbox h-4 w-4 text-brand-600 transition duration-150 ease-in-out">
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <label for="set_end_date" class="text-gray-500 cursor-pointer">{{ __("Set end date") }}</label>
                        </div>
                    </div>
                    @if($set_end_date === 'active')
                        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                            <div>
                                <label for="dateEnd" class="block text-sm font-medium leading-5 text-gray-700">{{ __("End date") }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <input wire:model="dateEnd" id="dateEnd" class="form-input date block w-full pl-10 sm:text-sm sm:leading-5" readonly>
                                </div>
                            </div>
                            <div>
                                <label for="timeEnd" class="block text-sm font-medium leading-5 text-gray-700">{{ __("End time") }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <input wire:model="timeEnd" id="timeEnd" class="form-input time block w-full pl-10 sm:text-sm sm:leading-5">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="lg:col-span-2">
            <aside class="sticky top-6 space-y-4">
                <div class="bg-white shadow-md rounded-md px-4 py-5 sm:px-6">
                    <h4 class="text-gray-800 font-medium text-base">{{ __('Summary') }}</h4>
                    @if($this->isEmpty())
                        <p class="text-gray-500 text-sm mt-5">{{ __('No information entered yet.') }}</p>
                    @else
                        @if($code !== '') <p class="text-base mt-5 font-bold text-gray-800 leading-6">{{ $code }}</p> @endif
                        <ul class="list-disc list-inside mt-4 space-y-1 text-sm">
                            @if($value !== ''&& (int) $value > 0)
                                <li>
                                    {{ $type === 'percentage' ? $value . ' %' : shopper_money_format($value) }}
                                    <span>{{ __("off") }} {{ $apply === 'order' ? __("entire order") : $this->getProductSize() }}</span>
                                </li>
                            @endif
                            @if($minRequiredValue !== '' && (int) $minRequiredValue > 0 && $minRequired !== 'none')
                                <li>
                                    <span>{{ __("Minimum purchase of") }}</span>
                                    {{ $minRequired === 'quantity' ?  __(":count items", ['count' => $minRequiredValue]) : shopper_money_format($minRequiredValue) }}
                                </li>
                            @endif
                            @if($this->getCustomSize() !== null)
                                <li>
                                    <span>{{ $this->getCustomSize() }}</span>
                                </li>
                            @endif
                            @if($this->getUsageLimitMessage() !== null)
                                <li>
                                    <span>{{ $this->getUsageLimitMessage() }}</span>
                                </li>
                            @endif
                            @if($this->getDateWord() !== null)
                                <li>
                                    <span>{{ $this->getDateWord() }}</span>
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>
                <div class="bg-white shadow-md rounded-md px-4 py-5 sm:px-6">
                    <h4 class="text-gray-800 font-medium text-base leading-6">{{ __('Visibility') }}</h4>
                    <p class="text-sm mt-5 font-normal text-gray-500 leading-5">{{ __("Setup discount visibility for the customers.") }}</p>
                    <div class="mt-3 px-3 py-2.5 bg-blue-50 rounded-md text-blue-600 flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="h-8 w-8 flex items-center justify-center rounded-md bg-blue-600 flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </span>
                            <span class="font-semibold ml-3 text-sm">{{ __("Visible") }}</span>
                        </div>
                        <div>
                            <span wire:model="is_active" role="checkbox" tabindex="0" x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-bind:class="{ 'bg-gray-100': !on, 'bg-blue-600': on }" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-blue bg-gray-200">
                                <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200 translate-x-0"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-8 border-t pt-5 border-gray-200">
                    <div class="flex justify-end">
                        <button wire:click="store" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-600 focus:outline-none focus:border-brand-700 focus:shadow-outline-brand active:bg-brand-700 transition ease-in-out duration-150">
                            <span wire:loading wire:target="store" class="pr-2">
                                <span class="btn-spinner"></span>
                            </span>
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    @if($apply === 'products')
        <div
            x-cloak
            x-show="modal"
            class="fixed bottom-0 z-100 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center"
            x-on:products-added.window="modal = false"
        >
            <div
                x-cloak
                x-show="modal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity"
            >
                <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
            </div>
            <div
                x-cloak
                x-show="modal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="bg-white rounded-md overflow-hidden shadow-xl transform transition-all sm:max-w-2xl sm:w-full"
            >
                <div class="bg-white">
                    <div class="sm:flex sm:items-start px-4 sm:px-6 py-4">
                        <div class="text-center sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-700">{{ __("Add Products") }}</h3>
                        </div>
                    </div>
                    <div class="py-2 px-4 sm:px-6 border-t border-gray-100">
                        <label for="search" class="sr-only">{{ __("Search") }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input id="search" wire:model.debounce.300ms="searchProduct" autocomplete="off" class="form-input block w-full pl-10 pr-6 sm:text-sm sm:leading-5" placeholder="{{ __("Search products") }}" />
                            <div wire:loading wire:target="searchProduct" class="spinner right-0 top-0 mt-5 mr-6"></div>
                        </div>
                    </div>
                    <div class="my-2 divide-y divide-gray-200 h-80 overflow-auto">
                        @foreach($products as $product)
                            <label for="product_{{ $product->id }}" class="flex items-center py-3 cursor-pointer hover:bg-gray-50 px-4 sm:px-6 focus:bg-gray-50">
                                <span class="mr-4">
                                    <input id="product_{{ $product->id }}" aria-label="{{ __("Product") }}" wire:model="selectedProducts" value="{{ $product->id }}" type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 transition duration-150 ease-in-out" />
                                </span>
                                <span class="flex flex-1 items-center justify-between">
                                    <span class="block font-medium text-sm text-gray-700">{{ $product->name }}</span>
                                    <span class="flex items-center space-x-2">
                                        <span class="text-sm leading-5 text-gray-500">{{ $product->stock }} {{ __("available") }}</span>
                                        <span class="text-sm leading-5 text-gray-500">{{ shopper_money_format($product->price) }}</span>
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button
                            wire:click="addProducts"
                            type="button"
                            class="inline-flex items-center justify-center w-full rounded-md border border-transparent px-4 py-2 bg-brand-400 text-base leading-6 font-medium text-white shadow-sm hover:bg-brand-500 focus:outline-none focus:border-brand-400 focus:shadow-outline-brand transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                        >
                            <span wire:loading wire:target="addProducts" class="mr-2 hidden">
                                <span class="btn-spinner"></span>
                            </span>
                            {{ __('Add Select Product') }}
                        </button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                        <button @click="modal = false;" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-brand-300 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            {{ __('Cancel') }}
                        </button>
                    </span>
                </div>
            </div>
        </div>
    @endif

    @if($eligibility === 'customers')
        <div
            x-cloak
            x-show="show"
            class="fixed bottom-0 z-100 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center"
            x-on:customers-added.window="show = false"
        >
            <div
                x-cloak
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity"
            >
                <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
            </div>
            <div
                x-cloak
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="bg-white rounded-md overflow-hidden shadow-xl transform transition-all sm:max-w-2xl sm:w-full"
            >
                <div class="bg-white">
                    <div class="sm:flex sm:items-start px-4 sm:px-6 py-4">
                        <div class="text-center sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-700">{{ __("Add Customers") }}</h3>
                        </div>
                    </div>
                    <div class="py-2 px-4 sm:px-6 border-t border-gray-100">
                        <label for="search" class="sr-only">{{ __("Search") }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input id="search" wire:model.debounce.300ms="searchCustomer" autocomplete="off" class="form-input block w-full pl-10 pr-6 sm:text-sm sm:leading-5" placeholder="{{ __("Search customer") }}" />
                            <div wire:loading wire:target="searchCustomer" class="spinner right-0 top-0 mt-5 mr-6"></div>
                        </div>
                    </div>
                    <div class="my-2 divide-y divide-gray-200 h-80 overflow-auto">
                        @foreach($customers as $customer)
                            <label for="customer_{{ $customer->id }}" class="flex items-center py-3 cursor-pointer hover:bg-gray-50 px-4 sm:px-6 focus:bg-gray-50">
                                <span class="mr-4">
                                    <input id="customer_{{ $customer->id }}" aria-label="{{ __("Product") }}" wire:model="selectedCustomers" value="{{ $customer->id }}" type="checkbox" class="form-checkbox h-5 w-5 text-brand-600 transition duration-150 ease-in-out" />
                                </span>
                                <span class="flex flex-1 items-center justify-between">
                                    <span class="block font-medium text-sm text-gray-700">{{ $customer->full_name }}</span>
                                    <span class="flex items-center space-x-2">
                                        <span class="text-sm leading-5 text-gray-500">{{ $customer->email }}</span>
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button
                            wire:click="addCustomers"
                            type="button"
                            class="inline-flex items-center justify-center w-full rounded-md border border-transparent px-4 py-2 bg-brand-400 text-base leading-6 font-medium text-white shadow-sm hover:bg-brand-500 focus:outline-none focus:border-brand-400 focus:shadow-outline-brand transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                        >
                            <span wire:loading wire:target="addCustomers" class="mr-2 hidden">
                                <span class="btn-spinner"></span>
                            </span>
                            {{ __('Add Select Customers') }}
                        </button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                        <button @click="show = false;" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-brand-300 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            {{ __('Cancel') }}
                        </button>
                    </span>
                </div>
            </div>
        </div>
    @endif

</div>
