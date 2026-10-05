@props([
    'theme',
])

<div
    data-theme="{{ $theme->id }}"
    x-data="{ dark: document.documentElement.classList.contains('dark') }"
    x-init="
        new MutationObserver(
            () => (dark = document.documentElement.classList.contains('dark')),
        ).observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class'],
        })
    "
    x-bind:class="{ dark }"
    {{ $attributes->twMerge(['class' => 'aspect-video w-full overflow-hidden rounded-lg ring-1 ring-sh-border']) }}
>
    <div class="bg-sh-body flex h-full flex-col">
        <!-- header -->
        <header
            class="bg-sh-header border-sh-header-border flex shrink-0 items-center justify-between border-b px-2.5 py-2"
        >
            <div class="flex items-center gap-2">
                <div class="flex flex-col gap-0.5">
                    <div class="bg-sh-header-fg/80 h-px w-2 rounded-full"></div>
                    <div class="bg-sh-header-fg/80 h-px w-2 rounded-full"></div>
                    <div class="bg-sh-header-fg/80 h-px w-2 rounded-full"></div>
                </div>
                <div class="size-2 rounded-full bg-green-600"></div>
                <div class="bg-sh-header-fg/70 ml-1 h-0.75 w-6 rounded-full"></div>
                <div class="bg-sh-header-fg/40 h-0.75 w-6 rounded-full"></div>
                <div class="bg-sh-header-fg/40 h-0.75 w-4 rounded-full"></div>
            </div>
            <div class="flex items-center gap-1">
                <div class="bg-sh-header-fg/70 size-1.5 rounded-full"></div>
                <div class="bg-sh-header-fg/90 size-1.5 rounded-full"></div>
            </div>
        </header>

        <!-- App body : sidebar + content -->
        <div class="bg-sh-body grid min-h-0 flex-1 grid-cols-[1fr_4fr]">
            <!-- Sidebar -->
            <div class="bg-sh-sidebar border-sh-border flex flex-col gap-1.25 border-r p-2">
                <div class="bg-sh-fg-muted/40 h-0.75 w-2/3 rounded-full"></div>
                <div class="bg-sh-fg-muted/25 h-0.75 rounded-full"></div>
                <div class="bg-sh-fg-muted/25 h-0.75 rounded-full"></div>
                <div class="bg-sh-fg-muted/25 h-0.75 rounded-full"></div>

                <div class="bg-sh-fg-muted/40 mt-2 h-0.75 w-2/3 rounded-full"></div>
                <div class="bg-sh-fg-muted/25 h-0.75 rounded-full"></div>
                <div class="bg-sh-fg-muted/25 h-0.75 rounded-full"></div>

                <div class="bg-sh-fg-muted/40 mt-2 h-0.75 w-2/3 rounded-full"></div>
                <div class="bg-sh-fg-muted/25 h-0.75 rounded-full"></div>
            </div>

            <!-- Content -->
            <div class="px-5 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <div class="bg-sh-fg-muted/50 size-1.5 rounded-sm"></div>
                        <div class="bg-sh-fg-muted/40 h-1.5 w-12 rounded-full"></div>
                    </div>
                    <div class="bg-sh-accent flex h-3.5 w-9 items-center justify-center rounded-md">
                        <div class="bg-sh-accent-fg/90 h-1 w-2/3 rounded-full"></div>
                    </div>
                </div>

                <div class="bg-sh-muted mt-2.5 rounded-lg p-1.5">
                    <div
                        class="bg-sh-surface ring-sh-border flex items-center justify-between rounded-md p-2 shadow-sm ring-1"
                    >
                        <div class="flex flex-col gap-1.5">
                            <div class="bg-sh-fg-muted/50 h-1.5 w-6 rounded-full"></div>
                            <div class="bg-sh-fg-muted/30 h-1.5 w-14 rounded-full"></div>
                        </div>
                        <div class="bg-sh-accent flex w-6 justify-end rounded-full p-0.75">
                            <div class="size-2.5 rounded-full bg-white"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
