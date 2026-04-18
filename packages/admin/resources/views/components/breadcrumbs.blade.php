@php
    $breadcrumbs = \Shopper\Sidebar\sidebar_breadcrumbs();
@endphp

@if (filled($breadcrumbs))
    <nav aria-label="Breadcrumb" class="flex items-center gap-x-1 text-sm">
        <ol role="list" class="flex items-center gap-x-1">
            @foreach ($breadcrumbs as $crumb)
                <li class="flex items-center gap-x-1">
                    @if (! $loop->first)
                        <span aria-hidden="true" class="text-sh-header-fg-muted/60">/</span>
                    @endif

                    @if (filled($crumb->links))
                        <div class="flex items-center">
                            @if ($crumb->url)
                                <a
                                    href="{{ $crumb->url }}"
                                    @if ($crumb->spa) wire:navigate @endif
                                    class="text-sh-header-fg hover:bg-sh-header-hover rounded-md px-2 py-1 font-medium transition"
                                >
                                    {{ $crumb->text }}
                                </a>
                            @else
                                <span class="text-sh-header-fg px-2 py-1 font-medium">{{ $crumb->text }}</span>
                            @endif

                            <div
                                x-data="{ open: false }"
                                x-on:click.outside="open = false"
                                x-on:keydown.escape.window="open = false"
                                class="relative"
                            >
                                <button
                                    type="button"
                                    x-on:click="open = ! open"
                                    x-bind:aria-expanded="open ? 'true' : 'false'"
                                    aria-haspopup="true"
                                    aria-label="{{ __('shopper::layout.breadcrumb.toggle', ['label' => $crumb->text]) }}"
                                    class="hover:bg-sh-header-hover -ml-0.5 flex items-center rounded-md px-1 py-1.5 transition"
                                >
                                    <x-phosphor-caret-up-down class="size-3" aria-hidden="true" />
                                </button>

                                <div
                                    x-show="open"
                                    x-cloak
                                    x-transition.opacity.duration.150ms
                                    class="bg-sh-surface ring-sh-border absolute left-0 top-full z-20 mt-2 w-56 origin-top-left rounded-lg py-2 shadow-xl ring-1"
                                >
                                    @if ($crumb->url)
                                        <a
                                            href="{{ $crumb->url }}"
                                            @if ($crumb->spa) wire:navigate @endif
                                            class="text-sh-fg flex items-center gap-x-2 px-3 py-2 font-semibold"
                                        >
                                            @if ($crumb->icon)
                                                @svg($crumb->icon, 'text-sh-fg-secondary size-4')
                                            @endif
                                            <span>{{ $crumb->text }}</span>
                                        </a>
                                        <div class="border-sh-border my-1 border-t"></div>
                                    @endif

                                    @foreach ($crumb->links as $link)
                                        <a
                                            href="{{ $link->url }}"
                                            @if ($link->spa) wire:navigate @endif
                                            class="text-sh-fg-secondary hover:bg-sh-muted hover:text-sh-fg flex items-center gap-x-2 px-3 py-2 transition"
                                        >
                                            @if ($link->icon)
                                                @svg($link->icon, 'size-4')
                                            @endif
                                            <span>{{ $link->text }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @elseif ($crumb->url && ! $loop->last)
                        <a
                            href="{{ $crumb->url }}"
                            @if ($crumb->spa) wire:navigate @endif
                            class="text-sh-header-fg hover:bg-sh-header-hover rounded-md px-2 py-1 transition"
                        >
                            {{ $crumb->text }}
                        </a>
                    @else
                        <span aria-current="page" class="text-sh-header-fg-muted px-2 py-1">
                            {{ $crumb->text }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
