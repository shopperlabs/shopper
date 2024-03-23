@props(['value' => null])

<textarea {{ $attributes->twMerge(['class' => 'block w-full rounded-lg py-1.5 border-0 text-secondary-900 ring-1 ring-inset ring-secondary-300 placeholder:text-secondary-400 dark:ring-secondary-700 dark:bg-secondary-700 dark:text-white focus:ring-2 focus:ring-primary-600 dark:focus:ring-primary-500 sm:text-sm sm:leading-6']) }} rows="4">
    {{ $value }}
</textarea>
