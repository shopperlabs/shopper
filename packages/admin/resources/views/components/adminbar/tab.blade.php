<button
    x-on:click="barOpen = true;"
    :class="{ 'w-3 delay-300 translate-x-0' : !barOpen, '-translate-x-full' : barOpen }"
    class="group fixed top-0 left-0 z-50 flex h-8 translate-y-2 flex-col items-center justify-center overflow-hidden rounded-r-md bg-gray-900 duration-100 ease-out dark:bg-gray-50 dark:text-gray-700 dark:hover:bg-white"
>
    <svg
        :class="{ 'translate-x-full ml-4 opacity-0' : barOpen }"
        class="absolute size-5 shrink-0 -translate-x-px scale-100 opacity-60 duration-300 ease-out group-hover:opacity-100"
        viewBox="0 0 20 20"
        fill="currentColor"
        aria-hidden="true"
    >
        <path
            fill-rule="evenodd"
            d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
            clip-rule="evenodd"
        />
    </svg>
</button>
