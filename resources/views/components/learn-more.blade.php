<div class="flex justify-center text-center my-10">
    <div class="bg-secondary-50 dark:bg-secondary-800 overflow-hidden shadow-md rounded-md flex items-center">
        <div class="p-3 flex items-center justify-center shrink-0 border-r border-secondary-200 dark:border-secondary-700">
            <x-heroicon-o-information-circle class="w-5 h-5 text-secondary-500 dark:text-secondary-400" />
        </div>
        <div class="px-4 py-3 bg-white dark:bg-secondary-800 text-secondary-500 dark:text-secondary-400 text-sm leading-5">
            {{ __('Learn more about') }}
            <a href="{{ $link }}" target="_blank" class="ml-1 inline-flex items-center text-primary-600 hover:text-primary-500 transition-colors duration-150 ease-in-out">
                {{ __(ucfirst($name)) }}
                <span class="ml-1.5">
                    <x-heroicon-o-arrow-right class="w-5 h-5 text-secondary-500 dark:text-secondary-400" />
                </span>
            </a>
        </div>
    </div>
</div>
