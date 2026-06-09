<div x-data="{ dropdownOpen: false }" class="relative">
    <button
        type="button"
        @click="dropdownOpen = !dropdownOpen"
        class="hover:bg-sh-header-hover hidden cursor-pointer items-center overflow-hidden rounded-lg p-1 lg:inline-flex"
        aria-label="{{ __('shopper::layout.locale_switcher') }}"
    >
        <img
            src="{{ url(shopper()->prefix().'/images/flags/'.($locales[$locale]['flag'] ?? 'gb').'.svg') }}"
            class="size-6 rounded-full object-cover"
            alt="{{ $locale }}"
        />
    </button>

    <div
        x-show="dropdownOpen"
        x-transition:enter="transition duration-100 ease-out"
        x-transition:enter-start="scale-95 transform opacity-0"
        x-transition:enter-end="scale-100 transform opacity-100"
        x-transition:leave="transition duration-75 ease-in"
        x-transition:leave-start="scale-100 transform opacity-100"
        x-transition:leave-end="scale-95 transform opacity-0"
        @click.outside="dropdownOpen = false"
        x-cloak
        class="bg-sh-surface ring-sh-border absolute top-10 right-0 z-50 w-52 origin-top-right space-y-0.5 overflow-hidden rounded-xl p-1 shadow-md ring-1"
        role="menu"
        aria-orientation="vertical"
        tabindex="-1"
    >
        @foreach ($locales as $code => $config)
            <button
                type="button"
                wire:click="switchLocale('{{ $code }}')"
                @click="dropdownOpen = false"
                @class([
                    'group flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm leading-5 transition',
                    'bg-sh-muted text-sh-fg' => $locale === $code,
                    'text-sh-fg-secondary hover:bg-sh-muted hover:text-sh-fg' => $locale !== $code,
                ])
                role="menuitem"
            >
                <img
                    src="{{ url(shopper()->prefix().'/images/flags/'.$config['flag'].'.svg') }}"
                    class="size-5 rounded-full object-cover"
                    alt="{{ $code }}"
                />
                {{ $config['label'] }}
            </button>
        @endforeach
    </div>
</div>
