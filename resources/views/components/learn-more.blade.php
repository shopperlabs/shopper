<div class="flex justify-center text-center my-10">
    <div class="bg-gray-50 dark:bg-gray-800 overflow-hidden shadow-md rounded-md flex items-center">
        <div class="p-3 flex items-center justify-center flex-shrink-0 border-r border-gray-200 dark:border-gray-700">
            <x-heroicon-o-information-circle class="w-5 h-5 text-gray-500 dark:text-gray-400" />
        </div>
        <div class="px-4 py-3 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-sm leading-5">
            {{ __('Learn more about') }}
            <a href="{{ $link }}" target="_blank" class="ml-1 inline-flex items-center text-primary-600 hover:text-primary-500 transition-colors duration-150 ease-in-out">
                {{ __(ucfirst($name)) }}
                <span class="ml-1.5">
                    <x-heroicon-o-arrow-right class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                </span>
            </a>
        </div>
    </div>
</div>
