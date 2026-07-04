@php
    $key = $getKey();
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $placeholder = $getPlaceholder();
    $selectedIconHtml = $getSelectedIconHtml();
    $selectedIconLabel = $getSelectedIconLabel();
    $setOptions = $getSetOptions();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            open: false,
            search: '',
            set: '',
            results: [],
            loading: false,
            selectedHtml: @js($selectedIconHtml),
            selectedLabel: @js($selectedIconLabel),
            async loadResults() {
                this.loading = true
                this.results = await $wire.callSchemaComponentMethod(@js($key), 'getSearchResultsJs', { search: this.search, set: this.set || null })
                this.loading = false
            },
            toggle() {
                this.open = ! this.open

                if (this.open && this.results.length === 0) {
                    this.loadResults()
                }
            },
            select(icon) {
                this.state = icon.name
                this.selectedHtml = icon.html
                this.selectedLabel = icon.label
                this.open = false
            },
            clear() {
                this.state = null
                this.selectedHtml = null
                this.selectedLabel = null
            },
        }"
        x-on:click.outside="open = false"
        x-on:keydown.escape.window="open = false"
        class="relative"
    >
        <x-filament::input.wrapper
            :disabled="$isDisabled"
            :valid="! $errors->has($statePath)"
            :inline-prefix="true"
            :inline-suffix="true"
            x-bind:class="{
                '[&_.fi-input-wrp-prefix]:hidden': ! state,
                '[&_.fi-input-wrp-suffix]:hidden': ! state,
            }"
        >
            <x-slot:prefix>
                <span
                    x-html="selectedHtml"
                    class="text-sh-fg-muted flex size-5 shrink-0 items-center justify-center [&>svg]:size-5"
                ></span>
            </x-slot:prefix>

            <x-filament::input
                type="text"
                readonly
                x-model="selectedLabel"
                x-on:click="toggle"
                :disabled="$isDisabled"
                :placeholder="$placeholder"
                class="cursor-pointer"
            />

            <x-slot:suffix>
                <button
                    type="button"
                    x-on:click.stop="clear"
                    @disabled($isDisabled)
                    class="text-sh-fg-muted hover:text-sh-fg flex items-center"
                >
                    <x-filament::icon icon="untitledui-x-close" class="size-4" />
                </button>
            </x-slot:suffix>
        </x-filament::input.wrapper>

        <div
            x-cloak
            x-show="open"
            class="bg-sh-surface ring-sh-border absolute top-full left-0 z-10 mt-2 w-full rounded-xl shadow-md ring-1"
        >
            <div class="@container flex flex-col gap-3 pt-4">
                <div class="px-4">
                    <x-filament::input.wrapper :inline-prefix="true" prefix-icon="untitledui-search-md">
                        <x-filament::input
                            type="text"
                            x-model="search"
                            x-on:keydown.enter.prevent
                            x-on:input.debounce.300ms="loadResults"
                            :placeholder="__('shopper::forms.label.search')"
                        />
                    </x-filament::input.wrapper>
                </div>

                @if (count($setOptions) > 1)
                    <div class="border-sh-border flex items-center gap-2 overflow-x-auto border-b px-4 pb-3">
                        <button
                            type="button"
                            x-on:click="set = ''; loadResults()"
                            x-bind:class="set === '' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-sh-border text-sh-fg-secondary hover:bg-sh-muted'"
                            class="shrink-0 rounded-lg border px-3 py-1.5 text-sm whitespace-nowrap"
                        >
                            {{ __('shopper::forms.label.all_icons') }}
                        </button>
                        @foreach ($setOptions as $setName => $setOption)
                            <button
                                type="button"
                                x-on:click="set = @js($setName); loadResults()"
                                x-bind:class="set === @js($setName) ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-sh-border text-sh-fg-secondary hover:bg-sh-muted'"
                                class="flex shrink-0 items-center gap-1.5 rounded-lg border px-3 py-1.5 text-sm whitespace-nowrap"
                            >
                                {{ $setOption['label'] }}
                                <span class="text-sh-fg-muted text-xs">{{ $setOption['count'] }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif

                <div x-show="loading" class="flex items-center justify-center p-4">
                    <x-filament::loading-indicator class="size-5" />
                </div>

                <p
                    x-cloak
                    x-show="! loading && results.length === 0"
                    class="text-sh-fg-muted p-4 text-center text-sm"
                >
                    {{ __('shopper::words.empty_space') }}
                </p>

                {{ $getSearchResultsViewComponent() }}
            </div>
        </div>
    </div>
</x-dynamic-component>
