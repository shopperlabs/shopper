<x-shopper::form-slider-over
    action="store"
    :title="$discount->id ? $discount->code : __('shopper::pages/discounts.actions.create')"
    :description="__('shopper::pages/discounts.description')"
>
    {{ $this->form }}
</x-shopper::form-slider-over>

{{--
    <div>
    <div class="lg:col-span-4 space-y-5">
    <x-shopper::card class="divide-y divide-gray-200 dark:divide-gray-700">
    <div class="p-4">
    <div>
    <div class="mt-4 space-y-3">
    <div class="flex items-center">
    <x-shopper::forms.radio wire:model.lazy="minRequired" id="none" value="none" name="min" />
    <label for="none" class="ml-3 cursor-pointer">
    <span class="block text-sm leading-5 text-gray-700 dark:text-gray-300">
    {{ __('shopper::pages/discounts.none') }}
    </span>
    </label>
    </div>
    <div>
    <div class="flex items-center">
    <x-shopper::forms.radio wire:model.lazy="minRequired" id="price" value="price" name="min" />
    <label for="price" class="ml-3 cursor-pointer">
    <span class="block text-sm leading-5 text-gray-700 dark:text-gray-300">
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
    <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">
    {{ shopper_currency() }}
    </span>
    </div>
    </div>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
    {{ __('shopper::pages/discounts.applies_only_selected') }}
    </p>
    @endif
    </div>
    <div>
    <div class="flex items-center">
    <x-shopper::forms.radio wire:model.debounce.350ms="minRequired" id="quantity" value="quantity" name="min" />
    <label for="quantity" class="ml-3 cursor-pointer">
    <span class="block text-sm leading-5 text-gray-700 dark:text-gray-300">
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
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
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
    </x-shopper::card>
    </div>
    <div class="lg:col-span-2">
    <aside class="sticky top-14">
    <x-shopper::card class="space-y-5 divide-y divide-gray-200 dark:divide-gray-700">
    <div class="p-4 sm:p-5">
    <div class="flex items-center space-x-2">
    <h4 class="font-medium text-base text-gray-900 dark:text-white">
    {{ __('shopper::words.summary') }}
    </h4>
    @isset($discount)
    <x-shopper::badge
    :style="$discount->is_active ? 'success' : 'orange'"
    :value="$discount->is_active ? __('shopper::layout.forms.label.active') : __('shopper::layout.forms.label.inactive')"
    />
    @endisset
    </div>
    @if($this->isEmpty())
    <p class="text-gray-500 text-sm mt-4 dark:text-gray-400">
    {{ __('shopper::pages/discounts.empty_code') }}
    </p>
    @else
    @if($code !== '')
    <p class="text-base mt-4 font-bold text-gray-700 leading-6 dark:text-gray-300">
    {{ $code }}
    </p>
    @endif
    <ul class="list-disc list-inside mt-4 space-y-1 text-sm text-gray-500 dark:text-gray-400">
    @if($value !== '' && (int) $value > 0)
    <li>
    {{ $type === 'percentage' ? $value . ' %' : $this->formattedPrice($value * 100) }}
    <span>{{ __('shopper::words.of') }} {{ $apply === 'order' ? __('shopper::pages/discounts.entire_order') : $this->getProductSize() }}</span>
    </li>
    @endif
    @if($minRequiredValue !== '' && (int) $minRequiredValue > 0 && $minRequired !== 'none')
    <li>
    <span>{{ __('shopper::pages/discounts.min_purchase') }}</span>
    {{ $minRequired === 'quantity' ?  __('shopper::pages/discounts.count_items', ['count' => $minRequiredValue]) : $this->formattedPrice($minRequiredValue * 100) }}
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
    <div class="p-4 sm:p-5">
    <h4 class="text-gray-900 font-medium text-base leading-6 dark:text-white">
    {{ __('shopper::layout.forms.label.visibility') }}
    </h4>
    <p class="text-sm mt-4 font-normal text-gray-500 leading-5 dark:text-gray-400">
    {{ __('shopper::words.set_visibility', ['name' => 'discount']) }}
    </p>
    <div class="mt-5 px-3 py-2.5 bg-primary-500 bg-opacity-10 rounded-md text-primary-600 flex items-center justify-between">
    <div class="flex items-center">
    <span class="h-8 w-8 flex items-center justify-center rounded-md bg-primary-600 shrink-0">
    <x-heroicon-o-eye class="h-5 w-5 text-white" />
    </span>
    <span class="font-semibold ml-3 text-sm">{{ __('shopper::layout.forms.label.visible') }}</span>
    </div>
    </div>
    </div>
    </x-shopper::card>
    </aside>
    </div>
    </div>
--}}
