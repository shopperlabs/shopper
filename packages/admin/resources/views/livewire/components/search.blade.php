<div x-data class="w-full cursor-pointer lg:max-w-sm" @click="$dispatch('toggle-spotlight')">
    <div class="relative">
        <div
            class="w-full rounded-md border border-gray-300 bg-white py-2 pl-4 pr-12 text-sm leading-5 text-gray-500 transition-all duration-150 ease-in-out hover:bg-white/75 hover:shadow-sm dark:border-white/10 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-800/75"
        >
            {{ __('shopper::words.search') }}
        </div>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex py-1.5 pr-2">
            <kbd
                class="inline-flex items-center rounded-md border border-gray-300 px-2 font-sans text-sm font-medium text-gray-500 dark:border-white/10 dark:text-gray-400"
            >
                ⌘K
            </kbd>
        </div>
    </div>
</div>
