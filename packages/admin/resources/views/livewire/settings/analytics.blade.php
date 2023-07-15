<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.index')" :current="__('shopper::words.analytics')">
        <x-heroicon-s-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.index')" :title="__('shopper::words.settings')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-5">
        <x-slot name="title">
            {{ __('shopper::words.analytics') }}
        </x-slot>
    </x-shopper::heading>

    <div class="mt-8 lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
        <div class="lg:col-span-1">
            <div>
                <div class="shrink-0 bg-white w-10 h-10 rounded flex items-center justify-center dark:bg-secondary-800">
                    <x-shopper::icons.ga class="w-6 h-6" />
                </div>
                <h3 class="mt-5 text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                    {{ __('shopper::pages/settings.analytics.google') }}
                </h3>
                <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('shopper::pages/settings.analytics.google_description') }}
                </p>
            </div>
        </div>
        <div class="mt-5 lg:mt-0 lg:col-span-2 max-w-3xl">
            <div class="space-y-6">
                <div class="grid grid-cols-3 gap-6">
                    <x-shopper::forms.group for="google_tracking_id" :label="__('shopper::layout.forms.label.ga_tracking_id')" class="col-span-3">
                        <x-shopper::forms.input wire:model.defer="google_analytics_tracking_id" type="text" class="dark:bg-secondary-800 dark:border-transparent" id="google_tracking_id" placeholder="UA-XXX" />
                    </x-shopper::forms.group>
                    <x-shopper::forms.group for="google_view_id" :label="__('shopper::layout.forms.label.ga_view_id')" class="col-span-3">
                        <x-shopper::forms.input wire:model.defer="google_analytics_view_id" type="text" class="dark:bg-secondary-800 dark:border-transparent" id="google_view_id" placeholder="123456789" />
                    </x-shopper::forms.group>
                    <x-shopper::forms.group for="analytic_script" :label="__('shopper::layout.forms.label.ga_additional_script')" class="col-span-3">
                        <x-shopper::forms.textarea wire:model.defer="google_analytics_add_js" class="dark:bg-secondary-800 dark:border-transparent" id="analytic_script" />
                    </x-shopper::forms.group>
                </div>
                <div>
                    <label class="inline-flex items-center text-sm leading-5 font-medium text-secondary-700 dark:text-secondary-300">
                        {{ __('shopper::layout.forms.label.ga_json') }}
                        <a href="https://github.com/spatie/laravel-analytics#how-to-obtain-the-credentials-to-communicate-with-google-analytics" target="_blank" class="ml-3 text-secondary-400 hover:text-secondary-500 outline-none focus:outline-none leading-4 transition duration-200 ease-in-out">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </label>
                    <div class="mt-2 flex items-center">
                        <div x-data="{ focused: false }">
                            <span>
                                <input @focus="focused = true" @blur="focused = false" class="sr-only" type="file" id="json_file" wire:model="json_file">
                                <label for="json_file" :class="{ 'outline-none border-primary-300 shadow-outline-primary': focused }" class="cursor-pointer inline-flex items-center py-2 px-3 border border-secondary-300 rounded-md text-sm leading-4 font-medium text-secondary-700 hover:text-secondary-500 shadow-sm dark:border-secondary-700 dark:text-secondary-400 dark:hover:text-white">
                                    <x-heroicon-o-download class="h-5 w-5 mr-1.5" />
                                    {{ __('shopper::layout.forms.actions.upload') }}
                                </label>
                            </span>
                        </div>
                        @if($credentials_json)
                            <a class="ml-4 text-sm leading-5 text-secondary-500 underline hover:text-secondary-400 dark:text-secondary-400 dark:hover:text-secondary-200" href="{{ \Illuminate\Support\Facades\Storage::url('analytics/service-account-credentials.json') }}">{{ __('View json.') }}</a>
                        @endif
                        @if(! $json_file && ! $credentials_json)
                            <span class="ml-4 text-sm leading-5 text-secondary-400 dark:text-secondary-500">
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
                    <div class="shrink-0 bg-white w-10 h-10 rounded flex items-center justify-center dark:bg-secondary-800">
                        <x-shopper::icons.gtag class="h-6 w-6" />
                    </div>
                    <h3 class="mt-5 text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                        {{ __('shopper::pages/settings.analytics.gtag') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/settings.analytics.gtag_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 lg:mt-0 lg:col-span-2 max-w-3xl">
                <div>
                    <x-shopper::forms.group for="google_tag_id" :label="__('shopper::layout.forms.label.gtag')">
                        <x-shopper::forms.input wire:model="google_tag_manager_account_id" type="text" class="dark:bg-secondary-800 dark:border-transparent" id="google_tag_id" placeholder="GTM-XXX" />
                    </x-shopper::forms.group>
                    <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">
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
                    <div class="shrink-0 bg-white w-10 h-10 rounded flex items-center justify-center dark:bg-secondary-800">
                        <x-shopper::icons.pixel class="h-6 w-6" />
                    </div>
                    <h3 class="mt-5 text-lg font-bold leading-6 text-secondary-900 dark:text-white font-display">
                        {{ __('shopper::pages/settings.analytics.pixel') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/settings.analytics.pixel_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 lg:mt-0 lg:col-span-2 max-w-3xl">
                <div>
                    <x-shopper::forms.group for="pixel_facebook_id" :label="__('shopper::layout.forms.label.pixel_id')">
                        <x-shopper::forms.input wire:model="facebook_pixel_account_id" type="text" class="dark:bg-secondary-800 dark:border-transparent" id="pixel_facebook_id" placeholder="12345678" />
                    </x-shopper::forms.group>
                    <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::components.learn_more') }}
                        <a href="https://www.facebook.com/business/learn/facebook-ads-pixel" target="_blank" class="text-primary-500 hover:text-primary-400">
                            {{ __('shopper::pages/settings.analytics.pixel') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 pt-5 pb-10 border-t border-secondary-200 dark:border-secondary-700">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.save') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</x-shopper::container>
