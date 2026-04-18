@php
    $breadcrumbs = \Shopper\Sidebar\sidebar_breadcrumbs();
@endphp

@if (filled($breadcrumbs))
    <nav class="sh-breadcrumb" aria-label="Breadcrumb">
        <ol role="list" class="sh-breadcrumb-list">
            @foreach ($breadcrumbs as $crumb)
                <li class="sh-breadcrumb-item">
                    @if (! $loop->first)
                        <span class="sh-breadcrumb-separator" aria-hidden="true">/</span>
                    @endif

                    @if (filled($crumb->links))
                        <div class="sh-breadcrumb-group">
                            @if ($crumb->url)
                                <a
                                    href="{{ $crumb->url }}"
                                    @if ($crumb->spa) wire:navigate @endif
                                    class="sh-breadcrumb-link"
                                >
                                    {{ $crumb->text }}
                                </a>
                            @else
                                <span class="sh-breadcrumb-link">{{ $crumb->text }}</span>
                            @endif

                            <div
                                x-data="{ open: false }"
                                x-on:click.outside="open = false"
                                x-on:keydown.escape.window="open = false"
                                class="sh-breadcrumb-toggle-wrapper"
                            >
                                <button
                                    type="button"
                                    x-on:click="open = ! open"
                                    x-bind:aria-expanded="open ? 'true' : 'false'"
                                    aria-haspopup="true"
                                    aria-label="Toggle {{ $crumb->text }} menu"
                                    class="sh-breadcrumb-toggle"
                                >
                                    <svg x-bind:class="{ 'sh-breadcrumb-toggle-open': open }" class="sh-breadcrumb-chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak class="sh-breadcrumb-dropdown">
                                    @if ($crumb->url)
                                        <a
                                            href="{{ $crumb->url }}"
                                            @if ($crumb->spa) wire:navigate @endif
                                            class="sh-breadcrumb-dropdown-current"
                                        >
                                            @if ($crumb->icon)
                                                @svg($crumb->icon, 'sh-breadcrumb-dropdown-icon')
                                            @endif
                                            <span>{{ $crumb->text }}</span>
                                        </a>
                                        <div class="sh-breadcrumb-dropdown-divider"></div>
                                    @endif

                                    @foreach ($crumb->links as $link)
                                        <a
                                            href="{{ $link->url }}"
                                            @if ($link->spa) wire:navigate @endif
                                            class="sh-breadcrumb-dropdown-item"
                                        >
                                            @if ($link->icon)
                                                @svg($link->icon, 'sh-breadcrumb-dropdown-icon')
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
                            class="sh-breadcrumb-link"
                        >
                            {{ $crumb->text }}
                        </a>
                    @else
                        <span class="sh-breadcrumb-current" aria-current="page">{{ $crumb->text }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
