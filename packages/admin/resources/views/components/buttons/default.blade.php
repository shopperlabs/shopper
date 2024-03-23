@isset($link)
    <a
        href="{{ $link }}"
        {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-secondary-300 dark:border-secondary-700 shadow-sm text-sm font-medium rounded-md text-secondary-700 dark:text-white bg-white dark:bg-secondary-700 hover:bg-secondary-50 dark:hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-secondary-900']) }}
    >
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-secondary-300 dark:border-secondary-700 shadow-sm text-sm font-medium rounded-md text-secondary-700 dark:text-white bg-white dark:bg-secondary-700 hover:bg-secondary-50 dark:hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-secondary-900']) }}>
        {{ $slot }}
    </button>
@endisset
