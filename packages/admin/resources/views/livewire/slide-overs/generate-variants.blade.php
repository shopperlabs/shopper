<x-shopper::form-slider-over
    action="generate"
    :title="__('shopper::pages/products.variants.generate')"
    :description="__('shopper::pages/products.variants.generate_description')"
>
    @if (count($availableOptions))
        <x-shopper::card class="overflow-hidden [&>div:first-of-type]:p-0">
            <table class="fi-ta-table divide-sh-border w-full table-auto divide-y text-start">
                <thead>
                    <tr>
                        <th class="fi-ta-header-cell p-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="fi-ta-header-cell-label text-sh-fg text-sm font-semibold">
                                {{ __('shopper::pages/attributes.menu') }}
                            </span>
                        </th>
                        <th class="fi-ta-header-cell p-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6"></th>
                    </tr>
                </thead>
                <tbody class="divide-sh-border divide-y whitespace-nowrap">
                    @foreach (collect($availableOptions) as $attribute)
                        <tr>
                            <td
                                class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3"
                            >
                                <div class="grid w-full gap-y-1 p-2">
                                    <span class="fi-ta-text-item-label text-sh-fg text-sm leading-6">
                                        {{ $attribute['name'] }}
                                    </span>
                                </div>
                            </td>
                            <td
                                class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3"
                            >
                                <div class="flex w-full flex-wrap items-center gap-3 gap-y-1 p-2">
                                    @foreach (collect($attribute['values']) as $option)
                                        <x-filament::badge color="gray">
                                            {{ $option['value'] }}
                                        </x-filament::badge>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-shopper::card>
    @endif

    @if (count($variants))
        <div class="border-sh-border mt-10 border-t pt-6">
            <h4 class="font-heading text-sh-fg text-xl font-semibold">
                {{ __('shopper::pages/products.variants.title') }}
            </h4>
            <div class="mt-5 space-y-4">
                @foreach ($variants as $index => $variant)
                    <x-shopper::card
                        class="divide-sh-border divide-y"
                        x-data="{ expanded: true }"
                        wire:key="variant_{{ $variant['key'] }}"
                    >
                        <div class="flex items-center justify-between gap-4 py-2">
                            <button
                                @click="expanded = ! expanded"
                                :aria-expanded="expanded"
                                type="button"
                                class="text-sh-fg-secondary flex h-fit w-full flex-1 items-center gap-2 text-sm/5 font-medium"
                            >
                                <x-phosphor-swatches-duotone class="size-5" aria-hidden="true" />
                                {{ $variant['name'] }}

                                @if (! $variant['variant_id'])
                                    <x-filament::badge color="info" size="sm">
                                        {{ __('shopper-core::status.new') }}
                                    </x-filament::badge>
                                @endif
                            </button>
                            <div class="flex items-center gap-2">
                                <x-shopper::loader
                                    wire:loading
                                    wire:target="removeVariant('{{ $index }}')"
                                    class="text-primary-500"
                                />
                                <button
                                    class="text-danger-500 hover:text-danger-700 dark:hover:text-danger-400 inline-flex items-center text-sm"
                                    type="button"
                                    wire:click="removeVariant('{{ $index }}')"
                                    aria-label="{{ __('shopper::forms.actions.delete_label', ['label' => $variant['name']]) }}"
                                >
                                    <x-untitledui-trash-03 class="size-5" aria-hidden="true" />
                                </button>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-2 gap-5 py-2 lg:grid-cols-4 lg:gap-6"
                            x-show="expanded"
                            x-collapse
                        >
                            <input type="hidden" wire:model="variants.{{ $index }}.values" />
                            <input type="hidden" wire:model="variants.{{ $index }}.variant_id" />
                            <div class="space-y-2">
                                <x-shopper::label for="variant-{{ $variant['key'] }}-name" :isRequired="true">
                                    {{ __('shopper::forms.label.name') }}
                                </x-shopper::label>
                                <x-filament::input.wrapper :valid="! $errors->has('variants.'.$index.'.name')">
                                    <x-filament::input
                                        id="variant-{{ $variant['key'] }}-name"
                                        type="text"
                                        wire:model="variants.{{ $index }}.name"
                                        required
                                        :aria-invalid="$errors->has('variants.'.$index.'.name') ? 'true' : null"
                                        :aria-describedby="$errors->has('variants.'.$index.'.name') ? 'variant-'.$variant['key'].'-name-error' : null"
                                    />
                                </x-filament::input.wrapper>
                                @error('variants.' . $index . '.name')
                                    <p
                                        id="variant-{{ $variant['key'] }}-name-error"
                                        class="text-danger-500 mt-1 text-sm"
                                        x-init="expanded = true"
                                    >
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <x-shopper::label for="variant-{{ $variant['key'] }}-sku">
                                    {{ __('shopper::forms.label.sku') }}
                                </x-shopper::label>
                                <x-filament::input.wrapper :valid="! $errors->has('variants.'.$index.'.sku')">
                                    <x-filament::input
                                        id="variant-{{ $variant['key'] }}-sku"
                                        type="text"
                                        wire:model="variants.{{ $index }}.sku"
                                        :aria-invalid="$errors->has('variants.'.$index.'.sku') ? 'true' : null"
                                        :aria-describedby="$errors->has('variants.'.$index.'.sku') ? 'variant-'.$variant['key'].'-sku-error' : null"
                                    />
                                </x-filament::input.wrapper>
                                @error('variants.' . $index . '.sku')
                                    <p
                                        id="variant-{{ $variant['key'] }}-sku-error"
                                        class="text-danger-500 mt-1 text-sm"
                                        x-init="expanded = true"
                                    >
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <x-shopper::label for="variant-{{ $variant['key'] }}-price">
                                    {{ __('shopper::forms.label.price') }}
                                </x-shopper::label>
                                <x-filament::input.wrapper
                                    :suffix="shopper_currency()"
                                    :valid="! $errors->has('variants.'.$index.'.price')"
                                >
                                    <x-filament::input
                                        id="variant-{{ $variant['key'] }}-price"
                                        type="text"
                                        wire:model="variants.{{ $index }}.price"
                                        :aria-invalid="$errors->has('variants.'.$index.'.price') ? 'true' : null"
                                        :aria-describedby="$errors->has('variants.'.$index.'.price') ? 'variant-'.$variant['key'].'-price-error' : null"
                                    />
                                </x-filament::input.wrapper>
                                @error('variants.' . $index . '.price')
                                    <p
                                        id="variant-{{ $variant['key'] }}-price-error"
                                        class="text-danger-500 mt-1 text-sm"
                                        x-init="expanded = true"
                                    >
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <x-shopper::label for="variant-{{ $variant['key'] }}-stock">
                                    {{ __('shopper::forms.label.stock_number_value') }}
                                </x-shopper::label>
                                <x-filament::input.wrapper
                                    :disabled="filled($variant['variant_id'])"
                                    :valid="! $errors->has('variants.'.$index.'.stock')"
                                >
                                    <x-filament::input
                                        id="variant-{{ $variant['key'] }}-stock"
                                        type="number"
                                        wire:model="variants.{{ $index }}.stock"
                                        :disabled="filled($variant['variant_id'])"
                                        :aria-invalid="$errors->has('variants.'.$index.'.stock') ? 'true' : null"
                                        :aria-describedby="$errors->has('variants.'.$index.'.stock') ? 'variant-'.$variant['key'].'-stock-error' : (filled($variant['variant_id']) ? 'variant-'.$variant['key'].'-stock-hint' : null)"
                                    />
                                </x-filament::input.wrapper>
                                @error('variants.' . $index . '.stock')
                                    <p
                                        id="variant-{{ $variant['key'] }}-stock-error"
                                        class="text-danger-500 mt-1 text-sm"
                                        x-init="expanded = true"
                                    >
                                        {{ $message }}
                                    </p>
                                @enderror

                                @if (filled($variant['variant_id']))
                                    <p
                                        id="variant-{{ $variant['key'] }}-stock-hint"
                                        class="text-sh-fg-secondary text-sm"
                                    >
                                        {{ __('shopper::pages/products.variants.stock_managed_per_location') }}
                                    </p>
                                @endif
                            </div>
                            @error('variants.' . $index . '.values.*')
                                <p class="text-danger-500 col-span-full text-sm" x-init="expanded = true">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </x-shopper::card>
                @endforeach
            </div>
        </div>
    @endif
</x-shopper::form-slider-over>
