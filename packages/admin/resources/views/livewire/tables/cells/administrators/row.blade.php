@php
    $user = $getRecord();
@endphp

<div class="flex items-center">
    <div class="size-8 shrink-0">
        <img class="size-8 rounded-full" src="{{ $user->picture }}" alt="{{ $user->last_name }} avatar" />
    </div>
    <div class="ml-3 flex items-center gap-2">
        <span class="text-sm leading-5 font-medium">
            {{ $user->full_name }}
        </span>
        @if ($user->id === shopper()->auth()->id())
            <x-filament::badge icon="untitledui-user-circle" color="gray" size="sm">
                {{ __('shopper::words.me') }}
            </x-filament::badge>
        @endif
    </div>
</div>
