<x-shopper::layouts.setting :title="__('Mail Configuration ~ Templates ~ Mailable')">

    <x-shopper::breadcrumb :back="route('shopper.settings.index')">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400 dark:text-secondary-500" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.index')" :title="__('shopper::messages.settings')" />
    </x-shopper::breadcrumb>

    <div
        x-data="{
            options: ['config', 'templates', 'mailables'],
            words: {
                'config': '{{ __('Configuration') }}',
                'templates': '{{ __('Templates') }}',
                'mailables': '{{ __('Mailables') }}'
            },
            currentTab: 'config'
        }"
        class="sm:-mx-8"
    >
        <div class="mt-3 bg-secondary-100 z-30 relative pb-5 border-b border-secondary-200 md:flex md:items-center md:justify-between dark:bg-secondary-900 dark:border-secondary-700">
            <div class="flex-1 min-w-0 sm:px-8">
                <h2 class="text-2xl font-bold leading-6 text-secondary-900 sm:text-3xl sm:leading-9 sm:truncate dark:text-white">
                    {{ __('Email') }}
                </h2>
            </div>
        </div>
        <div class="min-w-0 py-5 flex-1 lg:flex">
            <aside class="hidden lg:block lg:shrink-0">
                <div class="h-full relative flex flex-col w-80">
                    <nav aria-label="{{ __('Email menu') }}" class="min-h-(screen-content) flex-1 overflow-y-auto">
                        <ul class="space-y-1">
                            <li class="relative py-5 px-6 rounded-md hover:bg-secondary-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-600 dark:hover:bg-secondary-800" :class="{ 'bg-secondary-50 dark:bg-secondary-800': currentTab === 'config' }">
                                <div class="flex items-start justify-between space-x-3">
                                    <span class="shrink-0 text-secondary-500 dark:text-secondary-400">
                                        <x-heroicon-o-cog class="w-6 h-6"/>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <button @click="currentTab = 'config'" type="button" class="block text-left focus:outline-none">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            <p class="text-sm font-medium text-secondary-900 truncate dark:text-white">
                                                {{ __('Configuration') }}
                                            </p>
                                            <div class="mt-1">
                                                <p class="line-clamp-2 text-sm text-secondary-500 dark:text-secondary-400">
                                                    {{ __('Manage email global configuration, driver, host, port etc.') }}
                                                </p>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </li>
                            <li class="relative py-5 px-6 rounded-md hover:bg-secondary-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-600 dark:hover:bg-secondary-800" :class="{ 'bg-secondary-50 dark:bg-secondary-800': currentTab === 'templates' }">
                                <div class="flex items-start justify-between space-x-3">
                                    <span class="shrink-0 text-secondary-500 dark:text-secondary-400">
                                        <x-heroicon-o-template class="w-6 h-6" />
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <button @click="currentTab = 'templates'" type="button" class="block text-left focus:outline-none">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            <p class="inline-flex items-center text-sm font-medium text-secondary-900 truncate dark:text-white">
                                                {{ __('Templates') }}
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-800 dark:bg-secondary-700 dark:text-secondary-300">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-secondary-400 dark:text-secondary-500" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    {{ __('work in progress') }}
                                                </span>
                                            </p>
                                            <div class="mt-1">
                                                <p class="line-clamp-2 text-sm text-secondary-500 dark:text-secondary-400">
                                                    {{ __('Modify the mail templates that are sent to customers and administrators, manage email layouts.') }}
                                                </p>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </li>
                            <li class="relative py-5 px-6 rounded-md hover:bg-secondary-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-600 dark:hover:bg-secondary-800" :class="{ 'bg-secondary-50 dark:bg-secondary-800': currentTab === 'mailables' }">
                                <div class="flex items-start justify-between space-x-3">
                                    <span class="shrink-0 text-secondary-500 dark:text-secondary-400">
                                        <x-heroicon-o-mail class="w-6 h-6" />
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <button @click="currentTab = 'mailables'" type="button" class="block text-left focus:outline-none">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            <p class="text-sm font-medium text-secondary-900 truncate dark:text-white">
                                                {{ __('Mailables') }}
                                            </p>
                                            <div class="mt-1">
                                                <p class="line-clamp-2 text-sm text-secondary-500 dark:text-secondary-400">
                                                    {{ __('Create Laravel mailable class to use on your project to send email notifications.') }}
                                                </p>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>
            <section aria-labelledby="configuration-heading" class="min-w-0 flex-1 h-full flex flex-col overflow-hidden">
                <div class="min-h-(screen-content) flex-1 overflow-y-auto">
                    <div class="lg:hidden py-6 border-b border-secondary-200 sm:px-4 max-w-2xl mx-auto">
                        <x-shopper::forms.select x-model="currentTab" aria-label="Selected tab" class="block w-full pl-3 pr-10 py-2">
                            <template x-for="option in options" :key="option">
                                <option
                                    x-bind:value="option"
                                    x-text="words[option]"
                                    x-bind:selected="option === currentTab"
                                ></option>
                            </template>
                        </x-shopper::forms.select>
                    </div>

                    <div x-show="currentTab === 'config'">
                        <livewire:shopper-settings.mails.configuration />
                    </div>
                    <div x-cloak x-show="currentTab === 'templates'">
                        <livewire:shopper-settings.mails.templates />
                    </div>
                    <div x-cloak x-show="currentTab === 'mailables'">
                        <livewire:shopper-settings.mails.mailables />
                    </div>
                </div>
            </section>
        </div>
    </div>

</x-shopper::layouts.setting>
