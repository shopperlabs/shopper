<x-shopper::card class="[&>div:first-of-type]:p-0">
    <div class="flex items-center justify-between p-4 dark:border-white/5">
        <h3 class="font-heading text-base font-semibold text-gray-900 dark:text-white">
            {{ __('shopper::pages/dashboard.recent_orders.heading') }}
        </h3>
        <x-shopper::link
            :href="route('shopper.orders.index')"
            wire:navigate
            class="inline-flex items-center gap-1 text-sm font-medium text-primary-500 transition-colors hover:text-primary-700 dark:text-gray-500 dark:hover:text-white"
        >
            {{ __('shopper::pages/dashboard.recent_orders.view_all') }}
            <x-untitledui-arrow-narrow-right class="size-3.5" />
        </x-shopper::link>
    </div>

    <div class="border-t border-gray-200 dark:border-white/10">
        @if ($this->orders->isNotEmpty())
            <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                <thead>
                    <tr>
                        <th class="fi-ta-header-cell px-3 py-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                {{ __('shopper::pages/orders.order_number') }}
                            </span>
                        </th>
                        <th class="fi-ta-header-cell px-3 py-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                {{ __('shopper::pages/products.menu') }}
                            </span>
                        </th>
                        <th class="fi-ta-header-cell px-3 py-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                {{ __('shopper::words.amount') }}
                            </span>
                        </th>
                        <th class="fi-ta-header-cell px-3 py-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                {{ __('shopper::forms.label.status') }}
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                    @foreach ($this->orders as $order)
                        <tr
                            class="cursor-pointer transition-colors hover:bg-gray-50 dark:hover:bg-white/5"
                            wire:click="$dispatch('navigateTo', { url: '{{ route('shopper.orders.detail', $order) }}' })"
                            x-on:click="window.Livewire.navigate('{{ route('shopper.orders.detail', $order) }}')"
                        >
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="px-3 py-2">
                                    <span class="text-sm font-medium text-gray-950 dark:text-white">
                                        #{{ $order->number }}
                                    </span>
                                </div>
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="flex items-center gap-2 px-3 py-2">
                                    @if ($order->items->first()?->product)
                                        <img
                                            class="size-7 shrink-0 rounded-lg object-cover ring-1 ring-gray-100 dark:ring-white/10"
                                            src="{{ $order->items->first()->product->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection')) }}"
                                            alt="{{ $order->items->first()->name }}"
                                        />
                                    @endif
                                    <span class="truncate text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->items->first()?->name ?? '—' }}
                                        @if ($order->items->count() > 1)
                                            <span class="text-gray-400 dark:text-gray-500">
                                                +{{ $order->items->count() - 1 }}
                                            </span>
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="px-3 py-2">
                                    <span class="text-sm font-medium tabular-nums text-gray-700 dark:text-gray-300">
                                        {{ shopper_money_format($order->total(), $order->currency_code) }}
                                    </span>
                                </div>
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="px-3 py-2">
                                    <x-filament::badge
                                        size="sm"
                                        :color="$order->status->getColor()"
                                        :icon="$order->status->getIcon()"
                                    >
                                        {{ $order->status->getLabel() }}
                                    </x-filament::badge>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-5 text-center">
                <x-untitledui-shopping-bag-02 class="mx-auto size-8 text-gray-300 dark:text-gray-600" />
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('shopper::pages/dashboard.recent_orders.empty') }}
                </p>
            </div>
        @endif
    </div>
</x-shopper::card>
