@props(['back'])

<div {{ $attributes }}>
    <nav class="sm:hidden">
        <a href="{{ $back }}" class="flex items-center text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400 hover:text-secondary-600 dark:hover:text-secondary-500">
            <x-heroicon-s-chevron-left class="shrink-0 -ml-1 mr-1 h-5 w-5 text-secondary-400 dark:text-secondary-500" />
            {{ __('shopper::layout.back') }}
        </a>
    </nav>
    <nav class="hidden sm:flex items-center text-sm leading-5 font-medium">
        {{ $slot }}
    </nav>
</div>
