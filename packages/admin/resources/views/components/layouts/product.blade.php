@props([
    'title' => null,
])

@php
    $product = request()->route('product');
    $groups = resolve(\Shopper\Navigation\Product\ProductSectionManager::class)->groupedForProduct($product);

    resolve(\Shopper\Sidebar\Breadcrumbs\Breadcrumbs::class)->prepend(
        new \Shopper\Sidebar\Breadcrumbs\Breadcrumb(
            text: $product->name,
            url: route('shopper.products.edit', $product),
        )
    );

    $editPath = trim(parse_url(route('shopper.products.edit', ['product' => $product]), PHP_URL_PATH) ?? '', '/');
    $currentRest = ltrim(\Illuminate\Support\Str::after(trim(request()->path(), '/'), $editPath), '/');
    $currentSegment = $currentRest === '' ? '' : \Illuminate\Support\Str::before($currentRest, '/');
@endphp

<x-shopper::layouts.app :$title>
    <div class="sticky top-0 z-10 bg-sh-surface border-b border-sh-border py-8">
        <x-shopper::container>
            <x-shopper::heading>
                <x-slot:title>
                    <div class="space-y-2">
                        @if ($product->type)
                            <x-filament::badge
                                :color="$product->type->getColor()"
                                :icon="$product->type->getIcon()"
                                class="inline-flex"
                            >
                                {{ $product->type->getLabel() }}
                            </x-filament::badge>
                        @endif

                        <h2 class="font-heading text-2xl font-bold text-sh-fg sm:truncate sm:text-3xl sm:leading-9">
                            {{ $product->name }}
                        </h2>
                    </div>
                </x-slot>

                <x-slot:action>
                    <livewire:shopper-products.delete-action :$product :key="'product-delete-' . $product->getKey()" />
                </x-slot>
            </x-shopper::heading>

            {{ shopper()->getRenderHook(\Shopper\View\ProductRenderHook::EDIT_HEADER_AFTER) }}
        </x-shopper::container>
    </div>

    <div
        x-data="{ collapsed: localStorage.getItem('shopper-product-section-collapsed') === 'true' }"
        x-init="$watch('collapsed', (value) => localStorage.setItem('shopper-product-section-collapsed', value))"
    >
        <div class="flex py-8">
            <aside
                class="shrink-0 pl-4 transition-all duration-200"
                :class="collapsed ? 'w-15' : 'w-58'"
            >
                <div class="sticky top-34 space-y-6">
                    <div class="flex" :class="collapsed ? 'justify-center' : 'justify-end'">
                        <button
                            type="button"
                            x-on:click="collapsed = ! collapsed"
                            class="text-sh-fg-muted hover:bg-sh-sidebar-hover hover:text-sh-fg inline-flex size-8 items-center justify-center rounded-lg transition"
                            :aria-label="collapsed ? @js(__('shopper::words.expand')) : @js(__('shopper::words.collapse'))"
                            x-tooltip="{
                                content: () => collapsed ? @js(__('shopper::words.expand')) : @js(__('shopper::words.collapse')),
                                placement: 'right',
                                theme: $store.theme,
                            }"
                        >
                            <span
                                class="inline-flex transition-transform duration-200"
                                :class="collapsed ? 'rotate-180' : ''"
                            >
                                <x-filament::icon
                                    icon="untitledui-chevron-left-double"
                                    class="size-4"
                                />
                            </span>
                        </button>
                    </div>

                    {{ shopper()->getRenderHook(\Shopper\View\ProductRenderHook::EDIT_TABS_BEFORE) }}

                    <nav :class="collapsed ? 'space-y-1' : 'space-y-6'" aria-label="{{ __('shopper::pages/products.single') }}">
                        @foreach ($groups as $group)
                            <div>
                                @if ($group['label'])
                                    <p
                                        x-show="! collapsed"
                                        x-cloak
                                        class="text-sh-fg-muted px-3 text-xs font-semibold tracking-wider uppercase"
                                    >
                                        {{ $group['label'] }}
                                    </p>
                                @endif

                                <ul role="list" class="space-y-0.5" :class="! collapsed && @js((bool) $group['label']) ? 'mt-2' : ''">
                                    @foreach ($group['items'] as $section)
                                        @php
                                            $permission = $section->permission();
                                        @endphp

                                        @if ($permission && ! auth()->user()?->can($permission))
                                            @continue
                                        @endif

                                        @php
                                            $url = $section->url($product);
                                            $sectionRest = ltrim(\Illuminate\Support\Str::after(trim(parse_url($url, PHP_URL_PATH) ?? '', '/'), $editPath), '/');
                                            $sectionSegment = $sectionRest === '' ? '' : \Illuminate\Support\Str::before($sectionRest, '/');
                                            $isActive = $sectionSegment === $currentSegment;
                                        @endphp

                                        <li x-bind:class="! collapsed ? '' : 'flex flex-col items-center'">
                                            <a
                                                href="{{ $url }}"
                                                wire:navigate
                                                @class([
                                                    'group items-center gap-x-3 rounded-lg text-sm font-medium transition',
                                                    'bg-sh-sidebar-hover text-sh-fg' => $isActive,
                                                    'text-sh-fg-secondary hover:bg-sh-sidebar-hover hover:text-sh-fg' => ! $isActive,
                                                ])
                                                :class="collapsed ? 'inline-flex justify-center p-2' : 'flex px-3 py-2'"
                                                x-tooltip="{
                                                    content: @js($section->name()),
                                                    placement: 'right',
                                                    theme: $store.theme,
                                                    onShow: () => collapsed,
                                                }"
                                                @if ($isActive) aria-current="page" @endif
                                            >
                                                <x-filament::icon
                                                    :icon="$section->icon()"
                                                    @class([
                                                        'size-5 shrink-0',
                                                        'text-sh-fg' => $isActive,
                                                        'text-sh-fg-muted group-hover:text-sh-fg-secondary' => ! $isActive,
                                                    ])
                                                />

                                                <span x-show="! collapsed" x-cloak class="truncate">
                                                    {{ $section->name() }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach

                        {{ shopper()->getRenderHook(\Shopper\View\ProductRenderHook::EDIT_TABS_END) }}
                    </nav>
                </div>
            </aside>

            <div class="min-w-0 flex-1 px-4 lg:px-6">
                {{ shopper()->getRenderHook(\Shopper\View\ProductRenderHook::EDIT_CONTENT_BEFORE) }}

                {{ $slot }}

                {{ shopper()->getRenderHook(\Shopper\View\ProductRenderHook::EDIT_CONTENT_AFTER) }}
            </div>
        </div>
    </div>
</x-shopper::layouts.app>
