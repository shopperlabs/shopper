@php
    $statePath = $getStatePath();
    $fromStatePath = $getFromStatePath();
    $isDisabled = $isDisabled();
    $shouldSync = $fromStatePath && blank($getRecord());
    $prefixLabel = $getPrefixLabel();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            synced: @js($shouldSync),
            slugify(value) {
                return (value ?? '')
                    .toString()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '')
            },
            init() {
                if (@js($shouldSync)) {
                    $wire.watch(@js($fromStatePath), (value) => {
                        if (this.synced) {
                            this.state = this.slugify(value)
                        }
                    })
                }
            },
            regenerate() {
                this.state = this.slugify($wire.get(@js($fromStatePath)))
                this.synced = @js($shouldSync)
            },
        }"
    >
        <x-filament::input.wrapper
            :disabled="$isDisabled"
            :valid="! $errors->has($statePath)"
            :prefix="$prefixLabel"
            :inline-prefix="true"
            :inline-suffix="true"
        >
            <x-filament::input
                type="text"
                x-model="state"
                x-on:input="synced = false"
                :disabled="$isDisabled"
                :attributes="\Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())"
            />

            @if (! $isDisabled && $fromStatePath)
                <x-slot:suffix>
                    <button
                        type="button"
                        x-on:click="regenerate"
                        title="{{ __('shopper::forms.actions.generate') }}"
                        class="text-sh-fg-muted hover:text-sh-fg flex items-center"
                    >
                        <x-filament::icon icon="untitledui-refresh-cw-02" class="size-4" />
                    </button>
                </x-slot:suffix>
            @endif
        </x-filament::input.wrapper>
    </div>
</x-dynamic-component>
