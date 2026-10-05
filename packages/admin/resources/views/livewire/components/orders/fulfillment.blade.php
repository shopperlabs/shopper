<div>
    <x-shopper::card>
        <x-slot:title>
            @if ($shippingAddress)
                <div>
                    <p class="text-sh-fg-secondary flex items-center gap-2 text-sm">
                        <span>{{ __('shopper::pages/orders.expedition_to') }}</span>
                        <span class="text-sh-fg font-semibold">
                            {{ $shippingAddress->full_name }}
                        </span>

                        @if ($country)
                            <img
                                src="{{ $country->svg_flag }}"
                                class="size-4 rounded-full object-cover object-center"
                                alt="{{ $country->translated_name }}"
                            />
                            <span class="text-sh-fg-muted">
                                {{ $country->cca2 }}, {{ $country->translated_name }}
                            </span>
                        @elseif ($shippingAddress->country_name)
                            <span class="text-sh-fg-muted">
                                {{ $shippingAddress->country_name }}
                            </span>
                        @endif
                    </p>
                </div>
            @endif
        </x-slot>

        <div class="grid grid-cols-4 gap-3">
            @foreach ($steps as $index => $step)
                @php
                    $stepNumber = $index + 1;
                    $isLast = $stepNumber === count($steps);
                    $isCompleted = $currentStep > $stepNumber || ($isLast && $currentStep === $stepNumber);
                    $isCurrent = $currentStep === $stepNumber && ! $isLast;
                @endphp

                <div>
                    <div
                        @class([
                            'flex items-center gap-1.5 text-sm',
                            'text-sh-fg font-semibold' => $isCurrent,
                            'text-success-600 dark:text-success-400 font-medium' => $isCompleted,
                            'text-sh-fg-muted font-medium' => ! $isCompleted && ! $isCurrent,
                        ])
                    >
                        @if ($isCompleted)
                            <x-heroicon-s-check-circle class="text-success-500 size-4" />
                        @elseif ($isCurrent)
                            <x-filament::icon
                                :icon="\Shopper\Core\Enum\OrderStatus::Processing->getIcon()"
                                class="size-4 animate-spin"
                            />
                        @else
                            <x-filament::icon :icon="$step['icon']" class="size-4" />
                        @endif
                        <span>{{ $step['label'] }}</span>
                    </div>
                    <div
                        @class([
                            'mt-4 h-1 w-full rounded-full',
                            'bg-success-500' => $isCompleted,
                            'bg-sh-fg' => $isCurrent,
                            'bg-sh-muted' => ! $isCompleted && ! $isCurrent,
                        ])
                    ></div>
                </div>
            @endforeach
        </div>
    </x-shopper::card>

    @if ($currentStep > 0 && $currentStep < 4 && $this->hasUnfulfilledItems())
        <div class="mt-5 flex items-center justify-end">
            <x-filament::button wire:click="openShippingLabel">
                {{ __('shopper::pages/orders.create_shipping_label') }}
            </x-filament::button>
        </div>
    @else
        @if ($currentStep >= 3)
            <p class="text-sh-fg-muted mt-4 text-sm">
                {{ __('shopper::pages/orders.all_items_fulfilled') }}
            </p>
        @endif
    @endif
</div>
