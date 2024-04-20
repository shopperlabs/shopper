@props([
    'name',
    'command',
    'param' => null,
])

<li class="flex items-center justify-between px-4 py-2 text-sm text-gray-700" role="listitem">
    <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">
        {{ $name }}
    </span>
    <span class="flex items-center space-x-1 text-xs text-gray-500 dark:text-gray-400">
        <kbd
            class="inline-block rounded-md px-1.5 py-1 text-gray-700 ring-1 ring-gray-300 dark:text-gray-300 dark:ring-gray-600"
        >
            {{ $command }}
        </kbd>
        @if ($param)
            <span>+</span>
            <kbd
                class="inline-block rounded-md px-1.5 py-1 text-gray-700 ring-1 ring-gray-300 dark:text-gray-300 dark:ring-gray-600"
            >
                {{ $param }}
            </kbd>
        @endif
    </span>
</li>
