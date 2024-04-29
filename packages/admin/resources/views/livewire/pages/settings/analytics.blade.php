<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.index')" :current="__('shopper::words.analytics')">
        <x-untitledui-chevron-left class="h-4 w-4 shrink-0 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.index')" :title="__('shopper::words.settings')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="my-6">
        <x-slot name="title">
            {{ __('shopper::words.analytics') }}
        </x-slot>
    </x-shopper::heading>

    <div class="mt-10 lg:grid lg:grid-cols-3 lg:gap-x-12 lg:gap-y-6">
        <div class="lg:col-span-1">
            <div>
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700"
                >
                    <x-shopper::icons.ga class="h-6 w-6" aria-hidden="true" />
                </div>
                <h3 class="mt-5 font-heading text-lg font-medium leading-6 text-gray-900 dark:text-white">
                    {{ __('shopper::pages/settings.analytics.google') }}
                </h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('shopper::pages/settings.analytics.google_description') }}
                </p>
            </div>
        </div>
        <div class="mt-5 max-w-3xl lg:col-span-2 lg:mt-0">
            <div class="space-y-6">
                <div class="grid grid-cols-3 gap-6">
                    <x-shopper::forms.group
                        for="google_tracking_id"
                        :label="__('shopper::forms.label.ga_tracking_id')"
                        class="col-span-3"
                    >
                        <x-shopper::forms.input
                            wire:model="google_analytics_tracking_id"
                            type="text"
                            class="dark:border-transparent dark:bg-gray-800"
                            id="google_tracking_id"
                            placeholder="UA-XXX"
                        />
                    </x-shopper::forms.group>
                    <x-shopper::forms.group
                        for="google_view_id"
                        :label="__('shopper::forms.label.ga_view_id')"
                        class="col-span-3"
                    >
                        <x-shopper::forms.input
                            wire:model="google_analytics_view_id"
                            type="text"
                            class="dark:border-transparent dark:bg-gray-800"
                            id="google_view_id"
                            placeholder="123456789"
                        />
                    </x-shopper::forms.group>
                    <x-shopper::forms.group
                        for="analytic_script"
                        :label="__('shopper::forms.label.ga_additional_script')"
                        class="col-span-3"
                    >
                        <x-shopper::forms.textarea
                            wire:model="google_analytics_add_js"
                            class="dark:border-transparent dark:bg-gray-800"
                            id="analytic_script"
                        />
                    </x-shopper::forms.group>
                </div>
                <div>
                    <label
                        class="inline-flex items-center text-sm font-medium leading-5 text-gray-700 dark:text-gray-300"
                    >
                        {{ __('shopper::forms.label.ga_json') }}
                        <a
                            href="https://github.com/spatie/laravel-analytics#how-to-obtain-the-credentials-to-communicate-with-google-analytics"
                            target="_blank"
                            class="ml-3 leading-4 text-gray-400 outline-none transition duration-200 ease-in-out hover:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:text-gray-400"
                        >
                            <x-untitledui-info-circle class="h-5 w-5" aria-hidden="true" />
                        </a>
                    </label>
                    <div class="mt-2 flex items-center">
                        <div x-data="{ focused: false }">
                            <span>
                                <input
                                    @focus="focused = true"
                                    @blur="focused = false"
                                    class="sr-only"
                                    type="file"
                                    id="json_file"
                                    wire:model="json_file"
                                />
                                <label
                                    for="json_file"
                                    :class="{ 'outline-none border-primary-300 shadow-outline-primary': focused }"
                                    class="inline-flex cursor-pointer items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-medium leading-4 text-gray-700 shadow-sm hover:text-gray-500 dark:border-gray-700 dark:text-gray-400 dark:hover:text-white"
                                >
                                    <x-untitledui-file-download-02 class="mr-1.5 h-5 w-5" aria-hidden="true" />
                                    {{ __('shopper::forms.actions.upload') }}
                                </label>
                            </span>
                        </div>
                        @if ($credentials_json)
                            <a
                                class="ml-4 text-sm text-gray-500 underline hover:text-gray-400 dark:text-gray-400 dark:hover:text-gray-200"
                                href="{{ \Illuminate\Support\Facades\Storage::url('analytics/service-account-credentials.json') }}"
                            >
                                {{ __('View json.') }}
                            </a>
                        @endif

                        @if (! $json_file && ! $credentials_json)
                            <span class="ml-4 text-sm text-gray-400 dark:text-gray-500">
                                {{ __('shopper::pages/settings.analytics.no_json') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div class="lg:grid lg:grid-cols-3 lg:gap-x-12 lg:gap-y-6">
            <div class="lg:col-span-1">
                <div>
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700"
                    >
                        <x-shopper::icons.gtag class="h-6 w-6" aria-hidden="true" />
                    </div>
                    <h3 class="mt-5 font-heading text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        {{ __('shopper::pages/settings.analytics.gtag') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/settings.analytics.gtag_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 max-w-3xl lg:col-span-2 lg:mt-0">
                <div>
                    <x-shopper::forms.group for="google_tag_id" :label="__('shopper::forms.label.gtag')">
                        <x-shopper::forms.input
                            wire:model="google_tag_manager_account_id"
                            type="text"
                            class="dark:border-transparent dark:bg-gray-800"
                            id="google_tag_id"
                            placeholder="GTM-XXX"
                        />
                    </x-shopper::forms.group>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('shopper::components.learn_more') }}
                        <a
                            href="https://marketingplatform.google.com/about/tag-manager"
                            target="_blank"
                            class="text-primary-500 hover:text-primary-400"
                        >
                            {{ __('shopper::pages/settings.analytics.gtag') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div class="lg:grid lg:grid-cols-3 lg:gap-x-12 lg:gap-y-6">
            <div class="lg:col-span-1">
                <div>
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700"
                    >
                        <x-shopper::icons.pixel class="h-6 w-6" aria-hidden="true" />
                    </div>
                    <h3 class="mt-5 font-heading text-lg font-bold leading-6 text-gray-900 dark:text-white">
                        {{ __('shopper::pages/settings.analytics.pixel') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/settings.analytics.pixel_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 max-w-3xl lg:col-span-2 lg:mt-0">
                <div>
                    <x-shopper::forms.group for="pixel_facebook_id" :label="__('shopper::forms.label.pixel_id')">
                        <x-shopper::forms.input
                            wire:model="facebook_pixel_account_id"
                            type="text"
                            class="dark:border-transparent dark:bg-gray-800"
                            id="pixel_facebook_id"
                            placeholder="12345678"
                        />
                    </x-shopper::forms.group>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('shopper::components.learn_more') }}
                        <a
                            href="https://www.facebook.com/business/learn/facebook-ads-pixel"
                            target="_blank"
                            class="text-primary-500 hover:text-primary-400"
                        >
                            {{ __('shopper::pages/settings.analytics.pixel') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-10 border-t border-gray-200 py-10 dark:border-gray-700">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::forms.actions.save') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</x-shopper::container>
