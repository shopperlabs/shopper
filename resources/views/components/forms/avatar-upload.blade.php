<div class="flex items-center">
    {{ $slot }}

    <div x-data="{ focused: false }">
        <span class="ml-5">
            <input @focus="focused = true" @blur="focused = false" class="sr-only" type="file" {{ $attributes }}>
            <label for="{{ $attributes['id'] }}" :class="{ 'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500': focused }" class="cursor-pointer inline-flex py-2 px-3 border border-secondary-300 dark:border-secondary-700 rounded-md text-sm leading-4 font-medium text-secondary-700 dark:bg-secondary-700 dark:text-secondary-400 hover:text-secondary-600 dark:hover:text-secondary-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                {{ __('Change') }}
            </label>
        </span>
    </div>
</div>
