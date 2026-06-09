@props([
    'back',
    'current' => null,
])

<div {{ $attributes }}>
    <nav class="sm:hidden">
        <x-shopper::link
            href="{{ $back }}"
            class="flex items-center text-sm font-medium text-sh-fg-muted hover:text-sh-fg-secondary"
        >
            <x-untitledui-chevron-left
                class="mr-1 -ml-1 size-5 shrink-0 text-sh-fg-muted"
                aria-hidden="true"
            />
            {{ __('shopper::layout.back') }}
        </x-shopper::link>
    </nav>
    <nav class="hidden items-center gap-x-2 text-sm font-medium sm:flex">
        <x-shopper::link
            href="{{ route('shopper.dashboard') }}"
            class="inline-flex items-center rounded-md p-1.5 text-sm text-sh-fg-muted hover:bg-sh-muted"
        >
            <x-phosphor-monitor class="size-5" aria-hidden="true" />
        </x-shopper::link>

        {{ $slot }}

        @if ($current)
            <x-untitledui-chevron-left class="size-4 shrink-0 text-sh-fg-muted" aria-hidden="true" />
            <span
                aria-current="page"
                class="inline-block rounded-md bg-sh-muted px-2 py-1.5 text-sh-fg-secondary"
            >
                {{ $current }}
            </span>
        @endif
    </nav>
</div>
