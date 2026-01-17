<div class="mt-10 pb-10 sm:mt-0">
    <div class="lg:grid lg:grid-cols-3 lg:gap-x-12 lg:gap-y-6">
        <div class="lg:col-span-1">
            <x-shopper::section-heading
                :title="__('shopper::pages/auth.account.device_title')"
                :description="__('shopper::pages/auth.account.device_description')"
            />
        </div>
        <div class="mt-5 lg:col-span-2 lg:mt-0 lg:max-w-3xl">
            @if (count($this->sessions) > 0)
                <x-shopper::card class="px-4 py-5 sm:px-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/auth.account.empty_device') }}
                    </p>
                    <div class="mt-2 divide-y divide-gray-200 dark:divide-white/20">
                        @foreach ($this->sessions as $session)
                            <div class="flex items-center justify-between py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="shrink-0 text-gray-500 dark:text-gray-400">
                                        @if ($session->agent->isDesktop())
                                            <x-untitledui-monitor-02 class="size-6" aria-hidden="true" />
                                        @else
                                            <x-untitledui-phone class="size-6" aria-hidden="true" />
                                        @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center">
                                            <h4 class="text-sm text-gray-500 dark:text-gray-400">
                                                <span class="text-success-600 dark:text-success-400">
                                                    {{
                                                        __('shopper::words.browser_platform', [
                                                            'browser' => $session->agent->browser(),
                                                            'platform' => $session->agent->platform(),
                                                        ])
                                                    }}
                                                </span>
                                                - {{ $session->ip_address }}
                                            </h4>
                                            @if ($session->is_current_device)
                                                <span
                                                    class="ml-2 inline-flex items-center rounded-md bg-success-100 px-2.5 py-0.5 text-xs leading-4 font-medium text-success-800"
                                                >
                                                    {{ __('shopper::pages/auth.account.current_device') }}
                                                </span>
                                            @else
                                                <span class="ml-2 text-xs text-gray-400 dark:text-gray-500">
                                                    {{ __('shopper::pages/auth.account.device_last_activity') }}
                                                    {{ $session->last_active }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="mt-0.5 text-sm leading-4 text-gray-500 dark:text-gray-400">
                                            @if ($session->location)
                                                {{ $session->location->cityName }},
                                                {{ $session->location->regionName }},
                                                {{ $session->location->countryName }}
                                            @else
                                                {{ __('shopper::pages/auth.account.device_location') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                @if (! $session->is_current_device)
                                    <div class="ml-4">
                                        {{ $this->logoutOtherBrowsersAction }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </x-shopper::card>
            @else
                <div
                    class="border-warning-400 bg-warning-50 ring-warning-100 dark:bg-warning-800/20 dark:ring-warning-800/50 rounded-lg border-l-4 p-4 ring-1"
                >
                    <div class="flex">
                        <div class="shrink-0">
                            <x-untitledui-alert-triangle class="text-warning-400 size-5" aria-hidden="true" />
                        </div>
                        <div class="text-warning-500 ml-3">
                            <p class="text-sm">
                                {{ __('shopper::pages/auth.account.device_enabled_feature') }}
                                <a
                                    href="https://laravel.com/docs/session"
                                    target="_blank"
                                    class="hover:text-warning-600 font-medium underline transition duration-150 ease-in-out"
                                >
                                    {{ __('shopper::words.learn_more') }}
                                    &rarr;
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <x-filament-actions::modals />
</div>
