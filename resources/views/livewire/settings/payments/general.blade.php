<div
    x-data="{
        open: false,
        options: ['general', 'stripe'],
        words: {'general': '{{ __("General") }}', 'stripe': '{{ __("Stripe") }}'},
        currentTab: 'stripe'
    }"
>

    <x:shopper-breadcrumb back="shopper.settings.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.settings.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Settings') }}</a>
    </x:shopper-breadcrumb>

    <div class="mt-3 relative pb-5 border-b border-gray-200 space-y-4 sm:pb-0">
        <div class="space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
            <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
                {{ __("Payment Methods") }}
            </h3>
            <div class="flex space-x-3 md:absolute md:top-3 md:right-0">
                <span class="shadow-sm rounded-md">
                    <button @click="open = true" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                        {{ __("Create custom payment method") }}
                    </button>
                </span>
            </div>
        </div>
        <div>
            <!-- Dropdown menu on small screens -->
            <div class="sm:hidden">
                <select x-model="currentTab" aria-label="Selected tab" class="form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5 transition ease-in-out duration-150">
                    <template x-for="option in options" :key="option">
                        <option
                            x-bind:value="option"
                            x-text="words[option]"
                            x-bind:selected="option === currentTab"
                        ></option>
                    </template>
                </select>
            </div>
            <!-- Tabs at small breakpoint and up -->
            <div class="hidden sm:block">
                <nav class="-mb-px flex space-x-8">
                    <button @click="currentTab = 'general'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" aria-current="page" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'general' }">
                        {{ __("General") }}
                    </button>

                    <button @click="currentTab = 'stripe'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'stripe' }">
                        {{ __("Stripe") }}
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <div class="mt-10">
        <div x-show="currentTab === 'general'">

        </div>
        <div x-cloak x-show="currentTab === 'stripe'">
            <livewire:shopper-settings-payments-stripe />
        </div>
    </div>

</div>
