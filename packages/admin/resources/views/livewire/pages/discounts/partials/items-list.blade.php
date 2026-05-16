@php
    $isProducts = $type === 'products';
    $items = $isProducts ? $this->selectedProducts : $this->selectedCustomers;
    $count = $items->count();
    $limit = $this->getItemsInlineLimit();
    $showAll = $isProducts ? $this->showAllProducts : $this->showAllCustomers;
    $displayed = $showAll ? $items : $items->take($limit);
    $hidden = max(0, $count - $displayed->count());
    $toggleShowAll = $isProducts ? 'toggleShowAllProducts' : 'toggleShowAllCustomers';
    $exceptIdsJson = json_encode($items->pluck('id')->all());
    $pickerComponent = $isProducts
        ? 'shopper-slide-overs.discount-products-picker'
        : 'shopper-slide-overs.discount-customers-picker';
    $label = $isProducts
        ? __('shopper::pages/discounts.select_products')
        : __('shopper::pages/discounts.select_customers');
    $addLabel = $isProducts
        ? __('shopper::pages/discounts.products_picker.button')
        : __('shopper::pages/discounts.customers_picker.button');
    $emptyLabel = $isProducts
        ? __('shopper::pages/discounts.products_picker.empty_field')
        : __('shopper::pages/discounts.customers_picker.empty_field');
    $removeMethod = $isProducts ? 'removeProductFromDiscount' : 'removeCustomerFromDiscount';
    $thumbnailRadius = $isProducts ? 'rounded-sm' : 'rounded-full';
@endphp

<div class="min-w-0 space-y-2">
    <div class="flex items-center justify-between gap-3">
        <span class="text-sh-fg text-sm font-medium">
            {{ $label }}
            @if ($count > 0)
                <span class="text-sh-fg-secondary font-normal">({{ $count }})</span>
            @endif
        </span>
        <x-filament::button
            type="button"
            size="sm"
            color="gray"
            icon="untitledui-plus"
            wire:click="$dispatch('openPanel', { component: '{{ $pickerComponent }}', arguments: { exceptIds: {{ $exceptIdsJson }} } })"
        >
            {{ $addLabel }}
        </x-filament::button>
    </div>

    @if ($items->isEmpty())
        <div class="border-sh-border bg-sh-surface flex items-center justify-center rounded-lg border border-dashed px-4 py-6">
            <p class="text-sh-fg-muted text-sm">
                {{ $emptyLabel }}
            </p>
        </div>
    @else
        <ul role="list" class="mt-3 flex flex-wrap gap-1.5">
            @foreach ($displayed as $item)
                @php
                    $thumbnail = $isProducts
                        ? ($item->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection')) ?: shopper_fallback_url())
                        : $item->picture;
                    $name = $isProducts ? $item->name : $item->full_name;
                @endphp
                <li
                    wire:key="discount-{{ $type }}-{{ $item->id }}"
                    class="bg-sh-surface ring-sh-border inline-flex min-w-0 max-w-56 items-center gap-1.5 rounded-full py-1 pl-1 pr-1.5 ring-1"
                >
                    <img
                        src="{{ $thumbnail }}"
                        alt=""
                        class="size-5 shrink-0 {{ $thumbnailRadius }} object-cover"
                        aria-hidden="true"
                    />
                    <span class="text-sh-fg min-w-0 flex-1 truncate text-xs font-medium" title="{{ $name }}">
                        {{ $name }}
                    </span>
                    <button
                        type="button"
                        wire:click="{{ $removeMethod }}({{ $item->id }})"
                        class="text-sh-fg-secondary hover:text-danger-600 dark:hover:text-danger-400 shrink-0 rounded-full p-0.5"
                        aria-label="{{ __('shopper::forms.actions.remove') }} {{ $name }}"
                    >
                        <x-untitledui-x-close class="size-3" stroke-width="2.5" aria-hidden="true" />
                    </button>
                </li>
            @endforeach

            @if ($hidden > 0 || ($showAll && $count > $limit))
                <li wire:key="discount-{{ $type }}-show-all">
                    <button
                        type="button"
                        wire:click="{{ $toggleShowAll }}"
                        class="bg-sh-surface ring-sh-border text-sh-fg-secondary hover:text-sh-fg inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium ring-1"
                    >
                        @if ($showAll)
                            {{ __('shopper::words.show_less') }}
                        @else
                            + {{ __('shopper::words.number_more', ['number' => $hidden]) }}
                        @endif
                    </button>
                </li>
            @endif
        </ul>
    @endif
</div>
