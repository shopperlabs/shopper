<x-shopper::form-slider-over
    action="generate"
    :title="__('shopper::pages/products.variants.generate')"
    :description="__('shopper::pages/products.variants.generate_description')"
>
    @if (count($availableOptions))
        <x-shopper::card class="overflow-hidden">
            <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                <thead>
                    <tr>
                        <th class="fi-ta-header-cell p-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                {{ __('shopper::pages/attributes.menu') }}
                            </span>
                        </th>
                        <th class="fi-ta-header-cell p-2 sm:first-of-type:ps-6 sm:last-of-type:pe-6"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/10">
                    @foreach (collect($availableOptions) as $attribute)
                        <tr>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="grid w-full gap-y-1 p-2">
                                    <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white">
                                        {{ $attribute['name'] }}
                                    </span>
                                </div>
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
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
        <div class="mt-10 border-t border-gray-200 pt-6 dark:border-white/10">
            <h4 class="font-heading text-xl font-semibold text-gray-900 dark:text-white">
                {{ __('shopper::pages/products.variants.title') }}
            </h4>
            <div class="mt-5 space-y-4">
                @foreach ($variants as $index => $variant)
                    <x-shopper::card
                        class="divide-y divide-gray-200 dark:divide-white/10"
                        x-data="{ expanded: true }"
                        wire:key="variant_{{ $variant['key'] }}"
                    >
                        <div class="flex items-center justify-between gap-4 px-3 py-2">
                            <button
                                @click="expanded = ! expanded"
                                type="button"
                                class="flex h-fit w-full flex-1 items-center gap-2 text-sm/5 font-medium text-gray-700 dark:text-gray-300"
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
                                >
                                    <x-untitledui-trash-03 class="size-5" aria-hidden="true" />
                                </button>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-2 gap-5 px-4 py-3 lg:grid-cols-4 lg:gap-6"
                            x-show="expanded"
                            x-collapse
                        >
                            <input type="hidden" wire:model="variants.{{ $index }}.values" />
                            <input type="hidden" wire:model="variants.{{ $index }}.variant_id" />
                            <div class="space-y-2">
                                <x-shopper::label :isRequired="true">
                                    {{ __('shopper::forms.label.name') }}
                                </x-shopper::label>
                                <x-filament::input.wrapper>
                                    <x-filament::input type="text" wire:model="variants.{{ $index }}.name" required />
                                </x-filament::input.wrapper>
                            </div>
                            <div class="space-y-2">
                                <x-shopper::label>
                                    {{ __('shopper::forms.label.sku') }}
                                </x-shopper::label>
                                <x-filament::input.wrapper>
                                    <x-filament::input type="text" wire:model="variants.{{ $index }}.sku" />
                                </x-filament::input.wrapper>
                            </div>
                            <div class="space-y-2">
                                <x-shopper::label>
                                    {{ __('shopper::forms.label.price') }}
                                </x-shopper::label>
                                <x-filament::input.wrapper :suffix="shopper_currency()">
                                    <x-filament::input type="text" wire:model="variants.{{ $index }}.price" />
                                </x-filament::input.wrapper>
                            </div>
                            <div class="space-y-2">
                                <x-shopper::label>
                                    {{ __('shopper::forms.label.stock_number_value') }}
                                </x-shopper::label>
                                <x-filament::input.wrapper>
                                    <x-filament::input type="number" wire:model="variants.{{ $index }}.stock" />
                                </x-filament::input.wrapper>
                            </div>
                        </div>
                    </x-shopper::card>
                @endforeach
            </div>
        </div>
    @endif
</x-shopper::form-slider-over>
