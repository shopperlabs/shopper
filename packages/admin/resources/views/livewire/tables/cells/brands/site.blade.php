@if ($state)
    <a
        href="{{ $state }}"
        target="_blank"
        class="inline-flex items-center gap-2 text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
    >
        {{ $state }}
        <x-untitledui-link-external-01 class="size-5" aria-hidden="true" />
    </a>
@else
    <span class="inline-flex text-gray-700 dark:text-gray-500">&mdash;</span>
@endif
