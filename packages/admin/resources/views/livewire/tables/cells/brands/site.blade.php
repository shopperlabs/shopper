@if ($state)
    <a
        href="{{ $state }}"
        target="_blank"
        class="inline-flex items-center text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
    >
        {{ $state }}
        <x-untitledui-link-external-01 class="-mr-1 ml-2 h-5 w-5" />
    </a>
@else
    <span class="inline-flex text-gray-700 dark:text-gray-500">&mdash;</span>
@endif
