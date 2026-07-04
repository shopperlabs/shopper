@php
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $minValue = $getMinValue();
    $maxValue = $getMaxValue();
    $step = $getStep() ?? 1;
    $placeholder = $getPlaceholder();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            min: @js($minValue),
            max: @js($maxValue),
            step: @js($step),
            get value() {
                const value = parseFloat(this.state)

                return Number.isNaN(value) ? null : value
            },
            clamp(value) {
                if (this.min !== null && value < this.min) {
                    value = this.min
                }

                if (this.max !== null && value > this.max) {
                    value = this.max
                }

                return value
            },
            increment() {
                this.state = this.clamp((this.value ?? 0) + this.step)
            },
            decrement() {
                this.state = this.clamp((this.value ?? 0) - this.step)
            },
            get canIncrement() {
                return this.value === null || this.max === null || this.value < this.max
            },
            get canDecrement() {
                return this.value === null || this.min === null || this.value > this.min
            },
        }"
        class="max-w-30"
    >
        <x-filament::input.wrapper
            :disabled="$isDisabled"
            :valid="! $errors->has($statePath)"
            :inline-prefix="true"
            :inline-suffix="true"
        >
            <x-slot:prefix>
                <button
                    type="button"
                    x-on:click="decrement"
                    x-bind:disabled="! canDecrement"
                    @disabled($isDisabled)
                    class="text-sh-fg-muted hover:text-sh-fg flex items-center disabled:pointer-events-none disabled:opacity-40"
                >
                    <x-filament::icon icon="untitledui-minus" class="size-4" />
                </button>
            </x-slot:prefix>

            <x-filament::input
                type="number"
                inputmode="numeric"
                x-model="state"
                :min="$minValue"
                :max="$maxValue"
                :step="$step"
                :disabled="$isDisabled"
                :placeholder="$placeholder"
                class="text-center [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                :attributes="\Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())"
            />

            <x-slot:suffix>
                <button
                    type="button"
                    x-on:click="increment"
                    x-bind:disabled="! canIncrement"
                    @disabled($isDisabled)
                    class="text-sh-fg-muted hover:text-sh-fg flex items-center disabled:pointer-events-none disabled:opacity-40"
                >
                    <x-filament::icon icon="untitledui-plus" class="size-4" />
                </button>
            </x-slot:suffix>
        </x-filament::input.wrapper>
    </div>
</x-dynamic-component>
