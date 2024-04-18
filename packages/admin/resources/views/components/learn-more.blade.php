<div class="flex justify-center text-center my-10">
    <div class="bg-gray-50 dark:bg-gray-800 overflow-hidden ring-1 ring-gray-200 dark:ring-gray-700 shadow rounded-lg flex items-center">
        <div class="p-3 flex items-center justify-center shrink-0 border-r border-gray-200 dark:border-gray-700">
            <x-untitledui-info-circle
                class="w-5 h-5 text-gray-400 dark:text-gray-500"
                aria-hidden="true"
            />
        </div>
        <div class="px-4 py-3 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-sm leading-5">
            {{ __('shopper::components.learn_more') }}
            <a href="https://laravelshopper.dev/docs/2.x/{{ $link }}" target="_blank" class="ml-1 inline-flex items-center text-primary-600 hover:text-primary-500 transition-colors duration-150 ease-in-out">
                {{ $name }}
                <x-untitledui-arrow-circle-broken-right
                    class="w-5 h-5 ml-2 text-gray-400 dark:text-gray-500"
                    stroke-width="1.5"
                    aria-hidden="true"
                />
            </a>
        </div>
    </div>
</div>
