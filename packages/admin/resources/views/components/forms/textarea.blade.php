@props(['value' => null])

<textarea
    {{ $attributes->twMerge(['class' => 'block w-full rounded-lg py-2 px-3 border-0 text-gray-900 ring-1 ring-gray-200 placeholder:text-gray-400 dark:ring-white/10 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-primary-600 dark:focus:ring-primary-500 sm:text-sm sm:leading-6']) }}
    rows="3"
>
    {{ $value }}
</textarea>
