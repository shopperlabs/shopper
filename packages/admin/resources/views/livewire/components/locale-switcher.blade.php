<x-filament::dropdown placement="bottom-end">
    <x-slot name="trigger">
        <button
            type="button"
            class="hover:bg-sh-header-hover hidden cursor-pointer items-center overflow-hidden rounded-lg p-1 lg:inline-flex"
            aria-label="{{ __('shopper::layout.locale_switcher') }}"
        >
            <img
                src="{{ url(shopper()->prefix().'/images/flags/'.($locales[$locale]['flag'] ?? 'gb').'.svg') }}"
                class="size-6 rounded-full object-cover"
                alt="{{ $locale }}"
            />
        </button>
    </x-slot>

    <x-filament::dropdown.list>
        @foreach ($locales as $code => $config)
            <x-filament::dropdown.list.item
                wire:click="switchLocale('{{ $code }}')"
                :image="url(shopper()->prefix().'/images/flags/'.$config['flag'].'.svg')"
                {{--:color="$locale === $code ? 'primary' : 'gray'"--}}
            >
                {{ $config['label'] }}
            </x-filament::dropdown.list.item>
        @endforeach
    </x-filament::dropdown.list>
</x-filament::dropdown>
