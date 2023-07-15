@props(['name', 'command', 'param' => null])

<li class="flex items-center justify-between text-gray-700 px-4 py-2 text-sm" role="listitem">
    <span class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ $name }}</span>
    <span class="flex items-center text-xs text-secondary-500 dark:text-secondary-400 space-x-1">
        <kbd class="inline-block px-1.5 py-1 rounded-md text-secondary-700 dark:text-secondary-300 ring-1 ring-secondary-300 dark:ring-secondary-600">
            {{ $command }}
        </kbd>
        @if ($param)
            <span>+</span>
            <kbd class="inline-block px-1.5 py-1 rounded-md text-secondary-700 dark:text-secondary-300 ring-1 ring-secondary-300 dark:ring-secondary-600">
                {{ $param }}
            </kbd>
        @endif
    </span>
</li>
