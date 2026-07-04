@php
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $countries = $getPhoneCountries();
    $selectedCountry = $getSelectedCountryCode();
    $nationalNumber = $getNationalNumber();
    $placeholder = $getPlaceholder();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            countries: @js($countries),
            country: @js($selectedCountry),
            national: @js($nationalNumber),
            search: '',
            get selectedCountry() {
                return this.countries.find((country) => country.code === this.country)
            },
            get filteredCountries() {
                const query = this.search.toLowerCase().trim()

                if (! query) {
                    return this.countries
                }

                const digits = query.replace(/[^0-9]/g, '')

                return this.countries.filter(
                    (country) =>
                        country.name.toLowerCase().includes(query)
                        || (digits.length > 0 && country.dial.includes(digits))
                )
            },
            select(country) {
                this.country = country.code
                this.search = ''
                this.compose()
                this.$refs.panel.close()
                this.$refs.national.focus()
            },
            compose() {
                let raw = (this.national ?? '').trim()

                if (raw.startsWith('+')) {
                    const digits = raw.replace(/[^0-9]/g, '')
                    const match = [...this.countries]
                        .filter((country) => ('+' + digits).startsWith(country.dial))
                        .sort((a, b) => b.dial.length - a.dial.length)[0]

                    if (match) {
                        this.country = match.code
                        this.national = digits.slice(match.dial.length - 1)
                        raw = this.national
                    }
                }

                const digits = raw.replace(/[^0-9]/g, '')

                this.state = digits ? (this.selectedCountry?.dial ?? '') + digits : null
            },
        }"
        class="relative"
    >
        <x-filament::input.wrapper
            :disabled="$isDisabled"
            :valid="! $errors->has($statePath)"
            :inline-prefix="true"
        >
            <x-slot:prefix>
                <button
                    type="button"
                    x-on:click="$refs.panel.style.width = $el.closest('.fi-input-wrp').offsetWidth + 'px'; $refs.panel.toggle($event)"
                    @disabled($isDisabled)
                    class="flex items-center gap-1.5"
                >
                    <img x-bind:src="selectedCountry?.flag" class="size-4 shrink-0 rounded-full object-cover" alt="" x-show="selectedCountry" />
                    <span class="text-sh-fg-secondary text-sm" x-text="selectedCountry?.dial"></span>
                    <x-filament::icon icon="untitledui-chevron-down" class="text-sh-fg-muted size-3.5 shrink-0" />
                </button>
            </x-slot:prefix>

            <x-filament::input
                type="tel"
                x-ref="national"
                x-model="national"
                x-on:input="compose"
                :disabled="$isDisabled"
                :placeholder="$placeholder"
                :attributes="\Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())"
            />
        </x-filament::input.wrapper>

        <div
            x-ref="panel"
            x-float.placement.bottom-start.flip.shift.teleport.offset="{ offset: 8 }"
            class="bg-sh-surface ring-sh-border absolute z-50 overflow-hidden rounded-xl shadow-md ring-1"
        >
            <div class="border-sh-border border-b p-2">
                <input
                    type="text"
                    x-model="search"
                    x-on:keydown.enter.prevent
                    placeholder="{{ __('shopper::forms.label.search') }}"
                    class="text-sh-fg placeholder:text-sh-fg-muted w-full border-0 bg-transparent text-sm focus:ring-0"
                />
            </div>
            <ul class="max-h-64 overflow-y-auto p-1">
                <template x-for="phoneCountry in filteredCountries" :key="phoneCountry.code">
                    <li>
                        <button
                            type="button"
                            x-on:click="select(phoneCountry)"
                            x-bind:class="{ 'bg-sh-muted': country === phoneCountry.code }"
                            class="hover:bg-sh-muted flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-start"
                        >
                            <img x-bind:src="phoneCountry.flag" class="size-4 shrink-0 rounded-full object-cover" alt="" />
                            <span class="text-sh-fg flex-1 truncate text-sm" x-text="phoneCountry.name"></span>
                            <span class="text-sh-fg-muted text-sm" x-text="phoneCountry.dial"></span>
                        </button>
                    </li>
                </template>
                <li
                    x-cloak
                    x-show="filteredCountries.length === 0"
                    class="text-sh-fg-muted p-4 text-center text-sm"
                >
                    {{ __('shopper::words.empty_space') }}
                </li>
            </ul>
        </div>
    </div>
</x-dynamic-component>
