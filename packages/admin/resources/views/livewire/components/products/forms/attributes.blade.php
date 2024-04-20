<x-shopper::container class="space-y-8">
    <div>
        <x-filament::section.heading>
            {{ __('shopper::words.attributes') }}
        </x-filament::section.heading>
        <x-filament::section.description class="mt-1 max-w-2xl">
            {{ __('shopper::pages/attributes.description') }}
        </x-filament::section.description>
    </div>

    @if ($productAttributes->isNotEmpty())
        <div>
            @if ($productAttributes['choice']->isNotEmpty())
                <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 lg:gap-x-8">
                    @foreach ($productAttributes['choice'] as $attribute)
                        @if ($attribute->hasMultipleValues())
                            <livewire:shopper-products.attributes.multiple-choice
                                wire:key="attribute-multiple-{{ $attribute->id }}"
                                :activated="$currentAttributes->contains($attribute->id)"
                                :product="$product"
                                :attribute="$attribute"
                            />
                        @endif

                        @if ($attribute->hasSingleValue())
                            <livewire:shopper-products.attributes.single-choice
                                wire:key="attribute-single-{{ $attribute->id }}"
                                :activated="$currentAttributes->contains($attribute->id)"
                                :product="$product"
                                :attribute="$attribute"
                            />
                        @endif
                    @endforeach
                </div>
            @endif

            @if ($productAttributes['text']->isNotEmpty())
                <x-shopper::separator />

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-x-8">
                    @foreach ($productAttributes['text'] as $productAttribute)
                        <div
                            @class([
                                'lg:col-span-3' => $productAttribute->type === \Shopper\Core\Enum\FieldType::RICHTEXT,
                            ])
                        >
                            <livewire:shopper-products.attributes.text
                                wire:key="attribute-text-{{ $productAttribute->id }}"
                                :activated="$currentAttributes->contains($productAttribute->id)"
                                :product="$product"
                                :attribute="$productAttribute"
                            />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <x-shopper::card class="flex items-center justify-center p-6 lg:py-12">
            <div class="flex flex-col items-center justify-center">
                <div class="flex items-center justify-center rounded-full bg-gray-100 p-3 dark:bg-gray-500/20">
                    <x-untitledui-file-05
                        class="h-6 w-6 text-gray-500 dark:text-gray-400"
                        stroke-width="1.5"
                        aria-hidden="true"
                    />
                </div>
                <h3 class="mt-2 font-heading text-base font-semibold text-gray-900 dark:text-white">
                    {{ __('shopper::pages/products.attributes.empty_title') }}
                </h3>
                <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                    {{ __('shopper::pages/products.attributes.empty_values') }}
                </p>
                <x-shopper::buttons.primary
                    wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.attribute-form' })"
                    type="button"
                    class="mt-4"
                >
                    {{ __('shopper::layout.forms.actions.create') }}
                </x-shopper::buttons.primary>
            </div>
        </x-shopper::card>
    @endif
</x-shopper::container>
