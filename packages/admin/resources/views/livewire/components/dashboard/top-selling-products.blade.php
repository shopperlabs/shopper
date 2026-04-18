<x-shopper::card class="[&>div:first-of-type]:p-0">
    <div class="flex items-center justify-between p-4">
        <h3 class="font-heading text-sh-fg text-base font-semibold">
            {{ __('shopper::pages/dashboard.top_products.heading') }}
        </h3>
        <x-shopper::link
            :href="route('shopper.products.index')"
            wire:navigate
            class="text-sh-fg-muted hover:text-sh-fg inline-flex items-center gap-1 text-sm font-medium transition-colors"
        >
            {{ __('shopper::pages/dashboard.top_products.view_all') }}
            <x-untitledui-arrow-narrow-right class="size-3.5" />
        </x-shopper::link>
    </div>

    <div class="border-sh-border border-t">
        @if ($this->products->isNotEmpty())
            <table class="fi-ta-table divide-sh-border w-full table-fixed divide-y text-start">
                <thead>
                    <tr>
                        <th class="fi-ta-header-cell px-3 py-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="fi-ta-header-cell-label text-sh-fg truncate text-sm font-semibold">
                                {{ __('shopper::pages/dashboard.top_products.product') }}
                            </span>
                        </th>
                        <th class="fi-ta-header-cell w-24 px-3 py-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="fi-ta-header-cell-label text-sh-fg text-sm font-semibold">
                                {{ __('shopper::pages/dashboard.top_products.reviews') }}
                            </span>
                        </th>
                        <th class="fi-ta-header-cell w-16 px-3 py-2 text-right sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="fi-ta-header-cell-label text-sh-fg text-sm font-semibold">
                                {{ __('shopper::pages/dashboard.top_products.sales') }}
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-sh-border divide-y whitespace-nowrap">
                    @foreach ($this->products as $item)
                        <tr>
                            <td class="fi-ta-cell overflow-hidden p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="flex min-w-0 items-center gap-3 px-3 py-2">
                                    <img
                                        class="ring-sh-border size-8 shrink-0 rounded-lg object-cover ring-1"
                                        src="{{ $item['product']?->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection')) }}"
                                        alt="{{ $item['product']?->name }}"
                                    />
                                    <span class="text-sh-fg truncate text-sm">
                                        {{ $item['product']?->name ?? '—' }}
                                    </span>
                                </div>
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="px-3 py-2">
                                    @if ($item['reviews_count'] > 0)
                                        <div class="flex items-center gap-1.5">
                                            <x-phosphor-star class="size-4 text-amber-400" aria-hidden="true" />
                                            <span class="text-sh-fg-secondary text-sm font-medium tabular-nums">
                                                {{ $item['average_rating'] }}
                                            </span>
                                            <span class="text-sh-fg-muted text-sm">
                                                ({{ $item['reviews_count'] }})
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-sh-fg-muted text-sm">—</span>
                                    @endif
                                </div>
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="px-3 py-2 text-right">
                                    <span class="text-sh-fg-secondary text-sm font-medium tabular-nums">
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
                <x-untitledui-package class="text-sh-fg-muted mx-auto size-8" aria-hidden="true" />
                <p class="text-sh-fg-muted mt-2 text-sm">
                    {{ __('shopper::pages/dashboard.top_products.empty') }}
                </p>
            </div>
        @endif
    </div>
</x-shopper::card>
