<div x-data class="w-full lg:max-w-md cursor-pointer" @click="$dispatch('toggle-spotlight')">
    <div class="relative">
        <div class="w-full pr-12 pl-4 py-2 border border-secondary-300 dark:border-secondary-700 rounded-md text-secondary-500 dark:text-secondary-400 leading-5 bg-white dark:bg-secondary-800 text-sm hover:bg-white/75 dark:hover:bg-secondary-800/75 hover:shadow-sm transition-all ease-in-out duration-150">
            {{ __('shopper::words.search') }}
        </div>
        <div class="absolute inset-y-0 right-0 flex py-1.5 pr-2 pointer-events-none">
            <kbd class="inline-flex items-center border border-secondary-300 dark:border-secondary-700 rounded-md px-2 text-sm font-sans font-medium text-secondary-500 dark:text-secondary-400">
                ⌘K
            </kbd>
        </div>
    </div>
</div>
