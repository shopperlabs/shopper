<div
    x-data="{
        selected: @entangle('theme'),
        original: @js(resolve(\Shopper\Theme\ThemeManager::class)->active()->id),
        apply(id) {
            this.selected = id
            document.documentElement.dataset.theme = id
        },
    }"
    x-on:theme-saved.window="original = selected"
    x-on:livewire:navigating.window="if (selected !== original) document.documentElement.dataset.theme = original"
>
    <x-shopper::container>
        <x-shopper::heading :title="__('shopper::pages/settings/appearance.title')">
            <x-slot name="action">
                <x-filament::button wire:click="store" wire:loading.attr="disabled">
                    {{ __('shopper::forms.actions.save') }}
                </x-filament::button>
            </x-slot>
        </x-shopper::heading>

        <p class="mt-2 max-w-2xl text-sm text-sh-fg-secondary">
            {{ __('shopper::pages/settings/appearance.description') }}
        </p>

        <div class="mt-8 border-b border-sh-border">
            <nav class="-mb-px flex gap-6" aria-label="Tabs">
                <span class="border-b-2 border-sh-accent px-1 pb-3 text-sm font-medium text-sh-fg">
                    {{ __('shopper::pages/settings/appearance.themes') }}
                </span>
            </nav>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
            @foreach ($this->themes as $theme)
                <button
                    type="button"
                    x-on:click="apply(@js($theme->id))"
                    x-bind:class="selected === @js($theme->id) ? 'ring-2 ring-sh-accent bg-sh-muted' : 'ring-1 ring-transparent hover:bg-sh-muted'"
                    class="group rounded-xl p-1.5 text-left transition focus:outline-none focus-visible:ring-2 focus-visible:ring-sh-accent"
                >
                    <x-shopper::theme-preview :$theme />

                    <div class="px-1 pt-2 pb-1 text-center">
                        <p class="truncate text-sm font-medium text-sh-fg">
                            {{ $theme->name }}
                            <span class="text-xs font-normal text-sh-fg-muted">
                                {{ __('shopper::pages/settings/appearance.by') }} {{ $theme->author }}
                            </span>
                        </p>
                    </div>
                </button>
            @endforeach
        </div>
    </x-shopper::container>
</div>
