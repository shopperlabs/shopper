<div
    x-data="{
        open: false,
        options: ['profile', 'address', 'orders', 'tax'],
        words: {
            'profile': '{{ __("Profile") }}',
            'address': '{{ __("Address") }}',
            'orders': '{{ __("Orders") }}',
            'tax': '{{ __("Tax") }}',
        },
        currentTab: 'profile'
    }"
>
    <x:shopper-breadcrumb back="shopper.customers.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.customers.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Customers') }}</a>
    </x:shopper-breadcrumb>
    <div class="mt-5 md:flex md:items-center md:justify-between relative z-20">
        <div class="flex-1 min-w-0">
            <div class="flex items-start">
                <div class="flex-shrink-0 h-12 w-12">
                    <img class="h-12 w-12 rounded-lg" src="{{ $picture }}" alt="">
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:truncate">
                        {{ $customer->full_name }}
                    </h3>
                    <div class="mt-1 flex items-center divide-x divide-gray-200">
                        <div class="flex items-center pr-2">
                            @if($customer->email_verified_at)
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd" />
                                </svg>
                            @endif
                            <span class="ml-1.5 text-sm leading-5 text-gray-500">{{ $customer->email }}</span>
                        </div>
                        <p class="pl-2 text-sm text-gray-500 leading-5">
                            {{ __("Customer for") }} {{ $customer->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-2">
            <div @keydown.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
                <div>
                    <button @click="open = !open" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" aria-label="Options" id="options-menu" aria-haspopup="true" x-bind:aria-expanded="open">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                    </button>
                </div>

                <div x-cloak x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg" style="display: none;">
                    <div class="rounded-md bg-white shadow-xs">
                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <button type="button" class="block w-full px-4 py-2 text-sm leading-5 text-left text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">{{ __("Delete customer") }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="bg-gray-100 sticky top-0 -mx-6 px-6">
            <div class="border-b border-gray-200">
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
                        <button @click="currentTab = 'profile'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" aria-current="page" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'profile' }">
                            {{ __("Profile") }}
                        </button>

                        <button @click="currentTab = 'address'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'address' }">
                            {{ __("Address") }}
                        </button>

                        <button @click="currentTab = 'orders'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'orders' }">
                            {{ __("Orders") }}
                        </button>

                        <button @click="currentTab = 'tax'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'tax' }">
                            {{ __("Tax") }}
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div x-show="currentTab === 'profile'" class="bg-white shadow overflow-hidden sm:rounded-md px-4 py-5 bg-white sm:p-6">
                <livewire:shopper-customers-profile :customer="$customer" />
            </div>
            <div x-cloak x-show="currentTab === 'address'" class="bg-white shadow overflow-hidden sm:rounded-md px-4 py-5 bg-white sm:p-6">
                <x-shopper-wip-placeholder />
            </div>
            <div x-cloak x-show="currentTab === 'orders'" class="bg-white shadow overflow-hidden sm:rounded-md px-4 py-5 bg-white sm:p-6">
                <x-shopper-wip-placeholder />
            </div>
            <div x-cloak x-show="currentTab === 'tax'" class="bg-white shadow overflow-hidden sm:rounded-md px-4 py-5 bg-white sm:p-6">
                <x-shopper-wip-placeholder />
            </div>
        </div>
    </div>
</div>
