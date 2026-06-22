<header
    class="sh-header bg-sh-header border-sh-header-border text-sh-header-fg sticky top-0 z-20 flex h-14 shrink-0 items-center gap-3 border-b px-4 text-sm"
>
    <button
        @click.stop="$store.sidebar.open()"
        class="hover:bg-sh-header-hover text-sh-header-fg-muted hover:text-sh-header-fg -ml-2 inline-flex size-9 items-center justify-center rounded-md lg:hidden"
        aria-label="Open sidebar"
    >
        <x-untitledui-menu-03 class="size-5" aria-hidden="true" />
    </button>

    @if (\Shopper\Sidebar\sidebar_is_collapsible())
        <button
            x-on:click="$store.sidebar.toggleCollapse()"
            class="hover:bg-sh-header-hover text-sh-header-fg-muted hover:text-sh-header-fg -ml-2 hidden size-9 items-center justify-center rounded-md lg:inline-flex"
            aria-label="{{ __('shopper::layout.sidebar.toggle') }}"
        >
            <x-untitledui-menu-03 class="size-5" aria-hidden="true" />
        </button>
    @endif

    <div class="flex-1 flex items-center gap-4">
        <x-shopper::link
            :href="route('shopper.dashboard')"
            class="flex items-center gap-2.5 font-semibold tracking-tight hover:opacity-80"
        >
            <x-shopper::brand class="size-6" aria-hidden="true" />
            @if ($storeName = shopper_setting('name'))
                <span>{{ $storeName }}</span>
            @endif
        </x-shopper::link>

        <div class="flex items-center gap-4 max-lg:hidden">
            <span class="text-sh-header-fg-muted/60" aria-hidden="true">/</span>

            @include(config('sidebar.breadcrumbs.view', 'sidebar::breadcrumbs'))
        </div>
    </div>

    <div class="flex items-center gap-x-2">
        {{ shopper()->getRenderHook(\Shopper\View\LayoutRenderHook::HEADER_START) }}

        @if (config('shopper.components.dashboard.components.search'))
            <livewire:shopper-search />
        @endif

        {{ shopper()->getRenderHook(\Shopper\View\LayoutRenderHook::HEADER_END) }}

        @if (config('shopper.admin.notifications.database.enabled'))
            <livewire:database-notifications />
        @endif

        <a
            href="{{ url('/') }}"
            target="_blank"
            class="text-sh-header-fg-muted hover:text-sh-header-fg hover:bg-sh-header-hover hidden items-center rounded-lg p-1 lg:inline-flex"
        >
            <x-phosphor-google-chrome-logo class="size-6.5" stroke-width="1.5" aria-hidden="true" />
        </a>

        @if (count(config('shopper.admin.locales', [])) > 1)
            <livewire:shopper-locale-switcher />
        @endif

        <livewire:shopper-account.dropdown />
    </div>
</header>
