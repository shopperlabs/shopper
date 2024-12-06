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
                    <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/auth.account.empty_device') }}
                    </p>
                    <div class="mt-2 divide-y divide-gray-200 dark:divide-white/10">
                        @foreach ($this->sessions as $session)
                            <div class="flex items-center justify-between py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="shrink-0 text-gray-500 dark:text-gray-400">
                                        @if ($session->agent->isDesktop())
                                            <x-untitledui-monitor-02 class="size-8" aria-hidden="true" />
                                        @else
                                            <x-untitledui-phone class="size-8" aria-hidden="true" />
                                        @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center">
                                            <h4 class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                                                <span class="text-green-500">
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
                                                    class="ml-2 inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium leading-4 text-green-800"
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
                                        <span class="inline-flex rounded-md shadow-sm">
                                            <x-shopper::buttons.primary
                                                wire:click="$dispatch('openModal', 'shopper-modals.logout-others-browser')"
                                                wire:loading.attr="disabled"
                                                type="button"
                                            >
                                                {{ __('shopper::words.log_out') }}
                                            </x-shopper::buttons.primary>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </x-shopper::card>
            @else
                <div class="rounded-lg border-l-4 ring-1 ring-warning-100 border-warning-400 bg-warning-50 p-4 dark:ring-warning-800/50 dark:bg-warning-800/20">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg
                                class="size-5 text-warning-400"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div class="ml-3 text-warning-500">
                            <p class="text-sm leading-5">
                                {{ __('shopper::pages/auth.account.device_enabled_feature') }}
                                <a
                                    href="https://laravel.com/docs/session"
                                    target="_blank"
                                    class="font-medium underline transition duration-150 ease-in-out hover:text-warning-600"
                                >
                                    {{ __('shopper::words.learn_more') }} &rarr;
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
