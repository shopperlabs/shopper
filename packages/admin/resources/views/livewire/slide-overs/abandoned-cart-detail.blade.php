@php
    $customer = $this->cart->customer;
    $shippingAddress = $this->cart->shippingAddress();
    $billingAddress = $this->cart->billingAddress();
    $lines = $this->cart->lines;
@endphp

<x-shopper::slideover-card>
    <div class="h-0 flex-1 overflow-y-auto py-4">
        <div class="px-4">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <h2 class="font-heading text-sh-fg text-xl font-bold">
                            {{ __('shopper::pages/orders.abandoned_carts.detail_title', ['id' => $this->cart->id]) }}
                        </h2>
                        <x-filament::badge color="warning" icon="untitledui-clock">
                            {{ $this->cart->updated_at->diffForHumans() }}
                        </x-filament::badge>
                    </div>
                    <p class="text-sh-fg-muted text-sm">
                        {{ __('shopper::forms.label.created_at') }}
                        <span class="text-sh-fg-secondary font-medium">
                            {{ $this->cart->created_at->translatedFormat('j M Y H:i') }}
                        </span>
                    </p>
                </div>
                <x-livewire-slide-over::close-icon />
            </div>

            <div class="bg-sh-muted mt-6 rounded-xl p-4">
                <h3 class="text-sh-fg-muted text-xs font-medium tracking-wider uppercase">
                    {{ __('shopper::words.customer') }}
                </h3>
                <div class="mt-2">
                    @if ($customer)
                        <p class="text-sh-fg text-sm font-medium">
                            {{ $customer->full_name }}
                        </p>
                        @if ($customer->email)
                            <p class="text-sh-fg-muted text-sm">{{ $customer->email }}</p>
                        @endif
                    @else
                        <p class="text-sh-fg-muted text-sm">
                            {{ __('shopper::pages/orders.abandoned_carts.guest') }}
                        </p>
                    @endif
                </div>
                @if ($this->cart->channel)
                    <div class="mt-3 flex items-center gap-2">
                        <x-filament::badge color="gray" icon="phosphor-storefront-duotone">
                            {{ $this->cart->channel->name }}
                        </x-filament::badge>
                        <x-filament::badge color="gray">
                            {{ $this->cart->currency_code }}
                        </x-filament::badge>
                    </div>
                @endif
            </div>

            @if ($lines->isNotEmpty())
                <x-shopper::card class="mt-6 [&>div:first-of-type]:p-0">
                    <div class="p-4">
                        <h3 class="text-sh-fg text-sm font-medium">
                            {{ __('shopper::pages/orders.abandoned_carts.items') }}
                            <span class="text-sh-fg-muted">({{ $lines->count() }})</span>
                        </h3>
                    </div>
                    <div class="border-sh-border border-t">
                        <table class="fi-ta-table divide-sh-border w-full table-auto divide-y rounded-none! text-start">
                            <thead>
                                <tr>
                                    <th class="fi-ta-header-cell px-3 py-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                        <span class="fi-ta-header-cell-label text-sh-fg text-sm font-semibold">
                                            {{ __('shopper::words.product') }}
                                        </span>
                                    </th>
                                    <th
                                        class="fi-ta-header-cell w-16 px-3 py-2 text-right sm:first-of-type:ps-6 sm:last-of-type:pe-6"
                                    >
                                        <span class="fi-ta-header-cell-label text-sh-fg text-sm font-semibold">
                                            {{ __('shopper::words.qty') }}
                                        </span>
                                    </th>
                                    <th
                                        class="fi-ta-header-cell w-24 px-3 py-2 text-right sm:first-of-type:ps-6 sm:last-of-type:pe-6"
                                    >
                                        <span class="fi-ta-header-cell-label text-sh-fg text-sm font-semibold">
                                            {{ __('shopper::words.price') }}
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-sh-border divide-y whitespace-nowrap">
                                @foreach ($lines as $line)
                                    @php
                                        $purchasable = $line->purchasable;
                                        $thumbnailUrl = $purchasable?->getThumbnailUrl();
                                    @endphp

                                    <tr>
                                        <td
                                            class="fi-ta-cell overflow-hidden p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3"
                                        >
                                            <div class="flex min-w-0 items-center gap-3 px-3 py-2">
                                                @if ($thumbnailUrl)
                                                    <img
                                                        src="{{ $thumbnailUrl }}"
                                                        class="ring-sh-border size-8 shrink-0 rounded-lg object-cover ring-1"
                                                        alt="{{ $purchasable?->name }}"
                                                    />
                                                @else
                                                    <div
                                                        class="bg-sh-muted ring-sh-border flex size-8 shrink-0 items-center justify-center rounded-lg ring-1"
                                                    >
                                                        <x-untitledui-image class="text-sh-fg-muted size-4" />
                                                    </div>
                                                @endif
                                                <span class="text-sh-fg truncate text-sm">
                                                    {{ $purchasable?->name ?? '—' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td
                                            class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3"
                                        >
                                            <div class="px-3 py-2 text-right">
                                                <span class="text-sh-fg-secondary text-sm tabular-nums">
                                                    {{ $line->quantity }}
                                                </span>
                                            </div>
                                        </td>
                                        <td
                                            class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3"
                                        >
                                            <div class="px-3 py-2 text-right">
                                                <span class="text-sh-fg-secondary text-sm font-medium tabular-nums">
                                                    {{ shopper_money_format($line->unit_price_amount, $this->cart->currency_code) }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-shopper::card>
            @endif

            @if ($shippingAddress || $billingAddress)
                <div class="{{ $shippingAddress && $billingAddress ? 'grid-cols-2' : 'grid-cols-1' }} mt-6 grid gap-4">
                    @if ($shippingAddress)
                        <div class="border-sh-border rounded-lg border p-4">
                            <h4 class="text-sh-fg-muted text-xs font-medium tracking-wider uppercase">
                                {{ __('shopper::pages/orders.shipping_address') }}
                            </h4>
                            <div class="text-sh-fg-secondary mt-2 space-y-1 text-sm">
                                <p class="text-sh-fg font-medium">{{ $shippingAddress->full_name }}</p>
                                <p>{{ $shippingAddress->address_1 }}</p>
                                @if ($shippingAddress->address_2)
                                    <p>{{ $shippingAddress->address_2 }}</p>
                                @endif

                                <p>{{ $shippingAddress->city }} {{ $shippingAddress->postal_code }}</p>
                                @if ($shippingAddress->country)
                                    <p>{{ $shippingAddress->country->translated_name }}</p>
                                @endif

                                @if ($shippingAddress->phone)
                                    <p>{{ $shippingAddress->phone }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($billingAddress)
                        <div class="border-sh-border rounded-lg border p-4">
                            <h4 class="text-sh-fg-muted text-xs font-medium tracking-wider uppercase">
                                {{ __('shopper::pages/orders.abandoned_carts.billing_address') }}
                            </h4>
                            <div class="text-sh-fg-secondary mt-2 space-y-1 text-sm">
                                <p class="text-sh-fg font-medium">{{ $billingAddress->full_name }}</p>
                                <p>{{ $billingAddress->address_1 }}</p>
                                @if ($billingAddress->address_2)
                                    <p>{{ $billingAddress->address_2 }}</p>
                                @endif

                                <p>{{ $billingAddress->city }} {{ $billingAddress->postal_code }}</p>
                                @if ($billingAddress->country)
                                    <p>{{ $billingAddress->country->translated_name }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-shopper::slideover-card>
