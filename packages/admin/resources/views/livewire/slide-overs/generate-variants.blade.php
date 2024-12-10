<x-shopper::form-slider-over
    action="generate"
    :title="__('shopper::pages/products.variants.generate')"
    :description="__('shopper::pages/products.variants.generate_description')"
>
    @if(count($availableOptions))
        <x-shopper::card class="overflow-hidden">
            <x-filament-tables::table>
                <x-slot name="header">
                    <x-filament-tables::header-cell class="lg:py-3">
                          <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                            {{ __('shopper::pages/attributes.menu') }}
                          </span>
                    </x-filament-tables::header-cell>
                    <x-filament-tables::header-cell class="lg:py-3" />
                </x-slot>

                @foreach(collect($availableOptions) as $attribute)
                    <x-filament-tables::row>
                        <x-filament-tables::cell>
                            <div class="grid w-full gap-y-1 py-2 px-3">
                                <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white">
                                    {{ $attribute['name'] }}
                                </span>
                            </div>
                        </x-filament-tables::cell>
                        <x-filament-tables::cell>
                            <div class="flex items-center flex-wrap gap-3 w-full gap-y-1 py-2 px-3">
                                @foreach(collect($attribute['values']) as $option)
                                    <x-filament::badge color="gray">{{ $option['value'] }}</x-filament::badge>
                                @endforeach
                            </div>
                        </x-filament-tables::cell>
                    </x-filament-tables::row>
                @endforeach
            </x-filament-tables::table>
        </x-shopper::card>
    @endif

    @if(count($variants))
        <div class="mt-10 border-t pt-6 border-gray-200 dark:border-white/10">
            <h4 class="font-heading font-semibold text-xl text-gray-900 dark:text-white">
                {{ __('shopper::pages/products.variants.title') }}
            </h4>
            <div class="mt-5 space-y-4">
                @foreach($variants as $index => $variant)
                    <x-shopper::card class="divide-y divide-gray-200 dark:divide-white/10" x-data="{ expanded: true }" wire:key="variant_{{ $variant['key'] }}">
                        <div class="flex items-center justify-between gap-4 py-2 px-3">
                            <button @click="expanded = ! expanded" type="button" class="flex items-center w-full h-fit flex-1 gap-2 text-sm/5 font-medium text-gray-700 dark:text-gray-300">
                                <x-phosphor-swatches-duotone class="size-5" aria-hidden="true" />
                                {{ $variant['name'] }}

                                @if(! $variant['variant_id'])
                                    <x-filament::badge color="info" size="sm">{{ __('shopper-core::status.new') }}</x-filament::badge>
                                @endif
                            </button>
                            <div class="flex items-center gap-2">
                                <x-shopper::loader wire:loading wire:target="removeVariant('{{ $index }}')" class="text-primary-500" />
                                <button
                                    class="inline-flex items-center text-sm text-danger-500 hover:text-danger-700 dark:hover:text-danger-400"
                                    type="button"
                                    wire:click="removeVariant('{{ $index }}')"
                                >
                                    <x-untitledui-trash-03 class="size-5" aria-hidden="true" />
                                </button>
                            </div>
                        </div>

                        <div class="px-4 py-3 grid grid-cols-2 gap-5 lg:grid-cols-4 lg:gap-6" x-show="expanded" x-collapse>
                            <input type="hidden" wire:model="variants.{{ $index }}.values" />
                            <input type="hidden" wire:model="variants.{{ $index }}.variant_id" />
                            <div class="space-y-2">
                                <x-filament-forms::field-wrapper.label :required="true">
                                    {{ __('shopper::forms.label.name') }}
                                </x-filament-forms::field-wrapper.label>
                                <x-filament::input.wrapper>
                                    <x-filament::input
                                        type="text"
                                        wire:model="variants.{{ $index }}.name"
                                        required
                                    />
                                </x-filament::input.wrapper>
                            </div>
                            <div class="space-y-2">
                                <x-filament-forms::field-wrapper.label>
                                    {{ __('shopper::forms.label.sku') }}
                                </x-filament-forms::field-wrapper.label>
                                <x-filament::input.wrapper>
                                    <x-filament::input
                                        type="text"
                                        wire:model="variants.{{ $index }}.sku"
                                    />
                                </x-filament::input.wrapper>
                            </div>
                            <div class="space-y-2">
                                <x-filament-forms::field-wrapper.label>
                                    {{ __('shopper::forms.label.price') }}
                                </x-filament-forms::field-wrapper.label>
                                <x-filament::input.wrapper :suffix="shopper_currency()">
                                    <x-filament::input
                                        type="text"
                                        wire:model="variants.{{ $index }}.price"
                                    />
                                </x-filament::input.wrapper>
                            </div>
                            <div class="space-y-2">
                                <x-filament-forms::field-wrapper.label>
                                    {{ __('shopper::forms.label.stock_number_value') }}
                                </x-filament-forms::field-wrapper.label>
                                <x-filament::input.wrapper>
                                    <x-filament::input
                                        type="number"
                                        wire:model="variants.{{ $index }}.stock"
                                    />
                                </x-filament::input.wrapper>
                            </div>
                        </div>
                    </x-shopper::card>
                @endforeach
            </div>
        </div>
    @endif
</x-shopper::form-slider-over>
