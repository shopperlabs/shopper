<header class="hidden lg:block relative z-30 sticky top-0 bg-white shadow-md lg:border-t lg:border-b lg:border-gray-200 dark:bg-gray-800 lg:dark:border-gray-700">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ul class="overflow-hidden flex">
            <li class="relative overflow-hidden lg:flex-1">
                <div class="border border-gray-200 overflow-hidden border-b-0 rounded-t-md lg:border-0">
                    <button x-data @click="scrollToPosition('#step-one')" type="button" class="group text-left">
                        <div class="absolute top-0 left-0 w-1 h-full bg-transparent group-hover:bg-gray-200 group-focus:bg-gray-200 lg:w-full lg:h-1 lg:bottom-0 lg:top-auto  dark:group-hover:bg-gray-700 dark:group-focus:bg-gray-700"></div>
                        <div class="pr-6 py-5 flex items-start text-sm leading-5 font-medium space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-full">
                                    <p class="text-gray-500 dark:text-gray-400">01</p>
                                </div>
                            </div>
                            <div class="mt-0.5 min-w-0">
                                <h3 class="text-xs leading-4 font-semibold uppercase tracking-wide text-gray-900 dark:text-white">{{ __('Store information') }}</h3>
                                <p class="text-sm leading-5 font-medium text-gray-400 dark:text-gray-500">{{ __('Provide useful information for your store.') }}</p>
                            </div>
                        </div>
                    </button>
                </div>
            </li>
            <li class="relative overflow-hidden lg:flex-1">
                <div class="border border-gray-200 overflow-hidden lg:border-0">
                    <button x-data @click="scrollToPosition('#step-two')" type="button" class="group text-left">
                        <div class="absolute top-0 left-0 w-1 h-full bg-transparent group-hover:bg-gray-200 group-focus:bg-gray-200 lg:w-full lg:h-1 lg:bottom-0 lg:top-auto dark:group-hover:bg-gray-700 dark:group-focus:bg-gray-700"></div>
                        <div class="px-6 py-5 flex items-start text-sm leading-5 font-medium space-x-4 lg:pl-9">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-full">
                                    <p class="text-gray-500 dark:text-gray-400">02</p>
                                </div>
                            </div>
                            <div class="mt-0.5 min-w-0">
                                <h3 class="text-xs leading-4 font-semibold uppercase tracking-wide text-gray-900 dark:text-white">{{ __('Address Information') }}</h3>
                                <p class="text-sm leading-5 font-medium text-gray-400 dark:text-gray-500">{{ __('Provide store address information.') }}</p>
                            </div>
                        </div>
                        <div class="hidden absolute top-0 left-0 w-3 inset-0 lg:block">
                            <svg class="h-full w-full text-gray-300 dark:text-gray-700" viewBox="0 0 12 82" fill="none" preserve-aspect-ratio="none">
                                <path d="M0.5 0V31L10.5 41L0.5 51V82" stroke="currentColor" vector-effect="non-scaling-stroke" />
                            </svg>
                        </div>
                    </button>
                </div>
            </li>
            <li class="relative overflow-hidden lg:flex-1">
                <div class="border border-gray-200 overflow-hidden border-b-0 rounded-t-md lg:border-0">
                    <button x-data @click="scrollToPosition('#step-tree')" type="button" class="group text-left">
                        <div class="absolute top-0 left-0 w-1 h-full bg-transparent group-hover:bg-gray-200 group-focus:bg-gray-200 lg:w-full lg:h-1 lg:bottom-0 lg:top-auto dark:group-hover:bg-gray-700 dark:group-focus:bg-gray-700"></div>
                        <div class="py-5 flex items-start text-sm leading-5 font-medium space-x-4 pl-9">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-full">
                                    <p class="text-gray-500 dark:text-gray-400">03</p>
                                </div>
                            </div>
                            <div class="mt-0.5 min-w-0">
                                <h3 class="text-xs leading-4 font-semibold uppercase tracking-wide text-gray-900 dark:text-white">{{ __('Social Links (Optional)') }}</h3>
                                <p class="text-sm leading-5 font-medium text-gray-400 dark:text-gray-500">{{ __('Links to your social media accounts.') }}</p>
                            </div>
                        </div>
                    </button>
                    <div class="hidden absolute top-0 left-0 w-3 inset-0 lg:block">
                        <svg class="h-full w-full text-gray-300 dark:text-gray-700" viewBox="0 0 12 82" fill="none" preserve-aspect-ratio="none">
                            <path d="M0.5 0V31L10.5 41L0.5 51V82" stroke="currentColor" vector-effect="non-scaling-stroke" />
                        </svg>
                    </div>
                </div>
            </li>
        </ul>
    </nav>
</header>
