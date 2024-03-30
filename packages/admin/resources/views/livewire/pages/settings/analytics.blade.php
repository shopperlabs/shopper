<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.index')" :current="__('shopper::words.analytics')">
        <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.index')" :title="__('shopper::words.settings')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="my-6">
        <x-slot name="title">
            {{ __('shopper::words.analytics') }}
        </x-slot>
    </x-shopper::heading>

    <div class="mt-10 lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
        <div class="lg:col-span-1">
            <div>
                <div class="shrink-0 bg-white w-10 h-10 rounded-lg ring-1 ring-gray-200 flex items-center justify-center dark:bg-gray-800 dark:ring-gray-700">
                    <x-shopper::icons.ga class="w-6 h-6" aria-hidden="true" />
                </div>
                <h3 class="mt-5 font-heading text-lg font-medium leading-6 text-gray-900 dark:text-white">
                    {{ __('shopper::pages/settings.analytics.google') }}
                </h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('shopper::pages/settings.analytics.google_description') }}
                </p>
            </div>
        </div>
        <div class="mt-5 lg:mt-0 lg:col-span-2 max-w-3xl">
            <div class="space-y-6">
                <div class="grid grid-cols-3 gap-6">
                    <x-shopper::forms.group
                        for="google_tracking_id"
                        :label="__('shopper::layout.forms.label.ga_tracking_id')"
                        class="col-span-3">
                        <x-shopper::forms.input
                            wire:model="google_analytics_tracking_id"
                            type="text"
                            class="dark:bg-gray-800 dark:border-transparent"
                            id="google_tracking_id"
                            placeholder="UA-XXX"
                        />
                    </x-shopper::forms.group>
                    <x-shopper::forms.group
                        for="google_view_id"
                        :label="__('shopper::layout.forms.label.ga_view_id')"
                        class="col-span-3">
                        <x-shopper::forms.input
                            wire:model="google_analytics_view_id"
                            type="text"
                            class="dark:bg-gray-800 dark:border-transparent"
                            id="google_view_id"
                            placeholder="123456789"
                        />
                    </x-shopper::forms.group>
                    <x-shopper::forms.group
                        for="analytic_script"
                        :label="__('shopper::layout.forms.label.ga_additional_script')"
                        class="col-span-3">
                        <x-shopper::forms.textarea
                            wire:model="google_analytics_add_js"
                            class="dark:bg-gray-800 dark:border-transparent"
                            id="analytic_script"
                        />
                    </x-shopper::forms.group>
                </div>
                <div>
                    <label class="inline-flex items-center text-sm leading-5 font-medium text-gray-700 dark:text-gray-300">
                        {{ __('shopper::layout.forms.label.ga_json') }}
                        <a href="https://github.com/spatie/laravel-analytics#how-to-obtain-the-credentials-to-communicate-with-google-analytics"
                           target="_blank"
                           class="ml-3 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 outline-none focus:outline-none leading-4 transition duration-200 ease-in-out">
                            <x-untitledui-info-circle class="w-5 h-5" aria-hidden="true" />
                        </a>
                    </label>
                    <div class="mt-2 flex items-center">
                        <div x-data="{ focused: false }">
                            <span>
                                <input @focus="focused = true" @blur="focused = false" class="sr-only" type="file" id="json_file" wire:model="json_file">
                                <label for="json_file" :class="{ 'outline-none border-primary-300 shadow-outline-primary': focused }" class="cursor-pointer inline-flex items-center py-2 px-3 border border-gray-300 rounded-md text-sm leading-4 font-medium text-gray-700 hover:text-gray-500 shadow-sm dark:border-gray-700 dark:text-gray-400 dark:hover:text-white">
                                    <x-untitledui-file-download-02 class="h-5 w-5 mr-1.5" aria-hidden="true" />
                                    {{ __('shopper::layout.forms.actions.upload') }}
                                </label>
                            </span>
                        </div>
                        @if($credentials_json)
                            <a class="ml-4 text-sm text-gray-500 underline hover:text-gray-400 dark:text-gray-400 dark:hover:text-gray-200"
                               href="{{ \Illuminate\Support\Facades\Storage::url('analytics/service-account-credentials.json') }}">
                                {{ __('View json.') }}
                            </a>
                        @endif
                        @if(! $json_file && ! $credentials_json)
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
        <div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
            <div class="lg:col-span-1">
                <div>
                    <div class="shrink-0 bg-white w-10 h-10 rounded-lg ring-1 ring-gray-200 flex items-center justify-center dark:bg-gray-800 dark:ring-gray-700">
                        <x-shopper::icons.gtag class="h-6 w-6" aria-hidden="true" />
                    </div>
                    <h3 class="mt-5 text-lg font-medium leading-6 text-gray-900 dark:text-white font-heading">
                        {{ __('shopper::pages/settings.analytics.gtag') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/settings.analytics.gtag_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 lg:mt-0 lg:col-span-2 max-w-3xl">
                <div>
                    <x-shopper::forms.group for="google_tag_id" :label="__('shopper::layout.forms.label.gtag')">
                        <x-shopper::forms.input
                            wire:model="google_tag_manager_account_id"
                            type="text"
                            class="dark:bg-gray-800 dark:border-transparent"
                            id="google_tag_id"
                            placeholder="GTM-XXX"
                        />
                    </x-shopper::forms.group>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('shopper::components.learn_more') }}
                        <a href="https://marketingplatform.google.com/about/tag-manager" target="_blank" class="text-primary-500 hover:text-primary-400">
                            {{ __('shopper::pages/settings.analytics.gtag') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
            <div class="lg:col-span-1">
                <div>
                    <div class="shrink-0 bg-white w-10 h-10 rounded-lg ring-1 ring-gray-200 flex items-center justify-center dark:bg-gray-800 dark:ring-gray-700">
                        <x-shopper::icons.pixel class="h-6 w-6" aria-hidden="true" />
                    </div>
                    <h3 class="mt-5 text-lg font-bold leading-6 text-gray-900 dark:text-white font-heading">
                        {{ __('shopper::pages/settings.analytics.pixel') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/settings.analytics.pixel_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 lg:mt-0 lg:col-span-2 max-w-3xl">
                <div>
                    <x-shopper::forms.group for="pixel_facebook_id" :label="__('shopper::layout.forms.label.pixel_id')">
                        <x-shopper::forms.input
                            wire:model="facebook_pixel_account_id"
                            type="text"
                            class="dark:bg-gray-800 dark:border-transparent"
                            id="pixel_facebook_id"
                            placeholder="12345678"
                        />
                    </x-shopper::forms.group>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('shopper::components.learn_more') }}
                        <a href="https://www.facebook.com/business/learn/facebook-ads-pixel" target="_blank" class="text-primary-500 hover:text-primary-400">
                            {{ __('shopper::pages/settings.analytics.pixel') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-10 py-10 border-t border-gray-200 dark:border-gray-700">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.save') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</x-shopper::container>
