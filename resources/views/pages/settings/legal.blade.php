<x-shopper::layouts.setting :title="__('Legal setting')">

    <div
        x-data="{
            options: ['role', 'users', 'permissions'],
            words: {
                'refund': '{{ __('Refund policy') }}',
                'privacy': '{{ __('Privacy policy') }}',
                'terms': '{{ __('Terms of use') }}',
                'shipping': '{{ __('Shipping policy') }}'
            },
            currentTab: 'refund'
        }"
    >
        <x-shopper::breadcrumb :back="route('shopper.settings.index')">
            <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400 dark:text-secondary-500" />
            <x-shopper::breadcrumb.link :link="route('shopper.settings.index')" :title="__('shopper::words.settings')" />
        </x-shopper::breadcrumb>

        <div class="mt-3 pb-5 relative border-b border-secondary-200 space-y-4 sm:pb-0 dark:border-secondary-700">
            <div class="space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
                <h3 class="text-2xl font-bold leading-6 text-secondary-900 sm:text-3xl sm:leading-9 sm:truncate dark:text-white">
                    {{ __('Legal') }}
                </h3>
            </div>
            <div>
                <!-- Dropdown menu on small screens -->
                <div class="sm:hidden">
                    <x-shopper::forms.select x-model="currentTab" aria-label="{{ __('Selected tab') }}">
                        <template x-for="option in options" :key="option">
                            <option
                                x-bind:value="option"
                                x-text="words[option]"
                                x-bind:selected="option === currentTab"
                            ></option>
                        </template>
                    </x-shopper::forms.select>
                </div>
                <!-- Tabs at small breakpoint and up -->
                <div class="hidden sm:block">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="currentTab = 'refund'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" aria-current="page" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'refund' }">
                            {{ __('Refund policy') }}
                        </button>

                        <button @click="currentTab = 'privacy'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'privacy' }">
                            {{ __('Privacy policy') }}
                        </button>

                        <button @click="currentTab = 'terms'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'terms' }">
                            {{ __('Terms of use') }}
                        </button>

                        <button @click="currentTab = 'shipping'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'shipping' }">
                            {{ __('Shipping policy') }}
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div x-show="currentTab === 'refund'">
                <livewire:shopper-settings.legal.refund />
            </div>
            <div x-cloak x-show="currentTab === 'privacy'">
                <livewire:shopper-settings.legal.privacy />
            </div>
            <div x-cloak x-show="currentTab === 'terms'">
                <livewire:shopper-settings.legal.terms />
            </div>
            <div x-cloak x-show="currentTab === 'shipping'">
                <livewire:shopper-settings.legal.shipping />
            </div>
        </div>

        <x-shopper::learn-more :name="__('Legal')" link="legal" />
    </div>

</x-shopper::layouts.setting>
