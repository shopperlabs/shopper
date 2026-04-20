@props([
    'title' => null,
])

@php
    $buckets = resolve(\Shopper\Settings\SettingManager::class)->grouped();
    $allItems = $buckets->flatMap(fn (array $bucket) => $bucket['items']);
    $navLabel = __('shopper::pages/settings/global.menu');
@endphp

<x-shopper::layouts.app :title="$title">
    <x-shopper::layouts.split with-border>
        <x-slot name="sidebar">
            <nav
                class="hide-scroll -mb-px flex space-x-8 overflow-x-auto scroll-smooth px-4 lg:hidden"
                aria-label="{{ $navLabel }}"
            >
                @foreach ($allItems as $item)
                    @php
                        $permission = $item->permission();
                    @endphp

                    @if ($permission && ! auth()->user()?->can($permission))
                        @continue
                    @endif

                    <x-shopper::menu.nav-setting :menu="$item" />
                @endforeach
            </nav>

            <nav class="hidden space-y-6 px-5 py-6 lg:block" aria-label="{{ $navLabel }}">
                @foreach ($buckets as $bucket)
                    <div>
                        @if ($bucket['label'])
                            <h3 class="text-sh-fg-muted px-3 text-xs font-semibold tracking-wider uppercase">
                                {{ $bucket['label'] }}
                            </h3>
                        @endif

                        <ul @class(['space-y-0.5', 'mt-2' => $bucket['label']])>
                            @foreach ($bucket['items'] as $item)
                                @php
                                    $permission = $item->permission();
                                @endphp

                                @if ($permission && ! auth()->user()?->can($permission))
                                    @continue
                                @endif

                                @php
                                    $url = $item->url();
                                    $path = $url ? trim(parse_url($url, PHP_URL_PATH) ?? '', '/') : null;
                                    $isCurrent = $path !== null && $path !== '' && request()->is($path . '*');
                                @endphp

                                <li>
                                    <a
                                        href="{{ $url ?? '#' }}"
                                        @class([
                                            'group flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-medium transition',
                                            'bg-sh-sidebar-hover text-sh-fg' => $isCurrent,
                                            'text-sh-fg-secondary hover:bg-sh-sidebar-hover hover:text-sh-fg' => ! $isCurrent,
                                        ])
                                        @if ($isCurrent)
                                            aria-current="page"
                                        @endif
                                        wire:navigate
                                    >
                                        <x-filament::icon
                                            :icon="$item->icon()"
                                            @class([
                                                'size-5 shrink-0',
                                                'text-sh-fg' => $isCurrent,
                                                'text-sh-fg-muted group-hover:text-sh-fg-secondary' => ! $isCurrent,
                                            ])
                                        />
                                        <span class="truncate">{{ $item->name() }}</span>

                                        @if (! $url)
                                            <x-filament::badge size="sm" color="gray" class="ml-auto">
                                                {{ __('shopper::words.soon') }}
                                            </x-filament::badge>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </nav>
        </x-slot>

        <div class="py-5">
            {{ $slot }}
        </div>
    </x-shopper::layouts.split>
</x-shopper::layouts.app>
