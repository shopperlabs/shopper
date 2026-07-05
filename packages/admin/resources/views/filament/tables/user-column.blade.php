@php
    $user = $getUser();
@endphp

@if ($user)
    <div class="flex min-w-0 items-center gap-2">
        <img
            class="size-8 shrink-0 rounded-full object-cover"
            src="{{ $user->picture }}"
            alt="{{ $user->full_name }}"
        />
        <span class="text-sh-fg truncate text-sm leading-5 font-medium">
            {{ $user->full_name }}
        </span>
        @if ($showsCurrentUserBadge() && $user->id === shopper()->auth()->id())
            <x-filament::badge color="gray" size="sm">
                {{ __('shopper::words.me') }}
            </x-filament::badge>
        @endif
    </div>
@endif
