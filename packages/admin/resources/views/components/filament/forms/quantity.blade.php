@php
    $getHeading = $getHeading();
    $getStatePath = $getStatePath();
    $getState = $getState();

    $hasInlineLabel = $hasInlineLabel();
    $extraAlpineAttributes = $getExtraAlpineAttributes();
    $id = $getId();
    $isDisabled = $isDisabled();
    $getMaxValue = $getMaxValue();
    $getMinValue = $getMinValue();

    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $prefixActions = $getPrefixActions();
    $prefixIcon = $getPrefixIcon();
    $prefixLabel = $getPrefixLabel();
    $suffixActions = $getSuffixActions();
    $suffixIcon = $getSuffixIcon();
    $suffixLabel = $getSuffixLabel();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field" :has-inline-label="$hasInlineLabel">
    <x-slot name="label" @class([
        'sm:pt-1.5' => $hasInlineLabel,
    ])>
        {{ $getLabel() }}
    </x-slot>

    <div
        x-data="{
            state: $wire.$entangle('{{ $getStatePath }}'),
            maxValue: {{ $getMaxValue ?? '0' }},
            minValue: {{ $getMinValue ?? '0' }},
            isDecrementAllowed: true,
            isIncrementAllowed: true,

            increment() {
                if (this.state < this.maxValue && this.state >= this.minValue) {
                    this.state++
                    $wire.$refresh()
                    if (this.state === this.maxValue) {
                        this.isIncrementAllowed = false
                    } else {
                        this.isIncrementAllowed = true
                        this.isDecrementAllowed = true
                    }
                }
            },

            decrement() {
                if (
                    this.state > 0 &&
                    this.state <= this.maxValue &&
                    this.state > this.minValue
                ) {
                    this.state--
                    $wire.$refresh()
                    if (this.state === this.minValue) {
                        this.isDecrementAllowed = false
                    } else {
                        this.isIncrementAllowed = true
                        this.isDecrementAllowed = true
                    }
                }
            },

            init: function () {
                this.isIncrementAllowed = true
                this.isDecrementAllowed = true
            },
        }"
    >
        <x-filament::input.wrapper
            :disabled="$isDisabled"
            :inline-prefix="$isPrefixInline"
            :inline-suffix="$isSuffixInline"
            :prefix="$prefixLabel"
            :prefix-actions="$prefixActions"
            :prefix-icon="$prefixIcon"
            :prefix-icon-color="$getPrefixIconColor()"
            :suffix="$suffixLabel"
            :suffix-actions="$suffixActions"
            :suffix-icon="$suffixIcon"
            :suffix-icon-color="$getSuffixIconColor()"
            :valid="! $errors->has($getStatePath)"
        >
            <div class="flex w-full items-center justify-between gap-x-5">
                <div class="grow">
                    @if ($getHeading !== null)
                        <label for="{{ $id }}" class="block px-2.5 pt-2 text-xs text-gray-500 dark:text-gray-400">
                            {{ $getHeading }}
                        </label>
                    @endif

                    <x-filament::input
                        :attributes="
                            \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                                ->merge($extraAlpineAttributes, escape: false)
                                ->merge([
                                    'inlinePrefix' => $isPrefixInline && (count($prefixActions) || $prefixIcon || filled($prefixLabel)),
                                    'inlineSuffix' => $isSuffixInline && (count($suffixActions) || $suffixIcon || filled($suffixLabel)),
                                    'class' => 'fi-quantity',
                                    'disabled' => $isDisabled,
                                    'id' => $id,
                                    'max' => $getMaxValue,
                                    'min' => $getMinValue,
                                    'readonly' => $isReadOnly(),
                                    'required' => $isRequired(),
                                    'type' => 'number',
                                    $applyStateBindingModifiers('wire:model') => $getStatePath,
                                ], escape: false)
                        "
                    />
                </div>

                @if ($isStacked())
                    <div
                        class="-gap-y-px flex flex-col divide-y divide-gray-200 border-s border-gray-200 dark:divide-white/10 dark:border-white/10"
                    >
                        <button
                            :disabled="!isIncrementAllowed"
                            @click="increment"
                            type="button"
                            class="inline-flex h-8 w-7 items-center justify-center gap-x-2 rounded-se-lg bg-gray-50 text-sm font-medium text-gray-800 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                        >
                            @svg('heroicon-o-plus', 'size-4 shrink-0')
                        </button>
                        <button
                            :disabled="!isDecrementAllowed"
                            @click="decrement"
                            type="button"
                            class="inline-flex h-8 w-7 items-center justify-center gap-x-2 rounded-ee-lg bg-gray-50 text-sm font-medium text-gray-800 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                        >
                            @svg('heroicon-o-minus', 'size-4 shrink-0')
                        </button>
                    </div>
                @else
                    <div class="flex items-center justify-end gap-x-1.5 px-2">
                        <button
                            :disabled="!isDecrementAllowed"
                            @click="decrement"
                            type="button"
                            class="inline-flex size-6 items-center justify-center gap-x-2 rounded-full border border-gray-200 bg-white text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 disabled:pointer-events-none disabled:opacity-50 dark:border-white/10 dark:bg-gray-900 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                        >
                            @svg('heroicon-o-minus', 'h-3.5 w-3.5 shrink-0')
                        </button>
                        <button
                            :disabled="!isIncrementAllowed"
                            @click="increment"
                            type="button"
                            class="inline-flex size-6 items-center justify-center gap-x-2 rounded-full border border-gray-200 bg-white text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 disabled:pointer-events-none disabled:opacity-50 dark:border-white/10 dark:bg-gray-900 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                        >
                            @svg('heroicon-o-plus', 'h-3.5 w-3.5 shrink-0')
                        </button>
                    </div>
                @endif
            </div>
        </x-filament::input.wrapper>
    </div>

    <style>
        /* Chrome, Safari, Edge, Opera */
        input.zeus-quantity::-webkit-outer-spin-button,
        input.zeus-quantity::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        /* Firefox */
        input[type='number'].zeus-quantity {
            -moz-appearance: textfield;
        }
    </style>
</x-dynamic-component>
