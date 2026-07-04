@php
    $key = $getKey();
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $placeholder = $getPlaceholder();
    $selectedIconHtml = $getSelectedIconHtml();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            search: '',
            results: [],
            loading: false,
            selectedHtml: @js($selectedIconHtml),
            async loadResults() {
                this.loading = true
                this.results = await $wire.callSchemaComponentMethod(@js($key), 'getSearchResultsJs', { search: this.search })
                this.loading = false
            },
            select(icon) {
                this.state = icon.name
                this.selectedHtml = icon.html
                this.$refs.panel.close()
            },
            clear() {
                this.state = null
                this.selectedHtml = null
            },
        }"
        class="relative"
    >
        <x-filament::input.wrapper
            :disabled="$isDisabled"
            :valid="! $errors->has($statePath)"
        >
            <button
                type="button"
                x-on:click="$refs.panel.toggle($event); results.length || loadResults()"
                @disabled($isDisabled)
                class="flex w-full items-center gap-2 bg-transparent px-3 py-1.5 text-start"
            >
                <span
                    x-cloak
                    x-show="selectedHtml"
                    x-html="selectedHtml"
                    class="text-sh-fg flex size-5 shrink-0 items-center justify-center [&>svg]:size-5"
                ></span>
                <span
                    class="flex-1 truncate text-sm"
                    x-bind:class="state ? 'text-sh-fg' : 'text-sh-fg-muted'"
                    x-text="state ?? @js($placeholder)"
                ></span>
                <span
                    x-cloak
                    x-show="state"
                    x-on:click.stop="clear"
                    class="text-sh-fg-muted hover:text-sh-fg flex shrink-0 cursor-pointer items-center"
                >
                    <x-filament::icon icon="untitledui-x-close" class="size-4" />
                </span>
                <x-filament::icon icon="untitledui-chevron-down" class="text-sh-fg-muted size-4 shrink-0" />
            </button>
        </x-filament::input.wrapper>

        <div
            x-ref="panel"
            x-float.placement.bottom-start.flip.offset="{ offset: 8 }"
            class="bg-sh-surface ring-sh-border absolute z-50 w-full overflow-hidden rounded-xl shadow-md ring-1"
        >
            <div class="border-sh-border border-b p-2">
                <input
                    type="text"
                    x-model="search"
                    x-on:keydown.enter.prevent
                    x-on:input.debounce.300ms="loadResults"
                    placeholder="{{ __('shopper::forms.label.search') }}"
                    class="text-sh-fg placeholder:text-sh-fg-muted w-full border-0 bg-transparent text-sm focus:ring-0"
                />
            </div>
            <div class="max-h-64 overflow-y-auto p-2">
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
                <div x-show="! loading" class="grid grid-cols-8 gap-1">
                    <template x-for="icon in results" :key="icon.name">
                        <button
                            type="button"
                            x-on:click="select(icon)"
                            x-bind:title="icon.name"
                            x-bind:class="{ 'bg-sh-muted': state === icon.name }"
                            class="hover:bg-sh-muted flex items-center justify-center rounded-md p-2"
                        >
                            <span x-html="icon.html" class="text-sh-fg [&>svg]:size-5"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
