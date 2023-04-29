@if($value)
    <a href="https://{{ $value }}" target="_blank" class="inline-flex items-center text-secondary-500 dark:text-secondary-400 hover:text-secondary-700 dark:hover:text-secondary-300 font-medium text-sm leading-5">
        {{ $value }}
        <x-heroicon-o-external-link class="w-5 h-5 -mr-1 ml-2" />
    </a>
@else
    <span class="inline-flex text-secondary-700 dark:text-secondary-500">&mdash;</span>
@endif
