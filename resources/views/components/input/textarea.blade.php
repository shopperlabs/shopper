@props(['value' => null])

<textarea {{ $attributes->merge(['class' => 'block w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-700 focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 sm:text-sm']) }} rows="4">{{ $value }}</textarea>
