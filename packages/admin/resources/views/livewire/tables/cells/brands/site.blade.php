@if($value)
    <a href="https://{{ $value }}" target="_blank" class="inline-flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 font-medium text-sm leading-5">
        {{ $value }}
        <x-untitledui-link-external-01 class="w-5 h-5 -mr-1 ml-2" />
    </a>
@else
    <span class="inline-flex text-gray-700 dark:text-gray-500">&mdash;</span>
@endif
