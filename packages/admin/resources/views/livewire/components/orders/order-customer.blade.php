<div class="divide-y divide-sh-border">
    <div class="py-4">
        <h3 class="text-lg leading-6 font-medium text-sh-fg">
            {{ __('shopper::words.customer') }}
        </h3>
        <div class="mt-4 space-y-4">
            @if ($this->customer)
                <div class="flex items-center space-x-4">
                    <div class="shrink-0">
                        <img
                            class="size-8 rounded-full"
                            src="{{ $this->customer->picture }}"
                            alt="Customer profile"
                        />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-sh-fg">
                            {{ $this->customer->full_name }}
                        </p>
                    </div>
                    <div>
                        <x-shopper::link
                            href="{{ route('shopper.customers.show', $this->customer) }}"
                            class="inline-flex items-center rounded-md border border-sh-border bg-sh-surface px-2.5 py-0.5 text-sm font-medium text-sh-fg-secondary hover:bg-sh-muted"
                        >
                            {{ __('shopper::words.view') }}
                        </x-shopper::link>
                    </div>
                </div>
                <p class="text-sm text-sh-fg-muted">
                    {{ __('shopper::pages/orders.customer_date', ['date' => $this->customer->created_at->diffForHumans()]) }},
                    {{ __('shopper::pages/orders.customer_orders', ['number' => $this->customer->orders_count]) }}
                </p>
            @else
                <div
                    class="inline-flex items-center gap-2 rounded-sm bg-sh-muted px-4 py-2"
                >
                    <x-untitledui-user-02
                        class="size-5 text-sh-fg-muted"
                        stroke-width="1.5"
                        aria-hidden="true"
                    />
                    <span class="text-sm text-sh-fg-muted">
                        {{ __('shopper::pages/orders.no_customer') }}
                    </span>
                </div>
            @endif
        </div>
    </div>
    <div class="space-y-3 py-4">
        <h3 class="text-xs leading-4 font-medium tracking-wider text-sh-fg uppercase">
            {{ __('shopper::pages/orders.customer_infos') }}
        </h3>

        @if ($this->customer)
            <div class="space-y-1">
                <p class="text-sm text-sh-fg-muted">
                    <a
                        href="mailto:{{ $this->customer->email }}"
                        class="text-primary-600 hover:text-primary-500 underline"
                    >
                        {{ $this->customer->email }}
                    </a>
                </p>
                <p class="text-sm text-sh-fg-muted">
                    {{ $this->customer->phone_number ?? __('shopper::words.no_phone_number') }}
                </p>
            </div>
        @else
            <p class="text-sm leading-6 text-sh-fg-muted">
                {{ __('shopper::pages/orders.customer_infos_empty') }}
            </p>
        @endif
    </div>

    @if ($shippingAddress)
        <div class="py-4">
            <h3
                class="text-xs leading-4 font-medium tracking-wider text-sh-fg uppercase"
            >
                {{ __('shopper::pages/customers.addresses.shipping') }}
            </h3>
            <p class="mt-3 text-sm text-sh-fg-muted">
                {{ $shippingAddress->full_name }}
                <br />
                @if ($shippingAddress->company)
                    {{ $shippingAddress->company }}
                    <br />
                @endif

                {{ $shippingAddress->street_address }}
                <br />
                {{ $shippingAddress->postal_code }},
                {{ $shippingAddress->city }}
                <br />
                {{ $shippingAddress->country_name }}
                <br />
                @if ($shippingAddress->phone)
                    <span>
                        {{ $shippingAddress->phone }}
                    </span>
                @endif
            </p>
        </div>

        @if ($billingAddress)
            <div class="space-y-3 py-4">
                <h3
                    class="text-xs leading-4 font-medium tracking-wider text-sh-fg uppercase"
                >
                    {{ __('shopper::pages/customers.addresses.billing') }}
                </h3>

                @if ($billingAddress->is($shippingAddress))
                    <p class="text-sm text-sh-fg-muted">
                        {{ __('shopper::words.same_address') }}
                    </p>
                @else
                    <p class="text-sm text-sh-fg-muted">
                        {{ $billingAddress->full_name }}
                        <br />
                        @if ($billingAddress->company)
                            {{ $billingAddress->company }}
                            <br />
                        @endif

                        {{ $billingAddress->street_address }}
                        <br />
                        {{ $billingAddress->postal_code }},
                        {{ $billingAddress->city }}
                        <br />
                        {{ $billingAddress->country_name }}
                        <br />
                        @if ($billingAddress->phone)
                            <span>
                                {{ $billingAddress->phone }}
                            </span>
                        @endif
                    </p>
                @endif
            </div>
        @endif
    @endif
</div>
