@props(['value' => null])

<textarea {{ $attributes->merge(['class' => 'block w-full rounded-md shadow-sm border-secondary-300 dark:bg-secondary-700 dark:text-white dark:border-secondary-700 focus:ring-primary-500 focus:border-primary-500 sm:text-sm']) }} rows="4">{{ $value }}</textarea>
