<div>
    <div class="flex items-center space-x-2">
        <svg class="h-8 w-8 text-secondary-500" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.8 3.6a1.2 1.2 0 1 1 2.4 0v6.6a.6.6 0 1 0 1.2 0V4.8a1.2 1.2 0 1 1 2.4 0v5.4a.6.6 0 1 0 1.2 0v-3a1.2 1.2 0 1 1 2.4 0v6a8.401 8.401 0 0 1-16.16 3.215A8.4 8.4 0 0 1 3.6 13.2v-2.4a1.2 1.2 0 0 1 2.4 0v3a.6.6 0 1 0 1.2 0v-9a1.2 1.2 0 0 1 2.4 0v5.4a.6.6 0 1 0 1.2 0V3.6z" />
        </svg>
        <h2 class="welcome text-3xl leading-8 font-bold text-secondary-900 dark:text-white sm:leading-9 sm:truncate">
            {!! __('shopper::messages.dashboard.welcome_message') !!}
        </h2>
    </div>

    <div class="my-8 bg-white dark:bg-secondary-800 overflow-hidden shadow-lg rounded-lg">
        <div class="p-6 border-b border-secondary-200 dark:border-secondary-700">
            <div class="text-xl text-secondary-900 dark:text-white font-medium">
                {{ __('shopper::messages.dashboard.header') }}
            </div>

            <div class="welcome-description mt-4 text-secondary-500 dark:text-secondary-400">
                {!! __('shopper::messages.dashboard.description') !!}
            </div>
        </div>

        <div class="bg-secondary-50 dark:bg-secondary-700 grid grid-cols-1 md:grid-cols-2">
            <div class="px-6 py-5">
                <div class="flex items-center">
                    <x-heroicon-o-code class="w-6 h-6 text-primary-600" />
                    <h4 class="ml-4 text-base text-secondary-900 dark:text-white leading-6 font-medium">
                        {{ __('shopper::messages.dashboard.cards.doc_title') }}
                    </h4>
                </div>

                <div class="ml-10">
                    <div class="mt-4 text-sm text-secondary-500 dark:text-secondary-400">{{ __('shopper::messages.dashboard.cards.doc_description') }}</div>

                    <a href="https://laravelshopper.dev" class="group">
                        <div class="mt-3 flex items-center text-sm font-medium text-primary-600">
                            <span>{{ __('shopper::messages.dashboard.cards.doc_link') }}</span>

                            <span class="ml-1 text-primary-500 transform translate-x-0 group-hover:translate-x-1 transition duration-200 ease-in-out">
                                <x-heroicon-o-arrow-narrow-right class="w-5 h-5" />
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="px-6 py-5 border-t border-secondary-200 dark:border-secondary-600 sm:border-t-0">
                <div class="flex items-center">
                    <x-heroicon-o-film class="w-6 h-6 text-primary-600" />
                    <h4 class="ml-4 text-base text-secondary-900 dark:text-white leading-6 font-medium">{{ __('shopper::messages.dashboard.cards.screencast_title') }}</h4>
                </div>

                <div class="ml-10">
                    <div class="mt-4 text-sm text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::messages.dashboard.cards.screencast_description') }}
                    </div>

                    <a href="https://www.youtube.com/channel/UCgxgoKJi3VA1eXxtjIs2tKw" class="group">
                        <div class="mt-3 flex items-center text-sm font-medium text-primary-600">
                            <span>{{ __('shopper::messages.dashboard.cards.screencast_link') }}</span>

                            <span class="ml-1 text-primary-500 transform translate-x-0 group-hover:translate-x-1 transition duration-200 ease-in-out">
                                <x-heroicon-o-arrow-narrow-right class="w-5 h-5" />
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="px-6 py-5 border-t border-secondary-200 dark:border-secondary-600">
                <div class="flex items-center">
                    <x-heroicon-o-color-swatch class="w-6 h-6 text-primary-600" />
                    <h4 class="ml-4 text-base text-secondary-900 dark:text-white leading-6 font-medium">{{ __('shopper::messages.dashboard.cards.theme_title') }}</h4>
                    <span class="ml-1.5 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                      {{ __('shopper::layout.soon') }}
                    </span>
                </div>

                <div class="ml-10">
                    <div class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::messages.dashboard.cards.theme_description') }}
                    </div>
                </div>
            </div>

            <div class="px-6 py-5 border-t border-secondary-200 dark:border-secondary-600">
                <div class="flex items-center">
                    <x-heroicon-o-book-open class="w-6 h-6 text-primary-600" />
                    <h4 class="ml-4 text-base text-secondary-900 dark:text-white leading-6 font-medium">{{ __('shopper::messages.dashboard.cards.product_title') }}</h4>
                </div>

                <div class="ml-10">
                    <div class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::messages.dashboard.cards.product_description') }}
                    </div>

                    @can('add_products')
                        <a href="{{ route('shopper.products.create') }}" class="group">
                            <div class="mt-3 flex items-center text-sm font-medium text-primary-600">
                                <span>{{ __('shopper::messages.dashboard.cards.product_link') }}</span>

                                <span class="ml-1 text-primary-500 transform translate-x-0 group-hover:translate-x-1 transition duration-200 ease-in-out">
                                    <x-heroicon-o-arrow-narrow-right class="w-5 h-5" />
                                </span>
                            </div>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
