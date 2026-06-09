@props([
    'theme',
])

<div
    data-theme="{{ $theme->id }}"
    x-data="{ dark: document.documentElement.classList.contains('dark') }"
    x-init="new MutationObserver(() => dark = document.documentElement.classList.contains('dark')).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] })"
    x-bind:class="{ dark }"
    {{ $attributes->twMerge(['class' => 'aspect-video w-full overflow-hidden rounded-lg ring-1 ring-sh-border']) }}
>
    <div class="flex h-full flex-col bg-sh-body">
        <!-- header -->
        <header class="flex shrink-0 bg-sh-header border-sh-header-border border-b items-center justify-between px-2.5 py-2">
            <div class="flex items-center gap-2">
                <div class="flex flex-col gap-0.5">
                    <div class="h-px w-2 rounded-full bg-sh-header-fg/80"></div>
                    <div class="h-px w-2 rounded-full bg-sh-header-fg/80"></div>
                    <div class="h-px w-2 rounded-full bg-sh-header-fg/80"></div>
                </div>
                <div class="size-2 rounded-full bg-green-600"></div>
                <div class="ml-1 h-0.75 w-6 rounded-full bg-sh-header-fg/70"></div>
                <div class="h-0.75 w-6 rounded-full bg-sh-header-fg/40"></div>
                <div class="h-0.75 w-4 rounded-full bg-sh-header-fg/40"></div>
            </div>
            <div class="flex items-center gap-1">
                <div class="size-1.5 rounded-full bg-sh-header-fg/70"></div>
                <div class="size-1.5 rounded-full bg-sh-header-fg/90"></div>
            </div>
        </header>

        <!-- App body : sidebar + content -->
        <div class="grid min-h-0 flex-1 grid-cols-[1fr_4fr] bg-sh-body">
            <!-- Sidebar -->
            <div class="flex flex-col gap-1.25 p-2 bg-sh-sidebar border-sh-border border-r">
                <div class="h-0.75 w-2/3 rounded-full bg-sh-fg-muted/40"></div>
                <div class="h-0.75 rounded-full bg-sh-fg-muted/25"></div>
                <div class="h-0.75 rounded-full bg-sh-fg-muted/25"></div>
                <div class="h-0.75 rounded-full bg-sh-fg-muted/25"></div>

                <div class="mt-2 h-0.75 w-2/3 rounded-full bg-sh-fg-muted/40"></div>
                <div class="h-0.75 rounded-full bg-sh-fg-muted/25"></div>
                <div class="h-0.75 rounded-full bg-sh-fg-muted/25"></div>

                <div class="mt-2 h-0.75 w-2/3 rounded-full bg-sh-fg-muted/40"></div>
                <div class="h-0.75 rounded-full bg-sh-fg-muted/25"></div>
            </div>

            <!-- Content -->
            <div class="py-3 px-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <div class="size-1.5 rounded-sm bg-sh-fg-muted/50"></div>
                        <div class="h-1.5 w-12 rounded-full bg-sh-fg-muted/40"></div>
                    </div>
                    <div class="flex h-3.5 w-9 items-center justify-center rounded-md bg-sh-accent">
                        <div class="h-1 w-2/3 rounded-full bg-sh-accent-fg/90"></div>
                    </div>
                </div>

                <div class="mt-2.5 rounded-lg bg-sh-muted p-1.5">
                    <div class="flex items-center justify-between rounded-md bg-sh-surface p-2 shadow-sm ring-1 ring-sh-border">
                        <div class="flex flex-col gap-1.5">
                            <div class="h-1.5 w-6 rounded-full bg-sh-fg-muted/50"></div>
                            <div class="h-1.5 w-14 rounded-full bg-sh-fg-muted/30"></div>
                        </div>
                        <div class="flex w-6 justify-end rounded-full bg-sh-accent p-0.75">
                            <div class="size-2.5 rounded-full bg-white"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
