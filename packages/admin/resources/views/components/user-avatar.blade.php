@props([
    'user',
    'showName' => true,
])

<div class="flex items-center gap-2">
    <img class="size-8 rounded-full" src="{{ $user->picture }}" alt="{{ $user->full_name }}" />

    @if ($showName)
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $user->full_name }}
        </span>
    @endif
</div>
