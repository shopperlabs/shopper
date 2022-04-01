<div>
    <nav class="sm:hidden">
        <a href="{{ route($back) }}" class="flex items-center text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400 hover:text-secondary-600 dark:hover:text-secondary-500">
            <svg class="shrink-0 -ml-1 mr-1 h-5 w-5 text-secondary-400 dark:text-secondary-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            {{ __('Back') }}
        </a>
    </nav>
    <nav class="hidden sm:flex items-center text-sm leading-5 font-medium">
        {{ $slot }}
    </nav>
</div>
