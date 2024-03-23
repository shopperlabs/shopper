<div class="flex rounded-md shadow-sm">
    <input
        wire:model="columnSearch.{{ $field }}"
        placeholder="{{ __('Search by :field', ['field' => $field]) }}"
        type="text"
        @class([
            'block w-full border-secondary-300 shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 dark:bg-secondary-700 dark:text-white dark:border-secondary-600',
            'rounded-none rounded-l-md focus:ring-0 focus:border-secondary-300' => isset($columnSearch[$field]) && strlen($columnSearch[$field]),
            'focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 rounded-md' => !(isset($columnSearch[$field]) && strlen($columnSearch[$field])),
        ])
    />

    @if (isset($columnSearch[$field]) && strlen($columnSearch[$field]))
        <span wire:click="$set('columnSearch.{{ $field }}', null)" class="inline-flex items-center px-3 text-secondary-500 bg-secondary-50 rounded-r-md border border-l-0 border-secondary-300 cursor-pointer sm:text-sm dark:bg-secondary-700 dark:text-white dark:border-secondary-600 dark:hover:bg-secondary-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </span>
    @endif
</div>
