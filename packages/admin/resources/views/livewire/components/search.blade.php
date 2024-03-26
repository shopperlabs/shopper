<div x-data class="w-full lg:max-w-sm cursor-pointer" @click="$dispatch('toggle-spotlight')">
    <div class="relative">
        <div class="w-full pr-12 pl-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-gray-500 dark:text-gray-400 leading-5 bg-white dark:bg-gray-800 text-sm hover:bg-white/75 dark:hover:bg-gray-800/75 hover:shadow-sm transition-all ease-in-out duration-150">
            {{ __('shopper::words.search') }}
        </div>
        <div class="absolute inset-y-0 right-0 flex py-1.5 pr-2 pointer-events-none">
            <kbd class="inline-flex items-center border border-gray-300 dark:border-gray-700 rounded-md px-2 text-sm font-sans font-medium text-gray-500 dark:text-gray-400">
                ⌘K
            </kbd>
        </div>
    </div>
</div>
