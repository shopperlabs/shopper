<button
    {{ $attributes->twMerge(['class' => 'inline-flex items-center justify-center rounded-lg px-4 py-2 bg-danger-600 text-sm font-medium text-white shadow-sm hover:bg-danger-500 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 focus:ring-danger-500 disabled:cursor-not-allowed disabled:opacity-75']) }}
>
    {{ $slot }}
</button>
