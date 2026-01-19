<div class="my-10 flex justify-center text-center">
    <div
        class="flex items-center overflow-hidden rounded-lg bg-gray-50 shadow ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/20"
    >
        <div class="flex shrink-0 items-center justify-center border-r border-gray-200 p-3 dark:border-white/20">
            <x-untitledui-info-circle class="size-5 text-gray-400 dark:text-gray-500" aria-hidden="true" />
        </div>
        <div class="bg-white px-4 py-3 text-sm text-gray-500 dark:bg-transparent dark:text-gray-400">
            {{ __('shopper::words.learn_more') }}
            <a
                href="https://docs.laravelshopper.dev/{{ shopper()->version() }}/{{ $link }}"
                target="_blank"
                class="text-primary-600 hover:text-primary-500 ml-1 inline-flex items-center"
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
