<x-shopper::link
    {{ $attributes->twMerge(['class' => 'group flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white transition duration-150 ease-in-out']) }}
>
    {{ $slot }}
</x-shopper::link>
