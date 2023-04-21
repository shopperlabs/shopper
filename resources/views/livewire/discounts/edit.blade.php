<div x-data="{ modal: false, show: false, on: @entangle('is_active') }">
    <x-shopper::breadcrumb :back="route('shopper.discounts.index')">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper::breadcrumb.link :link="route('shopper.discounts.index')" :title="__('shopper::layout.sidebar.discounts')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-3">
        <x-slot name="title">
            {{ $discount->code }}
        </x-slot>

        <x-slot name="action">
            <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.update') }}
            </x-shopper::buttons.primary>
        </x-slot>
    </x-shopper::heading>

    <div class="mt-6 grid gap-4 sm:grid-cols-6 sm:gap-6">
        <div class="lg:col-span-4 space-y-5">
            <div class="bg-white p-4 sm:p-5 shadow rounded-md dark:bg-secondary-800">
                <div class="w-full mb-3">
                    <div class="flex items-center justify-between">
                        <x-shopper::label for="code" :value="__('shopper::layout.forms.label.code')" />
                        <button wire:click="generate" type="button" class="text-primary-600 text-sm leading-5 hover:text-primary-500 dark:text-primary-500/50">
                            {{ __('shopper::messages.generate') }}
                        </button>
                    </div>
                    <div class="mt-4 relative rounded-md shadow-sm">
                        <x-shopper::forms.input wire:model.lazy="code" id="code" type="text" placeholder="{{ __('Eg.: NOELCMR900') }}" autocomplete="off" />
                        @error('code')
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-danger-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        @enderror
                    </div>
                    @error('code')
                    <p class="mt-2 text-sm text-danger-600 dark:text-danger-500">{{ $message }}</p>
                    @enderror
                </div>
                <p class="mt-2 text-sm text-secondary-500 leading-5 dark:text-secondary-400">
                    {{ __('shopper::pages/discounts.name_helptext') }}
                </p>
            </div>
            <div class="bg-white divide-y divide-secondary-200 shadow rounded-md dark:bg-secondary-800 dark:divide-secondary-700">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base leading-5 font-medium text-secondary-900 dark:text-white">
                            {{ __('shopper::layout.forms.label.type') }}
                        </h4>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center">
                                <x-shopper::forms.radio wire:model.lazy="type" id="percentage" value="percentage" name="type" />
                                <label for="percentage" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                                        {{ __('shopper::pages/discounts.percentage') }}
                                    </span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <x-shopper::forms.radio wire:model.lazy="type" id="amount" value="fixed_amount" name="type" />
                                <label for="amount" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                                        {{ __('shopper::pages/discounts.fixed_amount') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <x-shopper::label for="value" :value="__('shopper::layout.forms.label.value')" />
                        <div class="mt-1 relative rounded-md shadow-sm w-full sm:w-64">
                            <x-shopper::forms.input wire:model.lazy="value" id="value" type="text" autocomplete="off" />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-secondary-500 sm:text-sm sm:leading-5 dark:text-secondary-400">
                                    {{ $type === 'percentage' ? '%' : shopper_currency() }}
                                </span>
                            </div>
                        </div>
                        @error('value')
                        <p class="mt-2 text-sm text-danger-600 dark:text-danger-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base leading-5 font-medium text-secondary-900 dark:text-white">
                            {{ __('shopper::pages/discounts.applies_to') }}
                        </h4>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center">
                                <x-shopper::forms.radio wire:model.lazy="apply" id="order" value="order" name="apply" />
                                <label for="order" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-secondary-700 dark:text-secondary-300">
                                        {{ __('shopper::pages/discounts.entire_order') }}
                                    </span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <x-shopper::forms.radio wire:model.lazy="apply" id="product" value="products" name="apply" />
                                <label for="product" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-secondary-700 dark:text-secondary-300">
                                        {{ __('shopper::pages/discounts.specific_products') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @if($apply === 'products')
                        <div class="mt-4">
                            <span class="inline-flex rounded-md shadow-sm">
                                <x-shopper::buttons.default wire:click="$emit('openModal', 'shopper-modals.discount-products', {{ json_encode(['excludeIds' => $selectedProducts]) }})" type="button">
                                    {{ __('shopper::pages/discounts.select_products') }}
                                </x-shopper::buttons.default>
                            </span>
                        </div>
                        @if(count($selectedProducts) > 0)
                            <div class="mt-2 divide-y divide-secondary-100 dark:divide-secondary-700">
                                @foreach($products as $product)
                                    <div class="flex items-center justify-between py-2">
                                        <div class="flex items-center">
                                            @if($product->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
                                                <span class="shrink-0 h-10 w-10 rounded-md overflow-hidden">
                                                    <img class="object-cover object-center w-full h-full block" src="{{ $product->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')) }}" alt="" />
                                                </span>
                                            @else
                                                <span class="flex items-center justify-center h-10 w-10 bg-secondary-100 text-secondary-300 rounded-md dark:bg-secondary-700 dark:text-secondary-500">
                                                    <x-heroicon-o-photograph class="w-8 h-8" />
                                                </span>
                                            @endif
                                            <p class="ml-4 text-sm font-medium text-secondary-500 dark:text-secondary-400">
                                                {{ $product->name }}
                                            </p>
                                        </div>
                                        <button wire:key="product_{{ $product->id }}" wire:click="removeProduct({{ $product->id }})" type="button" class="text-secondary-500 text-sm font-medium inline-flex items-center dark:text-secondary-400">
                                            <x-heroicon-s-x class="h-5 w-5" />
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
                <div class="p-4">
                    <div>
                        <h4 class="text-base leading-5 font-medium text-secondary-900 dark:text-white">
                            {{ __('shopper::pages/discounts.min_requirement') }}
                        </h4>
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center">
                                <x-shopper::forms.radio wire:model.lazy="minRequired" id="none" value="none" name="min" />
                                <label for="none" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 text-secondary-700 dark:text-secondary-300">
                                        {{ __('shopper::pages/discounts.none') }}
                                    </span>
                                </label>
                            </div>
                            <div>
                                <div class="flex items-center">
                                    <x-shopper::forms.radio wire:model.lazy="minRequired" id="price" value="price" name="min" />
                                    <label for="price" class="ml-3 cursor-pointer">
                                        <span class="block text-sm leading-5 text-secondary-700 dark:text-secondary-300">
                                            {{ __('shopper::pages/discounts.min_amount', ['currency' => shopper_currency()]) }}
                                        </span>
                                    </label>
                                </div>
                                @if($minRequired === 'price')
                                    <div class="mt-2 relative rounded-md shadow-sm w-full sm:w-64">
                                        <x-shopper::forms.input
                                            wire:model.lazy="minRequiredValue"
                                            aria-label="{{ __('shopper::pages/discounts.min_value') }}"
                                            type="text"
                                            autocomplete="off"
                                            class="sm:w-64"
                                        />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                                {{ shopper_currency() }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-sm text-secondary-500 dark:text-secondary-400">
                                        {{ __('shopper::pages/discounts.applies_only_selected') }}
                                    </p>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center">
                                    <x-shopper::forms.radio wire:model.debounce.350ms="minRequired" id="quantity" value="quantity" name="min" />
                                    <label for="quantity" class="ml-3 cursor-pointer">
                                        <span class="block text-sm leading-5 text-secondary-700 dark:text-secondary-300">
                                            {{ __('shopper::pages/discounts.min_quantity') }}
                                        </span>
                                    </label>
                                </div>
                                @if($minRequired === 'quantity')
                                    <div class="mt-2 relative rounded-md shadow-sm w-full sm:w-64">
                                        <x-shopper::forms.input
                                            wire:model.lazy="minRequiredValue"
                                            aria-label="{{ __('shopper::pages/discounts.min_value') }}"
                                            type="number"
                                            autocomplete="off"
                                            class="sm:w-64"
                                        />
                                    </div>
                                    <p class="mt-1 text-sm text-secondary-500 dark:text-secondary-400">
                                        {{ __('shopper::pages/discounts.applies_only_selected') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        @error('minRequiredValue')
                            <p class="mt-2 text-sm text-danger-600 dark:text-danger-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-base leading-5 font-medium text-secondary-900 dark:text-white">
                            {{ __('shopper::pages/discounts.customer_eligibility') }}
                        </h4>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center">
                                <x-shopper::forms.radio wire:model.lazy="eligibility" id="everyone" value="everyone" />
                                <label for="everyone" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                                        {{ __('shopper::pages/discounts.everyone') }}
                                    </span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <x-shopper::forms.radio wire:model.lazy="eligibility" id="customer" value="customers" />
                                <label for="customer" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                                        {{ __('shopper::pages/discounts.specific_customers') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @if($eligibility === 'customers')
                        <div class="mt-4">
                            <span class="inline-flex rounded-md shadow-sm">
                                <x-shopper::buttons.default wire:click="$emit('openModal', 'shopper-modals.discount-customers', {{ json_encode(['excludeIds' => $selectedCustomers]) }})" type="button">
                                    {{ __('shopper::pages/discounts.select_customers') }}
                                </x-shopper::buttons.default>
                            </span>
                        </div>
                        @if(count($selectedCustomers) > 0)
                            <div class="mt-2 divide-y divide-secondary-100 dark:divide-secondary-700">
                                @foreach($customers as $customer)
                                    <div class="flex items-center justify-between py-2">
                                        <div class="flex items-center space-x-3">
                                            <span class="text-sm font-medium text-secondary-900 dark:text-white">{{ $customer->full_name }}</span>
                                            <span class="text-sm font-normal text-secondary-500 dark:text-secondary-400">{{ $customer->email }}</span>
                                        </div>
                                        <button
                                            wire:key="customer_{{ $customer->id }}"
                                            wire:click="removeCustomer({{ $customer->id }})"
                                            type="button"
                                            class="text-secondary-500 text-sm font-medium inline-flex items-center dark:text-secondary-400"
                                        >
                                            <x-heroicon-s-x class="h-5 w-5" />
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <div class="p-4 bg-white shadow rounded-md sm:p-5 dark:bg-secondary-800">
                <h4 class="text-base leading-6 font-medium text-secondary-900 dark:text-white">
                    {{ __('shopper::pages/discounts.usage_limits') }}
                </h4>
                <div class="mt-5 space-y-4">
                    <div>
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <x-shopper::forms.checkbox wire:model.lazy="usage_number" id="usage_number" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <x-shopper::label
                                    for="usage_number"
                                    class="text-secondary-500 cursor-pointer dark:text-secondary-400"
                                    :value="__('shopper::pages/discounts.usage_label')"
                                />
                            </div>
                        </div>
                        @if($usage_number)
                            <div class="mt-2">
                                <div class="relative rounded-md shadow-sm w-full sm:w-64">
                                    <x-shopper::forms.input
                                        wire:model.lazy="usage_limit"
                                        aria-label="{{ __('shopper::pages/discounts.usage_value') }}"
                                        type="number"
                                        min="1"
                                        step="1"
                                        autocomplete="off"
                                        class="sm:w-64"
                                    />
                                </div>
                                @error('usage_limit')
                                    <p class="mt-2 text-sm text-danger-600 dark:text-danger-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <x-shopper::forms.checkbox wire:model.lazy="usage_limit_per_user" id="usage_limit_per_user" />
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <x-shopper::label
                                for="usage_limit_per_user"
                                class="text-secondary-500 cursor-pointer dark:text-secondary-400"
                                :value="__('shopper::pages/discounts.limit_one_per_user')"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-white shadow rounded-md sm:p-5 dark:bg-secondary-800">
                <h4 class="text-base leading-6 font-medium text-secondary-900 dark:text-white">
                    {{ __('shopper::pages/discounts.active_dates') }}
                </h4>
                <div class="space-y-4 mt-4">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                        <div class="sm:col-span-1">
                            <x-datetime-picker
                                :label="__('shopper::pages/discounts.start_date')"
                                :placeholder="__('shopper::pages/discounts.choose_start_date')"
                                wire:model.lazy="dateStart"
                                parse-format="YYYY-MM-DD HH:mm"
                                time-format="24"
                                without-timezone
                            />
                        </div>
                        <div class="sm:col-span-1">
                            <x-datetime-picker
                                :label="__('shopper::pages/discounts.end_date')"
                                :placeholder="__('shopper::pages/discounts.choose_end_date')"
                                wire:model.lazy="dateEnd"
                                parse-format="YYYY-MM-DD HH:mm"
                                time-format="24"
                                without-timezone
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-2">
            <aside class="sticky top-10">
                <div class="space-y-5">
                    <div class="px-4 py-5 bg-white shadow-md rounded-md sm:px-6 dark:bg-secondary-800">
                        <div class="flex items-center space-x-2">
                            <h4 class="font-medium text-base text-secondary-900 dark:text-white">
                                {{ __('shopper::messages.summary') }}
                            </h4>
                            <x-shopper::badge
                                :style="$discount->is_active ? 'success' : 'orange'"
                                :value="$discount->is_active ? __('shopper::layout.forms.label.active') : __('shopper::layout.forms.label.inactive')"
                            />
                        </div>
                        @if($this->isEmpty())
                            <p class="text-secondary-500 text-sm mt-4 dark:text-secondary-400">
                                {{ __('shopper::pages/discounts.empty_code') }}
                            </p>
                        @else
                            @if($code !== '')
                                <p class="text-base mt-4 font-bold text-secondary-700 leading-6 dark:text-secondary-300">
                                    {{ $code }}
                                </p>
                            @endif
                            <ul class="list-disc list-inside mt-4 space-y-1 text-sm text-secondary-500 dark:text-secondary-400">
                                @if($value !== '' && (int) $value > 0)
                                    <li>
                                        {{ $type === 'percentage' ? $value . ' %' : $this->formattedPrice($value) }}
                                        <span>{{ __('shopper::messages.of') }} {{ $apply === 'order' ? __('shopper::pages/discounts.entire_order') : $this->getProductSize() }}</span>
                                    </li>
                                @endif
                                @if($minRequiredValue !== '' && (int) $minRequiredValue > 0 && $minRequired !== 'none')
                                    <li>
                                        <span>{{ __('shopper::pages/discounts.min_purchase') }}</span>
                                        {{ $minRequired === 'quantity' ?  __('shopper::pages/discounts.count_items', ['count' => $minRequiredValue]) : $this->formattedPrice($minRequiredValue) }}
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
                    <div class="p-4 bg-white shadow-md rounded-md sm:p-5 dark:bg-secondary-800">
                        <h4 class="text-secondary-900 font-medium text-base leading-6 dark:text-white">
                            {{ __('shopper::layout.forms.label.visibility') }}
                        </h4>
                        <p class="text-sm mt-4 font-normal text-secondary-500 leading-5 dark:text-secondary-400">
                            {{ __('shopper::messages.actions_label.set_visibility', ['name' => 'discount']) }}
                        </p>
                        <div class="mt-5 px-3 py-2.5 bg-primary-500 bg-opacity-10 rounded-md text-primary-600 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="h-8 w-8 flex items-center justify-center rounded-md bg-primary-600 shrink-0">
                                    <x-heroicon-o-eye class="h-5 w-5 text-white" />
                                </span>
                                <span class="font-semibold ml-3 text-sm">{{ __('shopper::layout.forms.label.visible') }}</span>
                            </div>
                            <div>
                                <span wire:model="is_active"
                                      role="checkbox"
                                      tabindex="0"
                                      x-on:click="$dispatch('input', !on); on = !on"
                                      @keydown.space.prevent="on = !on"
                                      :aria-checked="on.toString()"
                                      aria-checked="false"
                                      x-bind:class="{ 'bg-secondary-200 dark:bg-secondary-700': !on, 'bg-primary-600': on }"
                                      class="relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-primary bg-secondary-200">
                                    <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                    <span aria-hidden="true"
                                          x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }"
                                          class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200 translate-x-0"
                                    ></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div class="mt-6 pt-5 pb-10 border-t border-secondary-200 dark:border-secondary-700">
        <div class="flex items-center justify-between space-x-4">
            @can('delete_discounts')
                <x-shopper::buttons.danger wire:click="$emit('openModal', 'shopper-modals.delete-discount', {{ json_encode([$discount->id]) }})" type="button">
                    <x-heroicon-o-trash class="w-5 h-5 -ml-1 mr-2"/>
                    {{ __('shopper::layout.forms.actions.delete') }}
                </x-shopper::buttons.danger>
            @endcan
            <div class="flex justify-end">
                <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                    <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                    {{ __('shopper::layout.forms.actions.update') }}
                </x-shopper::buttons.primary>
            </div>
        </div>
    </div>

</div>
