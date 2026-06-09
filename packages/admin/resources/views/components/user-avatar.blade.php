@props([
    'user',
    'showName' => true,
])

<div class="flex items-center gap-2">
    <img class="size-8 rounded-full" src="{{ $user->picture }}" alt="{{ $user->full_name }}" />

    @if ($showName)
        <span class="text-sm font-medium text-sh-fg-secondary">
            {{ $user->full_name }}
        </span>
    @endif
</div>
