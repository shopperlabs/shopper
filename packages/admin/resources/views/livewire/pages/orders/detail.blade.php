<div>
    @php
        $shippingOption = $this->order->shippingOption;
        $billingAddress = $this->order->billingAddress;
        $shippingAddress = $this->order->shippingAddress;

        $total = $order->total() + $shippingOption?->price;
        $customer = $this->customer;
    @endphp

    <x-shopper::container class="py-5">
        <x-shopper::breadcrumb :back="route('shopper.orders.index')">
            <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" />
            <x-shopper::breadcrumb.link
                :link="route('shopper.orders.index')"
                :title="__('shopper::pages/orders.menu')"
            />
        </x-shopper::breadcrumb>

        <div class="z-30 mt-5 border-b border-gray-200 pb-5 dark:border-white/10">
            <div class="space-y-4">
                <div class="space-y-3 lg:flex lg:items-center lg:justify-between lg:space-y-0">
                    <div class="flex min-w-0 flex-1 items-center space-x-4">
                        <h3
                            class="font-heading text-2xl font-bold uppercase leading-6 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:leading-9"
                        >
                            {{ $order->number }}
                        </h3>
                        <div class="flex items-center divide-x-2 divide-gray-200 p-1 dark:divide-white/10">
                            <div class="flex items-center space-x-2 pr-4">
                                <x-filament::badge
                                    size="md"
                                    :color="$order->status->getColor()"
                                    :icon="$order->status->getIcon()"
                                >
                                    {{ $order->status->getLabel() }}
                                </x-filament::badge>
                            </div>
                            <div class="flex items-center gap-2 px-4">
                                <x-untitledui-calendar
                                    class="size-5 text-gray-400 dark:text-gray-500"
                                    stroke-width="1.5"
                                    aria-hidden="true"
                                />
                                <span class="text-sm font-medium leading-6 text-gray-500 dark:text-gray-400">
                                    <time class="capitalize" datetime="{{ $order->created_at->format('F j, Y h:m') }}">
                                        {{ $order->created_at->translatedFormat('j F Y H:i') }}
                                    </time>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        @if (! $order->isCompleted())
                            @if (! $order->isPaid())
                                <div class="hidden sm:block">
                                    <x-shopper::buttons.danger
                                        wire:click="$dispatch('openModal', { component: 'shopper-modals.archived-order', arguments: { order: {{ $order->id }} }})"
                                        type="button"
                                    >
                                        <x-untitledui-trash-03
                                            class="mr-2 size-5"
                                            stroke-width="1.5"
                                            aria-hidden="true"
                                        />
                                        {{ __('shopper::forms.actions.archive') }}
                                    </x-shopper::buttons.danger>
                                </div>
                            @endif

                            <x-filament-actions::group
                                :actions="[
                                    $this->registerAction,
                                    $this->markPaidAction,
                                    $this->markCompleteAction,
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

                        <span class="relative z-0 inline-flex shadow-sm">
                            <button
                                @if($prevOrder) wire:click="goToOrder({{ $prevOrder->id }})" @endif
                                type="button"
                                @class([
                                    'focus:shadow-outline-primary relative inline-flex items-center rounded-l-lg border border-gray-300 px-2 py-2 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-primary-300 focus:outline-none dark:border-white/10 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-500',
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
                                    'focus:shadow-outline-primary relative -ml-px inline-flex items-center rounded-r-lg border border-gray-300 px-2 py-2 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-primary-300 focus:outline-none dark:border-white/10 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-500',
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
            </div>
        </div>

        <div class="grid sm:grid-cols-6">
            <div class="divide-y divide-gray-200 py-2 dark:divide-white/10 sm:col-span-4">
                <div class="py-4 sm:pr-8">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                            {{ __('shopper::pages/products.menu') }}
                        </h3>
                        <div class="flex items-center space-x-3">
                            <span class="whitespace-nowrap text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('shopper::words.per_page') }}
                            </span>
                            <x-shopper::forms.select
                                wire:model="perPage"
                                class="!w-20"
                                aria-label="{{ __('shopper::words.per_page_items') }}"
                            >
                                <option value="3">3</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                            </x-shopper::forms.select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <ul class="bg-white divide-y divide-gray-200 ring-1 ring-gray-200 rounded-lg dark:ring-white/10 dark:bg-gray-900 dark:divide-white/10">
                            @foreach ($items as $item)
                                <li class="flex-1 p-4">
                                    <div class="flex items-start">
                                        <div class="flex min-w-0 flex-1 items-center">
                                            <div class="shrink-0">
                                                <img
                                                    class="size-10 rounded-lg object-cover"
                                                    src="{{ $item->product->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection')) }}"
                                                    alt="{{ $item->name }}"
                                                />
                                            </div>
                                            <div class="min-w-0 flex-1 px-4 lg:grid lg:grid-cols-4 lg:gap-4">
                                                <div class="lg:col-span-2">
                                                    <div
                                                        class="truncate text-sm font-medium leading-5 text-gray-900 dark:text-white"
                                                    >
                                                        {{ $item->name }}
                                                    </div>
                                                    <div
                                                        class="mt-1 flex items-center text-xs leading-4 text-gray-500 dark:text-gray-400"
                                                    >
                                                        <span class="truncate">{{ $item->product->sku ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="hidden lg:block">
                                                    <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                                                        {{ shopper_money_format($item->unit_price_amount, $order->currency_code) }}
                                                        x
                                                        {{ $item->quantity }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-sm font-medium leading-5 text-gray-700 dark:text-gray-300">
                                            {{ shopper_money_format($item->total, $order->currency_code) }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4">
                            {{ $items->links() }}
                        </div>
                    </div>
                    <div class="mt-3 flex justify-end">
                        <div class="w-full space-y-1 text-right sm:max-w-lg">
                            <div
                                class="inline-flex items-center gap-4 rounded-lg bg-gray-50 px-4 py-3 text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                            >
                                <span class="text-base leading-6 text-gray-900 dark:text-white">
                                    {{ __('shopper::words.subtotal') }}
                                </span>
                                <span class="font-semibold">
                                    {{ shopper_money_format($order->total(), $order->currency_code) }}
                                </span>
                            </div>
                            <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/orders.total_price_description') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="py-4 sm:pr-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        {{ __('shopper::words.payment_method') }}
                    </h3>
                    @if ($order->paymentMethod)
                        <div class="flex py-4">
                            @if ($order->paymentMethod->logo)
                                <img
                                    class="size-10 rounded-md object-cover"
                                    src="{{ $order->paymentMethod->logo_url }}"
                                    alt="payment icon"
                                />
                            @else
                                <span
                                    class="flex size-10 items-center justify-center rounded-md bg-gray-100 text-gray-300 dark:bg-gray-800 dark:text-white"
                                >
                                    <x-untitledui-image class="size-6" stroke-width="1.5" aria-hidden="true" />
                                </span>
                            @endif
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $order->paymentMethod->title }}
                                </p>
                                <x-shopper::link
                                    href="{{ route('shopper.settings.payments') }}"
                                    class="text-sm text-gray-500 underline hover:text-gray-400 dark:text-gray-400"
                                >
                                    {{ __('shopper::words.available_methods') }}
                                </x-shopper::link>
                            </div>
                        </div>
                    @else
                        <div class="py-4">
                            <div class="border-l-4 border-yellow-400 bg-yellow-50 p-4">
                                <div class="flex">
                                    <div class="shrink-0">
                                        <x-untitledui-alert-triangle
                                            class="size-5 text-yellow-400"
                                            stroke-width="1.5"
                                            aria-hidden="true"
                                        />
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            {{ __('shopper::pages/orders.no_payment_method') }}
                                        </p>
                                        <a
                                            href="https://laravelshopper.dev/docs/2.x/payment-methods"
                                            target="_blank"
                                            class="mt-1.5 inline-flex text-sm font-medium leading-5 text-yellow-700 underline hover:text-yellow-600"
                                        >
                                            {{ __('shopper::pages/orders.read_about_payment') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="py-4 sm:pr-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        {{ __('shopper::words.shipping') }}
                    </h3>
                    <div class="mt-4">
                        @if ($order->shippingOption)
                            <dl
                                class="bg-white divide-y divide-gray-200 overflow-hidden rounded-lg ring-1 ring-gray-200 dark:bg-gray-900 dark:divide-white/10 dark:bg-gray-800 dark:ring-white/10"
                            >
                                <div class="p-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('shopper::words.provider') }}
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm font-medium text-gray-900 dark:text-white sm:col-span-2 sm:mt-0"
                                    >
                                        {{ $order->shippingOption->name }}
                                        <span class="italic text-gray-500 dark:text-gray-400">
                                            ({{ $order->shippingOption->carrier->name }})
                                        </span>
                                    </dd>
                                </div>
                                <div class="p-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('shopper::forms.label.price') }}
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm font-medium text-gray-900 dark:text-white sm:col-span-2 sm:mt-0"
                                    >
                                        {{ shopper_money_format($order->shippingOption->price, $order->currency_code) }}
                                    </dd>
                                </div>
                                <div class="p-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('shopper::forms.label.tax') }}
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm font-medium text-gray-900 dark:text-white sm:col-span-2 sm:mt-0"
                                    >
                                        {{ __('shopper::words.not_available') }}
                                    </dd>
                                </div>
                            </dl>
                        @else
                            <div class="border-l-4 border-yellow-400 bg-yellow-50 p-4">
                                <div class="flex">
                                    <div class="shrink-0">
                                        <x-untitledui-alert-triangle
                                            class="size-5 text-yellow-400"
                                            stroke-width="1.5"
                                            aria-hidden="true"
                                        />
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            {{ __('shopper::pages/orders.no_shipping_method') }}
                                            <a
                                                href="https://laravelshopper.dev/docs/{{  shopper()->version() }}/shipping"
                                                target="_blank"
                                                class="font-medium text-yellow-700 underline hover:text-yellow-600"
                                            >
                                                {{ __('shopper::pages/orders.read_about_shipping') }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="py-4 sm:pr-8">
                    <div class="flex justify-end">
                        <div class="w-full space-y-1 text-right text-gray-700 dark:text-gray-300 sm:max-w-xs">
                            <div
                                class="inline-flex items-center justify-end gap-4 rounded-lg bg-gray-50 px-4 py-3 dark:bg-gray-800"
                            >
                                <span class="leading-6 text-gray-900 dark:text-white">
                                    {{ __('shopper::words.total') }}
                                </span>
                                <span class="font-semibold">
                                    {{ shopper_money_format($total, $order->currency_code) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="py-4 sm:pr-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        {{ __('shopper::pages/orders.private_notes') }}
                    </h3>
                    <div class="mt-5 flex space-x-3">
                        <div class="shrink-0">
                            <img
                                class="flex size-10 items-center justify-center rounded-full bg-gray-400 ring-4 ring-white dark:bg-gray-500 dark:ring-gray-800"
                                src="{{ auth()->user()->picture }}"
                                alt="Customer avatar"
                            />
                        </div>
                        <div class="min-w-0 flex-1">
                            @if ($order->notes)
                                <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                                    {{ $order->notes }}
                                </p>
                            @else
                                <div>
                                    <label for="comment" class="sr-only">
                                        {{ __('shopper::forms.label.comment') }}
                                    </label>
                                    <x-shopper::forms.textarea
                                        wire:model="notes"
                                        id="comment"
                                        :placeholder="__('shopper::forms.placeholder.leave_comment')"
                                        :value="$order->notes"
                                    />
                                    @error('notes')
                                        <p class="mt-1 text-sm text-danger-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-6 flex items-center justify-end space-x-4">
                                    <x-shopper::buttons.primary
                                        wire:click="leaveNotes"
                                        wire:loading.attr="disabled"
                                        type="button"
                                    >
                                        <x-shopper::loader wire:loading wire:target="leaveNotes" />
                                        {{ __('shopper::forms.actions.send') }}
                                    </x-shopper::buttons.primary>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="divide-y divide-gray-200 border-t border-gray-200 py-2 dark:divide-white/10 dark:border-white/10 sm:col-span-2 sm:border-l sm:border-t-0 sm:pl-8">
                <div class="py-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        {{ __('shopper::words.customer') }}
                    </h3>
                    <div class="mt-4 space-y-4">
                        @if ($customer)
                            <div class="flex items-center space-x-4">
                                <div class="shrink-0">
                                    <img
                                        class="size-8 rounded-full"
                                        src="{{ $customer->picture }}"
                                        alt="Customer profile"
                                    />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $customer->full_name }}
                                    </p>
                                </div>
                                <div>
                                    <x-shopper::link
                                        href="{{ route('shopper.customers.show', $customer) }}"
                                        class="inline-flex items-center rounded-full border border-gray-300 bg-white px-2.5 py-0.5 text-sm font-medium leading-5 text-gray-700 shadow-sm hover:bg-gray-50 dark:border-white/10 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                    >
                                        {{ __('shopper::words.view') }}
                                    </x-shopper::link>
                                </div>
                            </div>
                            <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/orders.customer_date', ['date' => $customer->created_at->diffForHumans()]) }},
                                {{ __('shopper::pages/orders.customer_orders', ['number' => $customer->orders_count]) }}
                            </p>
                        @else
                            <div
                                class="inline-flex items-center gap-2 rounded-sm bg-gray-50 px-4 py-2 dark:bg-gray-800"
                            >
                                <x-untitledui-user-02
                                    class="size-5 text-gray-400 dark:text-gray-500"
                                    stroke-width="1.5"
                                    aria-hidden="true"
                                />
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::pages/orders.no_customer') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="space-y-3 py-4">
                    <h3 class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-900 dark:text-white">
                        {{ __('shopper::pages/orders.customer_infos') }}
                    </h3>

                    @if ($customer)
                        <div class="space-y-1">
                            <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                                <a
                                    href="mailto:{{ $customer->email }}"
                                    class="text-primary-600 underline hover:text-primary-500"
                                >
                                    {{ $customer->email }}
                                </a>
                            </p>
                            <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                                {{ $customer->phone_number ?? __('shopper::words.no_phone_number') }}
                            </p>
                        </div>
                    @else
                        <p class="text-sm leading-6 text-gray-500 dark:text-gray-400">
                            {{ __('shopper::pages/orders.customer_infos_empty') }}
                        </p>
                    @endif
                </div>

                @if ($shippingAddress)
                    <div class="py-4">
                        <h3 class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-900 dark:text-white">
                            {{ __('shopper::pages/customers.addresses.shipping') }}
                        </h3>
                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                            {{ $order->shippingAddress->full_name }}
                            <br />
                            @if ($order->shippingAddress->company)
                                {{ $order->shippingAddress->company }}
                                <br />
                            @endif

                            {{ $order->shippingAddress->street_address }}
                            <br />
                            {{ $order->shippingAddress->postal_code }}, {{ $order->shippingAddress->city }}
                            <br />
                            {{ $order->shippingAddress->country_name }}
                            <br />
                            @if ($order->shippingAddress->phone)
                                <span>{{ $order->shippingAddress->phone }}</span>
                            @endif
                        </p>
                    </div>

                    @if ($billingAddress)
                        <div class="space-y-3 py-4">
                            <h3 class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-900 dark:text-white">
                                {{ __('shopper::pages/customers.addresses.billing') }}
                            </h3>

                            @if ($billingAddress->is($shippingAddress))
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::words.same_address') }}
                                </p>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $billingAddress->full_name }}
                                    <br />
                                    @if ($billingAddress->company)
                                        {{ $billingAddress->company }}
                                        <br />
                                    @endif

                                    {{ $billingAddress->street_address }}
                                    <br />
                                    {{ $billingAddress->postal_code }}, {{ $billingAddress->city }}
                                    <br />
                                    {{ $billingAddress->country_name }}
                                    <br />
                                    @if ($billingAddress->phone)
                                        <span>{{ $billingAddress->phone }}</span>
                                    @endif
                                </p>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </x-shopper::container>

    <div x-data>
        <template x-teleport="body">
            <x-filament-actions::modals />
        </template>
    </div>
</div>
