@php
    $preview = $schemaComponent->getLivewire()->preview;
@endphp

<div class="space-y-4">
    <ul class="flex flex-wrap items-center gap-x-6 gap-y-2">
        <li class="text-sh-fg-muted flex items-center gap-2 text-sm">
            <x-phosphor-tag-duotone class="text-sh-fg-muted size-5" aria-hidden="true" />
            <span class="text-sh-fg font-semibold">{{ $preview['total_products'] ?? 0 }}</span>
            {{ __('shopper::pages/products.import.review.products') }}
        </li>
        <li class="text-sh-fg-muted flex items-center gap-2 text-sm">
            <x-phosphor-swatches-duotone class="text-sh-fg-muted size-5" aria-hidden="true" />
            <span class="text-sh-fg font-semibold">{{ $preview['total_variants'] ?? 0 }}</span>
            {{ __('shopper::pages/products.import.review.variants') }}
        </li>
        <li class="text-sh-fg-muted flex items-center gap-2 text-sm">
            <x-phosphor-package-duotone class="text-sh-fg-muted size-5" aria-hidden="true" />
            <span class="text-sh-fg font-semibold">{{ $preview['total_stock'] ?? 0 }}</span>
            {{ __('shopper::pages/products.import.review.stock') }}
        </li>
    </ul>

    @if (($preview['unnamed'] ?? 0) > 0)
        <div
            class="bg-warning-50 text-warning-700 dark:bg-warning-400/10 dark:text-warning-400 flex items-center gap-2 rounded-lg p-3 text-sm"
        >
            <x-untitledui-alert-triangle class="size-4 shrink-0" aria-hidden="true" />
            {{ trans_choice('shopper::pages/products.import.review.unnamed', $preview['unnamed'], ['count' => $preview['unnamed']]) }}
        </div>
    @endif

    <div class="border-sh-border max-h-96 overflow-y-auto rounded-xl border">
        <table class="fi-ta-table divide-sh-border w-full table-auto divide-y text-start">
            <thead class="bg-sh-surface sticky top-0 z-10">
                <tr>
                    <th class="fi-ta-header-cell px-3 py-2 text-start sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                        <span class="fi-ta-header-cell-label text-sh-fg text-sm font-semibold">
                            {{ __('shopper::forms.label.name') }}
                        </span>
                    </th>
                    <th class="fi-ta-header-cell px-3 py-2 text-start sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                        <span class="fi-ta-header-cell-label text-sh-fg text-sm font-semibold">
                            {{ __('shopper::forms.label.brand') }}
                        </span>
                    </th>
                    <th class="fi-ta-header-cell px-3 py-2 text-end sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                        <span class="fi-ta-header-cell-label text-sh-fg text-sm font-semibold">
                            {{ __('shopper::forms.label.price') }}
                        </span>
                    </th>
                    <th class="fi-ta-header-cell px-3 py-2 text-end sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                        <span class="fi-ta-header-cell-label text-sh-fg text-sm font-semibold">
                            {{ __('shopper::pages/products.import.review.variants') }}
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-sh-border divide-y whitespace-nowrap">
                @foreach ($preview['products'] ?? [] as $product)
                    <tr>
                        <td
                            class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3"
                        >
                            <div class="px-3 py-2">
                                <span class="text-sh-fg text-sm font-medium">
                                    {{ $product['name'] }}
                                </span>
                            </div>
                        </td>
                        <td
                            class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3"
                        >
                            <div class="px-3 py-2">
                                <span class="text-sh-fg-muted text-sm">
                                    {{ $product['brand'] ?? '—' }}
                                </span>
                            </div>
                        </td>
                        <td
                            class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3"
                        >
                            <div class="px-3 py-2 text-end">
                                <span class="text-sh-fg-secondary text-sm font-medium tabular-nums">
                                    {{ $product['price'] !== null ? shopper_money_format((int) round($product['price'] * 100)) : '—' }}
                                </span>
                            </div>
                        </td>
                        <td
                            class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3"
                        >
                            <div class="px-3 py-2 text-end">
                                <span class="text-sh-fg-muted text-sm tabular-nums">
                                    {{ $product['variants_count'] }}
                                </span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if (($preview['total_products'] ?? 0) > count($preview['products'] ?? []))
        <p class="text-sh-fg-muted text-sm">
            {{ __('shopper::pages/products.import.review.more', ['count' => $preview['total_products'] - count($preview['products'] ?? [])]) }}
        </p>
    @endif
</div>
