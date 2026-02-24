@props([
    'tabs' => [],
    'active' => null,
])

@if (count($tabs))
    <nav class="flex items-center gap-x-1 overflow-x-auto border-b border-gray-200 dark:border-white/10">
        @foreach ($tabs as $tab)
            @php
                $key = $tab['key'];
                $isActive = (string) $active === (string) $key;
                $badge = $tab['badge'] ?? null;
                $badgeColor = $tab['badgeColor'] ?? 'gray';
                $icon = $tab['icon'] ?? null;
            @endphp

            <button
                type="button"
                wire:click="$set('{{ $attributes->wire('model')->value() }}', '{{ $key }}')"
                @class([
                    'group relative flex items-center gap-x-2 whitespace-nowrap px-3 pb-3 pt-1 text-sm font-medium outline-none transition',
                    'text-primary-600 dark:text-primary-400' => $isActive,
                    'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' => ! $isActive,
                ])
            >
                @if ($icon)
                    <x-filament::icon
                        :icon="$icon"
                        @class([
                            'size-5',
                            'text-primary-600 dark:text-primary-400' => $isActive,
                            'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-400' => ! $isActive,
                        ])
                    />
                @endif

                <span>{{ $tab['label'] }}</span>

                @if (filled($badge))
                    <x-filament::badge size="sm" :color="$badgeColor">
                        {{ $badge }}
                    </x-filament::badge>
                @endif

                @if ($isActive)
                    <span class="absolute inset-x-0 bottom-0 h-0.5 rounded-full bg-primary-600 dark:bg-primary-400"></span>
                @endif
            </button>
        @endforeach
    </nav>
@endif
