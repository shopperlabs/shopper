@php
    $items = $this->items;
    $count = $items->count();
    $exceptIds = $items->pluck('id')->all();
    $isProducts = $this->isProducts();
    $exceptIdsJson = json_encode($exceptIds);
    $pickerComponent = $this->pickerComponent();
    $label = $isProducts
        ? __('shopper::pages/discounts.select_products')
        : __('shopper::pages/discounts.select_customers');
@endphp

<div class="space-y-3">
    <div class="flex items-center justify-between gap-3">
        <span class="text-sh-fg text-sm font-medium">
            {{ $label }}
            @if ($count > 1)
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
            @if ($isProducts)
                {{ __('shopper::pages/discounts.products_picker.button') }}
            @else
                {{ __('shopper::pages/discounts.customers_picker.button') }}
            @endif
        </x-filament::button>
    </div>

    @if ($items->isEmpty())
        <div class="border-sh-border bg-sh-surface flex items-center justify-center rounded-lg border border-dashed px-4 py-8">
            <p class="text-sh-fg-muted text-sm">
                @if ($isProducts)
                    {{ __('shopper::pages/discounts.products_picker.empty_field') }}
                @else
                    {{ __('shopper::pages/discounts.customers_picker.empty_field') }}
                @endif
            </p>
        </div>
    @else
        <x-shopper::card role="list" class="[&>div.sh-card-content]:p-0 [&>div.sh-card-content]:overflow-hidden">
            <div class="divide-sh-border max-h-80 divide-y overflow-y-auto">
                @foreach ($items as $item)
                    <li wire:key="discount-{{ $this->type }}-{{ $item->id }}" class="flex items-center gap-3 px-4 py-3">
                        @if ($isProducts)
                            <img
                                src="{{ $item->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection')) ?: shopper_fallback_url() }}"
                                alt="{{ $item->name }}"
                                class="size-10 shrink-0 rounded-md object-cover"
                            />
                            <div class="min-w-0 flex-1">
                                <p class="text-sh-fg truncate text-sm font-medium">{{ $item->name }}</p>
                                @if ($item->sku)
                                    <p class="text-sh-fg-secondary truncate text-xs">{{ $item->sku }}</p>
                                @endif
                            </div>
                        @else
                            <img
                                src="{{ $item->picture }}"
                                alt="{{ $item->full_name }}"
                                class="size-10 shrink-0 rounded-full object-cover"
                            />
                            <div class="min-w-0 flex-1">
                                <p class="text-sh-fg truncate text-sm font-medium">{{ $item->full_name }}</p>
                                <p class="text-sh-fg-secondary truncate text-xs">{{ $item->email }}</p>
                            </div>
                        @endif

                        <button
                            type="button"
                            wire:click="removeItem({{ $item->id }})"
                            class="text-sh-fg-secondary hover:text-danger-600 dark:hover:text-danger-400 shrink-0"
                            aria-label="{{ __('shopper::forms.actions.remove') }}"
                        >
                            <x-untitledui-trash-03 class="size-4" stroke-width="2" aria-hidden="true" />
                        </button>
                    </li>
                @endforeach
            </div>
        </x-shopper::card>
    @endif
</div>
