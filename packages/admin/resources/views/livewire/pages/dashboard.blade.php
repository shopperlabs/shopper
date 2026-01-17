<x-shopper::container class="py-5">
    <div class="flex items-center">
        <h2
            class="welcome font-heading text-3xl leading-8 font-bold text-gray-950 sm:truncate sm:leading-9 dark:text-white"
        >
            {{ __('shopper::pages/dashboard.welcome_message') }}
        </h2>
    </div>

    <div class="my-8 overflow-hidden rounded-xl bg-white p-1 ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-white/10">
        <div class="p-6">
            <div class="text-xl font-medium text-gray-900 dark:text-white">
                {{ __('shopper::pages/dashboard.header') }}
            </div>
            <p class="welcome-description mt-4 text-gray-500 dark:text-gray-400">
                {{ __('shopper::pages/dashboard.description') }}
            </p>
        </div>

        <div
            class="grid grid-cols-1 rounded-lg bg-gray-50 ring-1 ring-gray-200 lg:grid-cols-2 dark:bg-gray-900 dark:ring-white/10"
        >
            <div class="space-y-5 p-6">
                <div class="flex items-center">
                    <div
                        class="bg-primary-100 dark:bg-primary-800/20 flex size-10 items-center justify-center rounded-lg"
                    >
                        <x-untitledui-brackets class="text-primary-500 size-5" aria-hidden="true" />
                    </div>
                    <h4 class="font-heading ml-4 text-lg leading-6 font-semibold text-gray-900 dark:text-white">
                        {{ __('shopper::pages/dashboard.cards.doc_title') }}
                    </h4>
                </div>
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">
                        {{ __('shopper::pages/dashboard.cards.doc_description') }}
                    </div>

                    <a href="https://laravelshopper.dev" class="group" target="_blank">
                        <div class="text-primary-500 mt-5 flex items-center text-sm font-medium">
                            <span>
                                {{ __('shopper::pages/dashboard.cards.doc_link') }}
                            </span>
                            <span
                                class="text-primary-500 ml-1 translate-x-0 transform transition duration-200 ease-in-out group-hover:translate-x-1"
                            >
                                <x-untitledui-arrow-narrow-right class="size-5" aria-hidden="true" />
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="space-y-5 border-t border-gray-200 p-6 sm:border-t-0 dark:border-gray-600">
                <div class="flex items-center">
                    <div
                        class="bg-primary-100 dark:bg-primary-800/20 flex size-10 items-center justify-center rounded-lg"
                    >
                        <x-untitledui-video-recorder class="text-primary-500 size-5" aria-hidden="true" />
                    </div>
                    <h4 class="font-heading ml-4 text-lg leading-6 font-semibold text-gray-900 dark:text-white">
                        {{ __('shopper::pages/dashboard.cards.screencast_title') }}
                    </h4>
                </div>
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-300">
                        {{ __('shopper::pages/dashboard.cards.screencast_description') }}
                    </div>

                    <a href="https://www.youtube.com/channel/UCgxgoKJi3VA1eXxtjIs2tKw" target="_blank" class="group">
                        <div class="text-primary-500 mt-5 flex items-center text-sm font-medium">
                            <span>
                                {{ __('shopper::pages/dashboard.cards.screencast_link') }}
                            </span>
                            <span
                                class="text-primary-500 ml-1 translate-x-0 transform transition duration-200 ease-in-out group-hover:translate-x-1"
                            >
                                <x-untitledui-arrow-narrow-right class="size-5" aria-hidden="true" />
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="space-y-5 border-t border-gray-200 p-6 dark:border-gray-600">
                <div class="flex items-center">
                    <div
                        class="bg-primary-100 dark:bg-primary-800/20 flex size-10 items-center justify-center rounded-lg"
                    >
                        <x-untitledui-palette class="text-primary-500 size-5" aria-hidden="true" />
                    </div>
                    <h4 class="font-heading ml-4 text-lg leading-6 font-semibold text-gray-900 dark:text-white">
                        {{ __('shopper::pages/dashboard.cards.theme_title') }}
                    </h4>
                    <span
                        class="bg-primary-100 text-primary-800 dark:bg-primary-800/20 dark:text-primary-500 ml-2 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                    >
                        {{ __('shopper::words.soon') }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-300">
                        {{ __('shopper::pages/dashboard.cards.theme_description') }}
                    </p>
                </div>
            </div>

            <div class="space-y-5 border-t border-gray-200 p-6 dark:border-gray-600">
                <div class="flex items-center">
                    <div
                        class="bg-primary-100 dark:bg-primary-800/20 flex size-10 items-center justify-center rounded-lg"
                    >
                        <x-untitledui-file-plus class="text-primary-500 size-5" aria-hidden="true" />
                    </div>
                    <h4 class="font-heading ml-4 text-lg leading-6 font-semibold text-gray-900 dark:text-white">
                        {{ __('shopper::pages/dashboard.cards.product_title') }}
                    </h4>
                </div>
                <div>
                    <div class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                        {{ __('shopper::pages/dashboard.cards.product_description') }}
                    </div>

                    @can('add_products')
                        <x-shopper::link :href="route('shopper.products.index')" class="group">
                            <div class="text-primary-500 mt-3 flex items-center text-sm font-medium">
                                <span>
                                    {{ __('shopper::pages/dashboard.cards.product_link') }}
                                </span>
                                <span
                                    class="text-primary-500 ml-1 translate-x-0 transform transition duration-200 ease-in-out group-hover:translate-x-1"
                                >
                                    <x-untitledui-arrow-narrow-right class="size-5" aria-hidden="true" />
                                </span>
                            </div>
                        </x-shopper::link>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-shopper::container>
