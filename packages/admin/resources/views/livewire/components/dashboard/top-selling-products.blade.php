<x-shopper::card class="[&>div:first-of-type]:p-0">
    <div class="flex items-center justify-between p-4">
        <h3 class="font-heading text-base font-semibold text-gray-900 dark:text-white">
            {{ __('shopper::pages/dashboard.top_products.heading') }}
        </h3>
        <x-shopper::link
            :href="route('shopper.products.index')"
            wire:navigate
            class="inline-flex items-center gap-1 text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
        >
            {{ __('shopper::pages/dashboard.top_products.view_all') }}
            <x-untitledui-arrow-narrow-right class="size-3.5" />
        </x-shopper::link>
    </div>

    <div class="border-t border-gray-200 dark:border-white/10">
        @if ($this->products->isNotEmpty())
            <table class="fi-ta-table w-full table-fixed divide-y divide-gray-200 text-start dark:divide-white/10">
                <thead>
                    <tr>
                        <th class="fi-ta-header-cell px-3 py-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="fi-ta-header-cell-label truncate text-sm font-semibold text-gray-950 dark:text-white">
                                {{ __('shopper::pages/dashboard.top_products.product') }}
                            </span>
                        </th>
                        <th class="fi-ta-header-cell w-24 px-3 py-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                {{ __('shopper::pages/dashboard.top_products.reviews') }}
                            </span>
                        </th>
                        <th class="fi-ta-header-cell w-16 px-3 py-2 text-right sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                {{ __('shopper::pages/dashboard.top_products.sales') }}
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/10">
                    @foreach ($this->products as $item)
                        <tr>
                            <td class="fi-ta-cell overflow-hidden p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="flex min-w-0 items-center gap-3 px-3 py-2">
                                    <img
                                        class="size-8 shrink-0 rounded-lg object-cover ring-1 ring-gray-100 dark:ring-white/10"
                                        src="{{ $item['product']?->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection')) }}"
                                        alt="{{ $item['product']?->name }}"
                                    />
                                    <span class="truncate text-sm text-gray-950 dark:text-white">
                                        {{ $item['product']?->name ?? '—' }}
                                    </span>
                                </div>
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="px-3 py-2">
                                    @if ($item['reviews_count'] > 0)
                                        <div class="flex items-center gap-1.5">
                                            <x-phosphor-star class="size-4 text-amber-400" aria-hidden="true" />
                                            <span class="text-sm font-medium tabular-nums text-gray-700 dark:text-gray-300">
                                                {{ $item['average_rating'] }}
                                            </span>
                                            <span class="text-sm text-gray-400 dark:text-gray-500">
                                                ({{ $item['reviews_count'] }})
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </div>
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="px-3 py-2 text-right">
                                    <span class="text-sm font-medium tabular-nums text-gray-700 dark:text-gray-300">
                                        {{ number_format($item['sales']) }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-5 text-center">
                <x-untitledui-package class="mx-auto size-8 text-gray-400 dark:text-gray-500" aria-hidden="true" />
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('shopper::pages/dashboard.top_products.empty') }}
                </p>
            </div>
        @endif
    </div>
</x-shopper::card>
