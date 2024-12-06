<div class="my-10 flex justify-center text-center">
    <div
        class="flex items-center overflow-hidden rounded-lg bg-gray-50 shadow ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10"
    >
        <div class="flex shrink-0 items-center justify-center border-r border-gray-200 p-3 dark:border-white/10">
            <x-untitledui-info-circle class="size-5 text-gray-400 dark:text-gray-500" aria-hidden="true" />
        </div>
        <div class="bg-white px-4 py-3 text-sm leading-5 text-gray-500 dark:bg-transparent dark:text-gray-400">
            {{ __('shopper::words.learn_more') }}
            <a
                href="https://laravelshopper.dev/docs/2.x/{{ $link }}"
                target="_blank"
                class="ml-1 inline-flex items-center text-primary-600 transition-colors duration-150 ease-in-out hover:text-primary-500"
            >
                {{ $name }}
                <x-untitledui-arrow-circle-broken-right
                    class="ml-2 size-5 text-gray-400 dark:text-gray-500"
                    stroke-width="1.5"
                    aria-hidden="true"
                />
            </a>
        </div>
    </div>
</div>
