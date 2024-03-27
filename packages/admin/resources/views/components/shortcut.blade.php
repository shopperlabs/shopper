@props(['name', 'command', 'param' => null])

<li class="flex items-center justify-between text-gray-700 px-4 py-2 text-sm" role="listitem">
    <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">
        {{ $name }}
    </span>
    <span class="flex items-center text-xs text-gray-500 dark:text-gray-400 space-x-1">
        <kbd class="inline-block px-1.5 py-1 rounded-md text-gray-700 dark:text-gray-300 ring-1 ring-gray-300 dark:ring-gray-600">
            {{ $command }}
        </kbd>
        @if ($param)
            <span>+</span>
            <kbd class="inline-block px-1.5 py-1 rounded-md text-gray-700 dark:text-gray-300 ring-1 ring-gray-300 dark:ring-gray-600">
                {{ $param }}
            </kbd>
        @endif
    </span>
</li>
