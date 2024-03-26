<div
    x-data="{
        options: ['privacy', 'terms', 'shipping', 'refund'],
        words: {
            'privacy': '{{ __('shopper::pages/settings.legal.privacy') }}',
            'terms': '{{ __('shopper::pages/settings.legal.terms_of_use') }}',
            'shipping': '{{ __('shopper::pages/settings.legal.shipping') }}',
            'refund': '{{ __('shopper::pages/settings.legal.refund') }}',
        },
        currentTab: 'privacy',
        activeTab(tab) {
            return this.currentTab === tab;
        },
    }"
>
    <x-shopper::container>
        <x-shopper::breadcrumb :back="route('shopper.settings.index')" :current="__('shopper::pages/settings.legal.title')">
            <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-gray-300 dark:text-gray-600" />
            <x-shopper::breadcrumb.link :link="route('shopper.settings.index')" :title="__('shopper::words.settings')" />
        </x-shopper::breadcrumb>
        <x-shopper::heading class="mt-5 border-b-0">
            <x-slot name="title">
                {{ __('shopper::pages/settings.legal.title') }}
            </x-slot>
        </x-shopper::heading>
    </x-shopper::container>

    <div class="relative border-gray-200 space-y-4 px-4 lg:pb-0 lg:border-y lg:px-0 dark:border-gray-700">
        <div class="lg:hidden">
            <x-shopper::forms.select x-model="currentTab" aria-label="{{ __('shopper::words.selected_tab') }}">
                <template x-for="option in options" :key="option">
                    <option
                        x-bind:value="option"
                        x-text="words[option]"
                        x-bind:selected="option === currentTab"
                    ></option>
                </template>
            </x-shopper::forms.select>
        </div>

        <div class="hidden lg:block">
            <nav class="-mb-px flex space-x-8 px-6">
                <button
                    @click="currentTab = 'privacy'"
                    type="button"
                    class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                    :class="activeTab('privacy') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                >
                    {{ __('shopper::pages/settings.legal.privacy') }}
                </button>
                <button
                    @click="currentTab = 'terms'"
                    type="button"
                    class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                    :class="activeTab('terms') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                >
                    {{ __('shopper::pages/settings.legal.terms_of_use') }}
                </button>
                <button
                    @click="currentTab = 'shipping'"
                    type="button"
                    class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                    :class="activeTab('shipping') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                >
                    {{ __('shopper::pages/settings.legal.shipping') }}
                </button>
                <button
                    @click="currentTab = 'refund'"
                    type="button"
                    class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                    :class="activeTab('refund') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                >
                    {{ __('shopper::pages/settings.legal.refund') }}
                </button>
            </nav>
        </div>
    </div>

    <x-shopper::container class="mt-8">
        <div x-show="currentTab === 'refund'">
            <livewire:shopper-settings.legal.refund
                :legal="$legals[Str::slug(__('shopper::pages/settings.legal.refund'))]"
            />
        </div>
        <div x-cloak x-show="currentTab === 'privacy'">
            <livewire:shopper-settings.legal.privacy
                :legal="$legals[Str::slug(__('shopper::pages/settings.legal.privacy'))]"
            />
        </div>
        <div x-cloak x-show="currentTab === 'terms'">
            <livewire:shopper-settings.legal.terms
                :legal="$legals[Str::slug(__('shopper::pages/settings.legal.terms_of_use'))]"
            />
        </div>
        <div x-cloak x-show="currentTab === 'shipping'">
            <livewire:shopper-settings.legal.shipping
                :legal="$legals[Str::slug(__('shopper::pages/settings.legal.shipping'))]"
            />
        </div>
    </x-shopper::container>

    <x-shopper::learn-more :name="__('shopper::pages/settings.legal.title')" link="legal" />
</div>
