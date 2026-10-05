<div>
    @php
        $customer = $order->customer;
    @endphp

    @if (filled($this->defaultAction))
        <div
            wire:init="mountAction(@js($this->defaultAction), @if (filled($this->defaultActionArguments)) @js($this->defaultActionArguments) @else {} @endif, @js($this->getDefaultActionUrlContext()))"
        ></div>
    @endif

    <div class="bg-sh-surface sticky top-0 z-10 pt-8 backdrop-blur-lg">
        <x-shopper::container class="border-sh-border space-y-2 border-b pb-5">
            <div class="justify-between space-y-3 lg:flex lg:items-center lg:space-y-0">
                <div class="min-w-0 gap-3 sm:flex sm:items-center">
                    <h3 class="font-heading text-sh-fg text-2xl font-bold uppercase sm:truncate sm:text-3xl">
                        {{ $order->number }}
                    </h3>
                    <div class="mt-3 flex items-center gap-2 sm:mt-0">
                        <x-filament::badge
                            size="md"
                            :color="$order->status->getColor()"
                            :icon="$order->status->getIcon()"
                        >
                            {{ $order->status->getLabel() }}
                        </x-filament::badge>
                        <x-filament::badge
                            size="md"
                            :color="$order->payment_status->getColor()"
                            :icon="$order->payment_status->getIcon()"
                        >
                            {{ $order->payment_status->getLabel() }}
                        </x-filament::badge>
                        <x-filament::badge
                            size="md"
                            :color="$order->shipping_status->getColor()"
                            :icon="$order->shipping_status->getIcon()"
                        >
                            {{ $order->shipping_status->getLabel() }}
                        </x-filament::badge>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if (! $order->isCompleted())
                        <div class="hidden sm:flex sm:items-center sm:gap-3">
                            @if ($order->isPaymentAuthorized())
                                {{ $this->capturePaymentAction }}
                            @endif

                            {{ $this->archiveAction }}
                        </div>

                        <x-filament-actions::group
                            :actions="[
                                $this->startProcessingAction,
                                $this->markPaidAction,
                                $this->markCompleteAction,
                                $this->cancelOrderAction,
                            ]"
                            :label="__('shopper::forms.actions.more_actions')"
                            icon="untitledui-chevron-selector-vertical"
                            color="gray"
                            size="md"
                            dropdown-width="sh-dropdown-width"
                            dropdown-placement="bottom-start"
                            :button="true"
                        />
                    @endif

                    <span class="relative z-0 inline-flex">
                        <button
                            @if($prevOrder) wire:click="goToOrder({{ $prevOrder->id }})" @endif
                            type="button"
                            @class([
                                'focus:shadow-outline-primary focus:border-primary-300 border-sh-border text-sh-fg-muted hover:text-sh-fg relative inline-flex items-center rounded-l-lg border px-2 py-2 text-sm font-medium transition duration-150 ease-in-out focus:z-10 focus:outline-none',
                                'bg-sh-muted disabled:cursor-not-allowed disabled:opacity-50' => ! $prevOrder,
                                'bg-sh-surface' => $prevOrder,
                            ])
                            aria-label="{{ __('shopper::words.previous_order') }}"
                            @if(! $prevOrder) disabled @endif
                        >
                            <x-untitledui-chevron-left class="size-5" stroke-width="1.5" aria-hidden="true" />
                        </button>
                        <button
                            @if($nextOrder) wire:click="goToOrder({{ $nextOrder->id }})" @endif
                            type="button"
                            @class([
                                'focus:shadow-outline-primary focus:border-primary-300 border-sh-border text-sh-fg-muted hover:text-sh-fg relative -ml-px inline-flex items-center rounded-r-lg border px-2 py-2 text-sm font-medium transition duration-150 ease-in-out focus:z-10 focus:outline-none',
                                'bg-sh-muted disabled:cursor-not-allowed disabled:opacity-50' => ! $nextOrder,
                                'bg-sh-surface' => $nextOrder,
                            ])
                            aria-label="{{ __('shopper::words.next_order') }}"
                            @if(! $nextOrder) disabled @endif
                        >
                            <x-untitledui-chevron-right class="size-5" stroke-width="1.5" aria-hidden="true" />
                        </button>
                    </span>
                </div>
            </div>
            <div class="text-sh-fg-muted flex flex-col gap-2 text-sm sm:flex-row sm:items-center">
                <time datetime="{{ $order->created_at->format('Y-m-d') }}">
                    {{ __('shopper::pages/orders.order_date', ['date' => '']) }}
                    <span class="text-sh-fg-secondary font-medium">
                        {{ $order->created_at->translatedFormat('M j, Y') }}
                    </span>
                </time>
                @if ($customer)
                    <span class="text-sh-fg-muted max-sm:hidden">&middot;</span>
                    <span>
                        {{ __('shopper::pages/orders.order_from', ['name' => '']) }}
                        <x-shopper::link
                            :href="route('shopper.customers.show', $customer)"
                            class="text-sh-fg-secondary hover:text-sh-fg font-medium"
                        >
                            {{ $customer->full_name }}
                        </x-shopper::link>
                    </span>
                @endif

                @if ($order->channel)
                    <span class="text-sh-fg-muted max-sm:hidden">&middot;</span>
                    <span class="inline-flex items-center gap-2">
                        {{ __('shopper::pages/orders.purchased_via') }}
                        <x-filament::badge color="gray" icon="phosphor-storefront-duotone">
                            {{ $order->channel->name }}
                        </x-filament::badge>
                    </span>
                @endif
            </div>
        </x-shopper::container>
    </div>

    {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::DETAIL_HEADER_AFTER) }}

    <x-shopper::container>
        {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::DETAIL_CONTENT_BEFORE) }}

        {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::DETAIL_MAIN_BEFORE) }}

        <div class="grid sm:grid-cols-6">
            <div class="divide-sh-border divide-y pt-2 sm:col-span-4 sm:pr-4 lg:pr-6">
                <div class="py-4">
                    <livewire:shopper-order-fulfillment :$order />
                </div>
                <div class="py-4">
                    <livewire:shopper-order-items :$order />
                </div>
                <div class="py-4">
                    <livewire:shopper-order-summary :$order />
                </div>

                {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::DETAIL_MAIN_AFTER) }}
            </div>

            <div class="border-sh-border border-t py-2 sm:col-span-2 sm:border-t-0 sm:border-l sm:pl-6">
                {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::DETAIL_SIDEBAR_BEFORE) }}

                <livewire:shopper-order-customer :$order />

                {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::DETAIL_SIDEBAR_AFTER) }}
            </div>
        </div>

        {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::DETAIL_CONTENT_AFTER) }}
    </x-shopper::container>

    <x-filament-actions::modals />
</div>
