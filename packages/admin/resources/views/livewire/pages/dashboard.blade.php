<x-shopper::container>
    <div class="flex items-center">
        <h2 class="welcome font-heading text-3xl leading-8 font-bold text-secondary-900 dark:text-white sm:leading-9 sm:truncate">
            {!! __('shopper::messages.dashboard.welcome_message') !!}
        </h2>
    </div>

    <div class="my-8 bg-white border border-secondary-200 dark:border-secondary-700 dark:bg-secondary-800 overflow-hidden rounded-lg">
        <div class="p-6 border-b border-secondary-200 dark:border-secondary-700">
            <div class="text-xl text-secondary-900 dark:text-white font-medium">
                {{ __('shopper::messages.dashboard.header') }}
            </div>
            <p class="welcome-description mt-4 text-secondary-500 dark:text-secondary-400">
                {!! __('shopper::messages.dashboard.description') !!}
            </p>
        </div>

        <div class="bg-secondary-50 dark:bg-secondary-800 grid grid-cols-1 lg:grid-cols-2">
            <div class="p-6 space-y-5">
                <div class="flex items-center">
                    <div class="flex items-center justify-center h-10 w-10 bg-primary-100 dark:bg-primary-800/20 rounded-lg">
                        <x-untitledui-brackets class="w-5 h-5 text-primary-500" />
                    </div>
                    <h4 class="ml-4 font-heading text-lg text-secondary-900 leading-6 font-semibold dark:text-white">
                        {{ __('shopper::messages.dashboard.cards.doc_title') }}
                    </h4>
                </div>
                <div>
                    <div class="text-sm text-secondary-500 dark:text-secondary-300">
                        {{ __('shopper::messages.dashboard.cards.doc_description') }}
                    </div>

                    <a href="https://laravelshopper.dev" class="group">
                        <div class="mt-5 flex items-center text-sm font-medium text-primary-500">
                            <span>{{ __('shopper::messages.dashboard.cards.doc_link') }}</span>
                            <span class="ml-1 text-primary-500 transform translate-x-0 group-hover:translate-x-1 transition duration-200 ease-in-out">
                                <x-untitledui-arrow-right class="w-5 h-5" aria-hidden="true" />
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="p-6 space-y-5 border-t border-secondary-200 dark:border-secondary-600 sm:border-t-0">
                <div class="flex items-center">
                    <div class="flex items-center justify-center h-10 w-10 bg-primary-100 dark:bg-primary-800/20 rounded-lg">
                        <x-untitledui-video-recorder class="w-5 h-5 text-primary-500" aria-hidden="true" />
                    </div>
                    <h4 class="ml-4 font-heading text-lg text-secondary-900 leading-6 font-semibold dark:text-white">
                        {{ __('shopper::messages.dashboard.cards.screencast_title') }}
                    </h4>
                </div>
                <div>
                    <div class="text-sm text-secondary-500 dark:text-secondary-300">
                        {{ __('shopper::messages.dashboard.cards.screencast_description') }}
                    </div>

                    <a href="https://www.youtube.com/channel/UCgxgoKJi3VA1eXxtjIs2tKw" class="group">
                        <div class="mt-5 flex items-center text-sm font-medium text-primary-500">
                            <span>{{ __('shopper::messages.dashboard.cards.screencast_link') }}</span>
                            <span class="ml-1 text-primary-500 transform translate-x-0 group-hover:translate-x-1 transition duration-200 ease-in-out">
                                <x-untitledui-arrow-right class="w-5 h-5" />
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="p-6 space-y-5 border-t border-secondary-200 dark:border-secondary-600">
                <div class="flex items-center">
                    <div class="flex items-center justify-center h-10 w-10 bg-primary-100 dark:bg-primary-800/20 rounded-lg">
                        <x-untitledui-palette class="w-5 h-5 text-primary-500" aria-hidden="true" />
                    </div>
                    <h4 class="ml-4 font-heading text-lg text-secondary-900 leading-6 font-semibold dark:text-white">
                        {{ __('shopper::messages.dashboard.cards.theme_title') }}
                    </h4>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-800/20 dark:text-primary-500">
                      {{ __('shopper::layout.soon') }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-secondary-500 dark:text-secondary-300">
                        {{ __('shopper::messages.dashboard.cards.theme_description') }}
                    </p>
                </div>
            </div>

            <div class="p-6 space-y-5 border-t border-secondary-200 dark:border-secondary-600">
                <div class="flex items-center">
                    <div class="flex items-center justify-center h-10 w-10 bg-primary-100 dark:bg-primary-800/20 rounded-lg">
                        <x-untitledui-file-plus class="w-5 h-5 text-primary-500" aria-hidden="true" />
                    </div>
                    <h4 class="ml-4 font-heading text-lg text-secondary-900 leading-6 font-semibold dark:text-white">
                        {{ __('shopper::messages.dashboard.cards.product_title') }}
                    </h4>
                </div>
                <div>
                    <div class="mt-2 text-sm text-secondary-500 dark:text-secondary-300">
                        {{ __('shopper::messages.dashboard.cards.product_description') }}
                    </div>

                    @if(\Shopper\Feature::enabled('product'))
                        @can('add_products')
                            <a href="{{ route('shopper.products.create') }}" class="group">
                                <div class="mt-3 flex items-center text-sm font-medium text-primary-500">
                                    <span>{{ __('shopper::messages.dashboard.cards.product_link') }}</span>
                                    <span class="ml-1 text-primary-500 transform translate-x-0 group-hover:translate-x-1 transition duration-200 ease-in-out">
                                        <x-untitledui-arrow-right class="w-5 h-5" aria-hidden="true" />
                                    </span>
                                </div>
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-shopper::container>
