@props(['back', 'current' => null])

<div {{ $attributes }}>
    <nav class="sm:hidden">
        <a href="{{ $back }}" class="flex items-center text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400 hover:text-secondary-600 dark:hover:text-secondary-500">
            <x-heroicon-o-arrow-narrow-left class="shrink-0 -ml-1 mr-1 h-5 w-5 text-secondary-400 dark:text-secondary-500" />
            {{ __('shopper::layout.back') }}
        </a>
    </nav>
    <nav class="hidden sm:flex items-center text-sm leading-5 font-medium gap-x-2">
        <a href="{{ route('shopper.dashboard') }}" class="inline-flex items-center p-1.5 rounded-md text-sm leading-5 text-secondary-500 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-secondary-800">
            <x-shopper::icons.home class="h-5 w-5" />
        </a>

        {{ $slot }}

        @if($current)
            <x-heroicon-s-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
            <span aria-current="page" class="inline-block py-1.5 px-2 bg-secondary-50 dark:bg-secondary-800 text-secondary-700 dark:text-secondary-300 rounded-md">
                {{ $current }}
            </span>
        @endif
    </nav>
</div>
