@props([
    'back',
    'current' => null,
])

<div {{ $attributes }}>
    <nav class="sm:hidden">
        <x-shopper::link
            href="{{ $back }}"
            class="text-sh-fg-muted hover:text-sh-fg-secondary flex items-center text-sm font-medium"
        >
            <x-untitledui-chevron-left class="text-sh-fg-muted mr-1 -ml-1 size-5 shrink-0" aria-hidden="true" />
            {{ __('shopper::layout.back') }}
        </x-shopper::link>
    </nav>
    <nav class="hidden items-center gap-x-2 text-sm font-medium sm:flex">
        <x-shopper::link
            href="{{ route('shopper.dashboard') }}"
            class="text-sh-fg-muted hover:bg-sh-muted inline-flex items-center rounded-md p-1.5 text-sm"
        >
            <x-phosphor-monitor class="size-5" aria-hidden="true" />
        </x-shopper::link>

        {{ $slot }}

        @if ($current)
            <x-untitledui-chevron-left class="text-sh-fg-muted size-4 shrink-0" aria-hidden="true" />
            <span aria-current="page" class="bg-sh-muted text-sh-fg-secondary inline-block rounded-md px-2 py-1.5">
                {{ $current }}
            </span>
        @endif
    </nav>
</div>
