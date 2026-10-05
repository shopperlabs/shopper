@props([
    'tabs' => [],
    'active' => null,
    'toolbar' => false,
])

@if (count($tabs))
    <nav
        @class([
            'flex gap-x-1 overflow-x-auto',
            'border-sh-border items-center border-b' => ! $toolbar,
            'sh-table-tabs bg-sh-muted-strong/50 ring-sh-border min-w-0 shrink items-center rounded-lg p-1 ring-1' => $toolbar,
        ])
    >
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
                    'group relative flex items-center gap-x-2 px-3 text-sm font-medium whitespace-nowrap transition outline-none',
                    'pt-1 pb-3' => ! $toolbar,
                    'focus-visible:ring-primary-500 rounded-md py-1 focus-visible:ring-2' => $toolbar,
                    'text-primary-600 dark:text-primary-400' => $isActive && ! $toolbar,
                    'bg-sh-surface text-sh-fg dark:bg-sh-muted-strong shadow-sm' => $isActive && $toolbar,
                    'text-sh-fg-muted hover:text-sh-fg-secondary' => ! $isActive,
                ])
            >
                @if ($icon)
                    <x-filament::icon
                        :icon="$icon"
                        @class([
                            'size-5' => ! $toolbar,
                            'size-4' => $toolbar,
                            'text-primary-600 dark:text-primary-400' => $isActive && ! $toolbar,
                            'text-sh-fg-muted group-hover:text-sh-fg-secondary' => ! $isActive,
                        ])
                    />
                @endif

                <span>{{ $tab['label'] }}</span>

                @if (filled($badge))
                    <x-filament::badge size="sm" :color="$badgeColor">
                        {{ $badge }}
                    </x-filament::badge>
                @endif

                @if ($isActive && ! $toolbar)
                    <span
                        class="bg-primary-600 dark:bg-primary-400 absolute inset-x-0 bottom-0 h-0.5 rounded-full"
                    ></span>
                @endif
            </button>
        @endforeach
    </nav>
@endif
