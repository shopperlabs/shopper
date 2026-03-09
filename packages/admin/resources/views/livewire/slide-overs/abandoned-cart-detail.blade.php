@php
    $customer = $this->cart->customer;
    $shippingAddress = $this->cart->shippingAddress();
    $billingAddress = $this->cart->billingAddress();
    $lines = $this->cart->lines;
@endphp

<div class="flex h-full flex-col divide-y divide-gray-200 dark:divide-white/10">
    <div class="h-0 flex-1 overflow-y-auto py-4">
        <div class="px-4">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <h2 class="font-heading text-xl font-bold text-gray-900 dark:text-white">
                            {{ __('shopper::pages/orders.abandoned_carts.detail_title', ['id' => $this->cart->id]) }}
                        </h2>
                        <x-filament::badge color="warning" icon="untitledui-clock">
                            {{ $this->cart->updated_at->diffForHumans() }}
                        </x-filament::badge>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('shopper::forms.label.created_at') }}
                        <span class="font-medium text-gray-700 dark:text-gray-300">
                            {{ $this->cart->created_at->translatedFormat('j M Y H:i') }}
                        </span>
                    </p>
                </div>
                <div class="ml-3 flex h-7 items-center">
                    <x-shopper::escape />
                    <button
                        type="button"
                        class="rounded-md bg-white text-gray-400 outline-none hover:text-gray-500 dark:bg-gray-900 dark:text-gray-500 dark:hover:text-gray-300"
                        wire:click="$dispatch('closePanel')"
                    >
                        <span class="sr-only">Close panel</span>
                        <x-untitledui-x class="size-6" stroke-width="1.5" aria-hidden="true" />
                    </button>
                </div>
            </div>

            <div class="mt-6 rounded-xl bg-gray-50 p-4 dark:bg-gray-950">
                <h3 class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    {{ __('shopper::words.customer') }}
                </h3>
                <div class="mt-2">
                    @if ($customer)
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $customer->full_name }}
                        </p>
                        @if ($customer->email)
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->email }}</p>
                        @endif
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">
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
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('shopper::pages/orders.abandoned_carts.items') }}
                            <span class="text-gray-500 dark:text-gray-400">({{ $lines->count() }})</span>
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 dark:border-white/10">
                        <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/10 rounded-none!">
                            <thead>
                                <tr>
                                    <th class="fi-ta-header-cell px-3 py-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                        <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                            {{ __('shopper::words.product') }}
                                        </span>
                                    </th>
                                    <th class="fi-ta-header-cell w-16 px-3 py-2 text-right sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                        <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                            {{ __('shopper::words.qty') }}
                                        </span>
                                    </th>
                                    <th class="fi-ta-header-cell w-24 px-3 py-2 text-right sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                        <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                            {{ __('shopper::words.price') }}
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                                @foreach ($lines as $line)
                                    @php
                                        $purchasable = $line->purchasable;
                                        $thumbnailUrl = $purchasable?->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection'));
                                    @endphp
                                    <tr>
                                        <td class="fi-ta-cell overflow-hidden p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                            <div class="flex min-w-0 items-center gap-3 px-3 py-2">
                                                @if ($thumbnailUrl)
                                                    <img
                                                        src="{{ $thumbnailUrl }}"
                                                        class="size-8 shrink-0 rounded-lg object-cover ring-1 ring-gray-100 dark:ring-white/10"
                                                        alt="{{ $purchasable?->name }}"
                                                    />
                                                @else
                                                    <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 ring-1 ring-gray-100 dark:bg-white/10 dark:ring-white/10">
                                                        <x-untitledui-image class="size-4 text-gray-400 dark:text-gray-500" />
                                                    </div>
                                                @endif
                                                <span class="truncate text-sm text-gray-950 dark:text-white">
                                                    {{ $purchasable?->name ?? '—' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                            <div class="px-3 py-2 text-right">
                                                <span class="text-sm tabular-nums text-gray-700 dark:text-gray-300">
                                                    {{ $line->quantity }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                            <div class="px-3 py-2 text-right">
                                                <span class="text-sm font-medium tabular-nums text-gray-700 dark:text-gray-300">
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
                <div class="mt-6 grid gap-4 {{ $shippingAddress && $billingAddress ? 'grid-cols-2' : 'grid-cols-1' }}">
                    @if ($shippingAddress)
                        <div class="rounded-lg border border-gray-200 p-4 dark:border-white/10">
                            <h4 class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/orders.shipping_address') }}
                            </h4>
                            <div class="mt-2 space-y-1 text-sm text-gray-700 dark:text-gray-300">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $shippingAddress->full_name }}</p>
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
                        <div class="rounded-lg border border-gray-200 p-4 dark:border-white/10">
                            <h4 class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/orders.abandoned_carts.billing_address') }}
                            </h4>
                            <div class="mt-2 space-y-1 text-sm text-gray-700 dark:text-gray-300">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $billingAddress->full_name }}</p>
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
</div>
