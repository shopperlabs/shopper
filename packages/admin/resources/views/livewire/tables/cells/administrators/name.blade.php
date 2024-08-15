@php
    $user = $getRecord();
@endphp

<div class="flex items-center">
    <div class="size-10 shrink-0">
        <img class="size-10 rounded-full" src="{{ $user->picture }}" alt="{{ $user->last_name }} avatar" />
    </div>
    <div class="ml-4">
        <div class="flex items-center gap-2">
            <span class="text-sm font-medium leading-5">
                {{ $user->full_name }}
            </span>
            @if ($user->id ===shopper()->auth()->id())
                <x-filament::badge icon="untitledui-user-circle" color="gray" size="sm">
                    {{ __('shopper::words.me') }}
                </x-filament::badge>
            @endif
        </div>
        <div class="text-sm leading-5 text-gray-500 dark:text-gray-400">
            {{ __('shopper::words.registered_on') }}
            <time datetime="{{ $user->created_at->format('Y-m-d') }}" class="capitalize">
                {{ $user->created_at->translatedFormat('j F Y') }}
            </time>
        </div>
    </div>
</div>
