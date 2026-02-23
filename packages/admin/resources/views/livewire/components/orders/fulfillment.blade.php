<div>
    <x-shopper::card>
        @if ($shippingAddress)
            <x-slot:title>
                <div>
                    <p class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                        <span>{{ __('shopper::pages/orders.expedition_to') }}</span>
                        <span class="font-semibold text-gray-900 dark:text-white">
                            {{ $shippingAddress->full_name }}
                        </span>
                        @if ($country)
                            <img
                                src="{{ $country->svg_flag }}"
                                class="size-4 rounded-full object-cover object-center"
                                alt="{{ $country->name }}"
                            />
                            <span class="text-gray-500 dark:text-gray-400">
                            {{ $country->cca2 }}, {{ $country->name }}
                        </span>
                        @elseif ($shippingAddress->country_name)
                            <span class="text-gray-500 dark:text-gray-400">
                                {{ $shippingAddress->country_name }}
                            </span>
                        @endif
                    </p>
                </div>
            </x-slot:title>
        @endif

            <div class="grid grid-cols-4 gap-3">
                @foreach ($steps as $index => $step)
                    @php
                        $stepNumber = $index + 1;
                        $isLast = $stepNumber === count($steps);
                        $isCompleted = $currentStep > $stepNumber || ($isLast && $currentStep === $stepNumber);
                        $isCurrent = $currentStep === $stepNumber && ! $isLast;
                    @endphp

                    <div>
                        <div @class([
                            'flex items-center gap-1.5 text-sm',
                            'font-semibold text-gray-900 dark:text-white' => $isCurrent,
                            'font-medium text-success-600 dark:text-success-400' => $isCompleted,
                            'font-medium text-gray-400 dark:text-gray-500' => ! $isCompleted && ! $isCurrent,
                        ])>
                            @if ($isCompleted)
                                <x-heroicon-s-check-circle class="size-4 text-success-500" />
                            @elseif ($isCurrent)
                                <x-filament::icon :icon="\Shopper\Core\Enum\OrderStatus::Processing->getIcon()" class="size-4 animate-spin" />
                            @else
                                <x-filament::icon :icon="$step['icon']" class="size-4" />
                            @endif
                            <span>{{ $step['label'] }}</span>
                        </div>
                        <div @class([
                            'mt-4 h-1 w-full rounded-full',
                            'bg-success-500' => $isCompleted,
                            'bg-gray-900 dark:bg-white' => $isCurrent,
                            'bg-gray-200 dark:bg-white/10' => ! $isCompleted && ! $isCurrent,
                        ])></div>
                    </div>
                @endforeach
            </div>
    </x-shopper::card>

    @if ($currentStep > 0 && $currentStep < 4 && $this->hasUnfulfilledItems())
        <div class="flex items-center justify-end mt-5">
            <x-filament::button
                wire:click="openShippingLabel"
            >
                {{ __('shopper::pages/orders.create_shipping_label') }}
            </x-filament::button>
        </div>
    @else
        @if ($currentStep >= 3)
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                {{ __('shopper::pages/orders.all_items_fulfilled') }}
            </p>
        @endif
    @endif
</div>
