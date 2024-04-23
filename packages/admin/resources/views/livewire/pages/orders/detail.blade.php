<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.orders.index')">
        <x-untitledui-chevron-left class="h-4 w-4 shrink-0 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.orders.index')"
            :title="__('shopper::layout.sidebar.orders')"
        />
    </x-shopper::breadcrumb>

    <div class="z-30 mt-5 border-b border-gray-200 pb-5 dark:border-gray-700">
        <div class="space-y-4">
            <div class="space-y-3 lg:flex lg:items-center lg:justify-between lg:space-y-0">
                <div class="flex min-w-0 flex-1 items-center space-x-4">
                    <h3
                        class="font-heading text-2xl font-bold leading-6 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:leading-9"
                    >
                        {{ $order->number }}
                    </h3>
                    <div class="flex items-center divide-x-2 divide-gray-200 p-1 dark:divide-gray-700">
                        <div class="flex items-center space-x-2 pr-4">
                            <span
                                class="{{ $order->status->badge() }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                            >
                                {{ $order->status->translateValue() }}
                            </span>
                        </div>
                        <div class="flex items-center px-4">
                            <x-untitledui-calendar class="mr-1.5 h-5 w-5 text-gray-500 dark:text-gray-400" />
                            <span class="text-sm font-medium leading-6 text-gray-700 dark:text-gray-300">
                                <time datetime="{{ $order->created_at->format('F j, Y h:m') }}">
                                    {{ $order->created_at->formatLocalized('%d %B %G - %H:%M') }}
                                </time>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    @if (! $order->isCompleted())
                        @if (! $order->isPaid())
                            <span class="hidden sm:block">
                                <x-shopper::buttons.danger
                                    wire:click="$emit('openModal', 'shopper-modals.archived-order', {{ json_encode([$order->id]) }})"
                                    type="button"
                                >
                                    <x-untitledui-trash-03 class="mr-2 h-5 w-5" />
                                    {{ __('shopper::layout.forms.actions.delete') }}
                                </x-shopper::buttons.danger>
                            </span>
                        @endif

                        <x-shopper::dropdown>
                            <x-slot name="trigger">
                                <x-shopper::buttons.default>
                                    {{ __('shopper::layout.forms.actions.more_actions') }}
                                    <x-untitledui-chevron-selector-vertical class="ml-2 h-5 w-5" />
                                </x-shopper::buttons.default>
                            </x-slot>

                            <x-slot name="content">
                                <div class="py-1">
                                    @if ($order->isPending())
                                        <x-shopper::dropdown-button wire:click="register" role="menuitem">
                                            {{ __('shopper::status.registered') }}
                                        </x-shopper::dropdown-button>
                                    @endif

                                    @if ($order->isPending() || $order->isRegister())
                                        <x-shopper::dropdown-button wire:click="markPaid" role="menuitem">
                                            {{ __('shopper::layout.forms.actions.mark_paid') }}
                                        </x-shopper::dropdown-button>
                                    @endif

                                    @if ($order->isPaid())
                                        <x-shopper::dropdown-button wire:click="markComplete" role="menuitem">
                                            <x-untitledui-check-circle
                                                class="mr-2 h-5 w-5 text-gray-400 dark:text-gray-500"
                                            />
                                            {{ __('shopper::layout.forms.actions.mark_complete') }}
                                        </x-shopper::dropdown-button>
                                    @endif

                                    @if ($order->canBeCancelled())
                                        <x-shopper::dropdown-button wire:click="cancelOrder" role="menuitem">
                                            <x-untitledui-x class="mr-2 h-5 w-5 text-gray-400 dark:text-gray-500" />
                                            {{ __('shopper::layout.forms.actions.cancel_order') }}
                                        </x-shopper::dropdown-button>
                                    @endif
                                </div>
                            </x-slot>
                        </x-shopper::dropdown>
                    @endif

                    <span class="relative z-0 inline-flex shadow-sm">
                        <button
                            @if($prevOrder) wire:click="goToOrder({{ $prevOrder->id }})" @endif
                            type="button"
                            @class([
                                'focus:shadow-outline-primary relative inline-flex items-center rounded-l-lg border border-gray-300 px-2 py-2 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-primary-300 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-500',
                                'bg-gray-50 disabled:opacity-50' => ! $prevOrder,
                                'bg-white' => $prevOrder,
                            ])
                            class="@if(! $prevOrder)  @endif dark:text-gray-400"
                            aria-label="{{ __('Previous order') }}"
                            @if(! $prevOrder) disabled @endif
                        >
                            <x-untitledui-chevron-left class="h-5 w-5" />
                        </button>
                        <button
                            @if($nextOrder) wire:click="goToOrder({{ $nextOrder->id }})" @endif
                            type="button"
                            @class([
                                'focus:shadow-outline-primary relative -ml-px inline-flex items-center rounded-r-lg border border-gray-300 px-2 py-2 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-primary-300 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-500',
                                'bg-gray-50 disabled:opacity-50' => ! $nextOrder,
                                'bg-white' => $nextOrder,
                            ])
                            class="@if(! $nextOrder) bg-gray-100 dark:bg-gray-800 hover:text-gray-500 dark:text-gray-400 disabled:opacity-50 @endif"
                            aria-label="{{ __('Next order') }}"
                            @if(! $nextOrder) disabled @endif
                        >
                            <x-untitledui-chevron-right class="h-5 w-5" />
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid sm:grid-cols-6">
        <div class="divide-y divide-gray-200 py-2 dark:divide-gray-700 sm:col-span-4">
            <div class="py-4 sm:pr-8">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        {{ __('shopper::layout.sidebar.products') }}
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
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($items as $item)
                            <li class="flex-1 py-2.5">
                                <div class="flex items-center">
                                    <div class="flex min-w-0 flex-1 items-center">
                                        <div class="shrink-0">
                                            <div class="h-10 w-10 shrink-0">
                                                <img
                                                    class="h-10 w-10 rounded-lg object-cover"
                                                    src="{{ $item->product->getFirstMediaUrl(config('shopper.core.storage.collection_name')) }}"
                                                    alt="{{ $item->id }}"
                                                />
                                            </div>
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
                                                    {{ shopper_money_format($item->unit_price_amount) }} x
                                                    {{ $item->quantity }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                                            {{ shopper_money_format($item->total) }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-2 border-t border-gray-200 pt-3 dark:border-gray-700">
                        {{ $items->links() }}
                    </div>
                </div>
                <div class="mt-3 flex justify-end">
                    <div class="w-full space-y-1 text-right sm:max-w-lg">
                        <div class="rounded-md bg-gray-100 p-4 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                            <span class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                                {{ __('shopper::words.subtotal') }}
                            </span>
                            {{ $order->total }}
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
                <div class="mt-4">
                    <div class="flex py-4">
                        @if ($order->paymentMethod->logo)
                            <img
                                class="h-10 w-10 rounded-md object-cover"
                                src="{{ $order->paymentMethod->logo_url }}"
                                alt="payment icon"
                            />
                        @else
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-md bg-gray-100 text-gray-300 dark:bg-gray-800 dark:text-white"
                            >
                                <x-untitledui-image class="h-6 w-6" />
                            </span>
                        @endif
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $order->paymentMethod->title }}
                            </p>
                            <a
                                href="{{ route('shopper.settings.payments') }}"
                                class="text-sm text-gray-500 underline hover:text-gray-400 dark:text-gray-400"
                            >
                                {{ __('shopper::words.available_methods') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-4 sm:pr-8">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                    {{ __('shopper::words.shipping') }}
                </h3>
                <div class="mt-4">
                    @if ($order->shipping_method)
                        <dl>
                            <div class="bg-gray-50 px-4 py-4 dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::words.provider') }}
                                </dt>
                                <dd
                                    class="mt-1 text-sm font-medium text-gray-900 dark:text-white sm:col-span-2 sm:mt-0"
                                >
                                    {{ $order->shipping_method }}
                                </dd>
                            </div>
                            <div class="bg-gray-100 px-4 py-4 dark:bg-gray-700 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::layout.forms.label.price') }}
                                </dt>
                                <dd
                                    class="mt-1 text-sm font-medium text-gray-900 dark:text-white sm:col-span-2 sm:mt-0"
                                >
                                    {{ shopper_money_format($order->shipping_total) }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-4 dark:bg-gray-800 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::layout.forms.label.tax') }}
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
                                    <x-untitledui-alert-triangle class="h-5 w-5 text-yellow-400" />
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        {{ __('shopper::pages/orders.no_shipping_method') }}
                                        <a href="#" class="font-medium text-yellow-700 underline hover:text-yellow-600">
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
                    <div class="w-full space-y-1 text-right text-gray-700 dark:text-gray-300 sm:max-w-sm">
                        <div class="rounded-md bg-gray-100 p-4 dark:bg-gray-800">
                            <span class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                                {{ __('shopper::words.total') }}
                            </span>
                            {{ shopper_money_format($order->fullPriceWithShipping()) }}
                        </div>
                    </div>
                </div>
                <div class="mt-2 flex justify-end text-right">
                    <x-shopper::dropdown width="56">
                        <x-slot name="trigger">
                            <x-shopper::buttons.default>
                                {{ __('shopper::pages/orders.payment_actions') }}
                                <x-untitledui-chevron-selector-vertical class="ml-2 h-5 w-5" />
                            </x-shopper::buttons.default>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-1">
                                <x-shopper::dropdown-button role="menuitem">
                                    {{ __('shopper::pages/orders.send_invoice') }}
                                    <span
                                        class="ml-3 inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium leading-4 text-gray-800 dark:bg-gray-800 dark:text-gray-300"
                                    >
                                        {{ __('shopper::layout.soon') }}
                                    </span>
                                </x-shopper::dropdown-button>
                                @if ($order->isPending())
                                    <x-shopper::dropdown-button wire:click="register" role="menuitem">
                                        {{ __('shopper::status.registered') }}
                                    </x-shopper::dropdown-button>
                                @endif

                                @if ($order->isPending() || $order->isRegister())
                                    <x-shopper::dropdown-button wire:click="markPaid" role="menuitem">
                                        {{ __('shopper::layout.forms.actions.mark_paid') }}
                                    </x-shopper::dropdown-button>
                                @endif

                                @if ($order->isPaid())
                                    <x-shopper::dropdown-button wire:click="markComplete" role="menuitem">
                                        {{ __('shopper::layout.forms.actions.mark_complete') }}
                                    </x-shopper::dropdown-button>
                                @endif
                            </div>
                        </x-slot>
                    </x-shopper::dropdown>
                </div>
            </div>
            <div class="py-4 sm:pr-8">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                    {{ __('shopper::pages/orders.private_notes') }}
                </h3>
                <div class="mt-5 flex space-x-3">
                    <div class="shrink-0">
                        <img
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-400 ring-4 ring-white dark:bg-gray-500 dark:ring-gray-800"
                            src="{{ auth()->user()->picture }}"
                            alt=""
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
                                    {{ __('shopper::layout.forms.label.comment') }}
                                </label>
                                <x-shopper::forms.textarea
                                    wire:model.defer="notes"
                                    id="comment"
                                    :placeholder="__('shopper::layout.forms.placeholder.leave_comment')"
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
                                    {{ __('shopper::layout.forms.actions.send') }}
                                </x-shopper::buttons.primary>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div
            class="divide-y divide-gray-200 border-t border-gray-200 py-2 dark:divide-gray-700 dark:border-gray-700 sm:col-span-2 sm:border-l sm:border-t-0 sm:pl-8"
        >
            <div class="py-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                    {{ __('shopper::words.customer') }}
                </h3>
                <div class="mt-4 flex items-center space-x-4">
                    <div class="shrink-0">
                        <img
                            class="h-8 w-8 rounded-full"
                            src="{{ $order->customer->picture }}"
                            alt="Customer profile"
                        />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-gray-900 dark:text-white">
                            {{ $order->customer->full_name }}
                        </p>
                    </div>
                    <div>
                        <a
                            href="{{ route('shopper.customers.show', $order->customer) }}"
                            class="inline-flex items-center rounded-full border border-gray-300 bg-white px-2.5 py-0.5 text-sm font-medium leading-5 text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                        >
                            {{ __('shopper::words.view') }}
                        </a>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/orders.customer_date', ['date' => $order->customer->created_at->diffForHumans()]) }}
                    </p>
                    <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/orders.customer_orders', ['number' => $order->customer->orders->count()]) }}
                    </p>
                </div>
            </div>
            <div class="py-4">
                <h3 class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-900 dark:text-white">
                    {{ __('shopper::pages/orders.customer_infos') }}
                </h3>
                <div class="mt-3 space-y-1">
                    <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                        <a
                            href="mailto:{{ $order->customer->email }}"
                            class="text-primary-600 underline hover:text-primary-500"
                        >
                            {{ $order->customer->email }}
                        </a>
                    </p>
                    <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ $order->customer->phone_number ?? __('shopper::words.no_phone_number') }}
                    </p>
                </div>
            </div>
            <div class="py-4">
                <h3 class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-900 dark:text-white">
                    {{ __('shopper::pages/customers.addresses.shipping') }}
                </h3>
                <p class="mt-3 text-sm leading-5 text-gray-500 dark:text-gray-400">
                    {{ $order->shippingAddress->full_name }}
                    <br />
                    @if ($order->shippingAddress->company_name)
                        {{ $order->shippingAddress->company_name }}
                        <br />
                    @endif

                    {{ $order->shippingAddress->street_address }}
                    <br />
                    {{ $order->shippingAddress->zipcode }} {{ $order->shippingAddress->city }}
                    <br />
                    {{ $order->shippingAddress->country->name }}
                    {{ isoToEmoji($order->shippingAddress->country->cca2) }}
                    <br />
                    @if ($order->shippingAddress->phone_number)
                        <span>{{ $order->shippingAddress->phone_number }}</span>
                    @endif
                </p>
            </div>
            <div class="py-4">
                <h3 class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-900 dark:text-white">
                    {{ __('shopper::pages/customers.addresses.billing') }}
                </h3>
                @if (! $billingAddress || $billingAddress->id === $order->shippingAddress->id)
                    <p class="mt-3 text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::words.same_address') }}
                    </p>
                @else
                    <p class="mt-3 text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ $billingAddress->full_name }}
                        <br />
                        @if ($billingAddress->company_name)
                            {{ $billingAddress->company_name }}
                            <br />
                        @endif

                        {{ $billingAddress->street_address }}
                        <br />
                        {{ $billingAddress->zipcode }} {{ $billingAddress->city }}
                        <br />
                        {{ $billingAddress->country->name }} {{ isoToEmoji($billingAddress->country->cca2) }}
                        <br />
                        @if ($billingAddress->phone_number)
                            <span>{{ $billingAddress->phone_number }}</span>
                        @endif
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-shopper::container>
