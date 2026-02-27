<div>
    @php
        $customer = $order->customer;
    @endphp

    <x-shopper::container class="pt-5">
        <x-shopper::breadcrumb :back="route('shopper.orders.index')">
            <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" />
            <x-shopper::breadcrumb.link
                :link="route('shopper.orders.index')"
                :title="__('shopper::pages/orders.menu')"
            />
        </x-shopper::breadcrumb>
    </x-shopper::container>

    <div class="sticky top-12 z-10 bg-white pt-6 backdrop-blur-lg dark:bg-gray-900">
        <x-shopper::container class="border-b space-y-2 pb-4 border-gray-200 dark:border-white/10">
            <div class="space-y-3 lg:flex lg:items-center justify-between lg:space-y-0">
                <div class="sm:flex min-w-0 sm:items-center gap-3">
                    <h3
                        class="font-heading text-2xl font-bold text-gray-900 uppercase sm:truncate sm:text-3xl dark:text-white"
                    >
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
                                    'focus:shadow-outline-primary focus:border-primary-300 relative inline-flex items-center rounded-l-lg border border-gray-300 px-2 py-2 text-sm font-medium text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:outline-none dark:border-white/10 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-500',
                                    'bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50' => ! $prevOrder,
                                    'bg-white' => $prevOrder,
                                ])
                                aria-label="{{ __('Previous order') }}"
                                @if(! $prevOrder) disabled @endif
                        >
                            <x-untitledui-chevron-left class="size-5" stroke-width="1.5" aria-hidden="true" />
                        </button>
                        <button
                            @if($nextOrder) wire:click="goToOrder({{ $nextOrder->id }})" @endif
                            type="button"
                                @class([
                                    'focus:shadow-outline-primary focus:border-primary-300 relative -ml-px inline-flex items-center rounded-r-lg border border-gray-300 px-2 py-2 text-sm font-medium text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:outline-none dark:border-white/10 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-500',
                                    'bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50' => ! $nextOrder,
                                    'bg-white' => $nextOrder,
                                ])
                                aria-label="{{ __('Next order') }}"
                                @if(! $nextOrder) disabled @endif
                            >
                            <x-untitledui-chevron-right class="size-5" stroke-width="1.5" aria-hidden="true" />
                        </button>
                    </span>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <time datetime="{{ $order->created_at->format('Y-m-d') }}">
                    {{ __('shopper::pages/orders.order_date', ['date' => '']) }}
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $order->created_at->translatedFormat('M j, Y') }}</span>
                </time>
                @if ($customer)
                    <span class="max-sm:hidden text-gray-300 dark:text-gray-600">&middot;</span>
                    <span>
                        {{ __('shopper::pages/orders.order_from', ['name' => '']) }}
                        <x-shopper::link
                            :href="route('shopper.customers.show', $customer)"
                            class="font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                        >
                            {{ $customer->full_name }}
                        </x-shopper::link>
                    </span>
                @endif
                @if ($order->channel)
                    <span class="max-sm:hidden text-gray-300 dark:text-gray-600">&middot;</span>
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
        {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::DETAIL_MAIN_BEFORE) }}

        <div class="grid sm:grid-cols-6">
            <div class="sm:col-span-4 divide-y divide-gray-200 pt-2 sm:pr-4 lg:pr-6 dark:divide-white/10">
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

            <div class="border-t border-gray-200 py-2 sm:col-span-2 sm:border-t-0 sm:border-l sm:pl-6 dark:border-white/10">
                {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::DETAIL_SIDEBAR_BEFORE) }}

                <livewire:shopper-order-customer :$order />

                {{ shopper()->getRenderHook(\Shopper\View\OrderRenderHook::DETAIL_SIDEBAR_AFTER) }}
            </div>
        </div>
    </x-shopper::container>

    <x-filament-actions::modals />
</div>
