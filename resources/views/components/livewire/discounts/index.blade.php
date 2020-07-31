<div>
    @if($total === 0)
        <div class="mt-6 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-600 sm:text-3xl sm:leading-9 sm:truncate pb-4 border-b border-gray-200">{{ __('Discounts') }}</h2>
            </div>
        </div>
        <div class="empty-categories w-full flex flex-col lg:flex-row items-center justify-end relative py-12 md:py-24">
            <img src="{{ asset('shopper/images/empty/discount.svg') }}" class="w-full object-cover relative flex lg:absolute lg:top-0" alt="Empty state">
            <div class="w-full lg:w-1/2 relative z-90">
                <div class="w-full pl-0 lg:pl-20 lg:pt-20 xl:pt-24">
                    <h3 class="text-gray-700 font-medium text-xl mb-2">{{ __('Create discount codes.') }}</h3>
                    <p class="text-gray-500 text-lg mb-3">{{ __('Manage discounts and promotions code for all your discounts and orders.') }}</p>
                    <a href="{{ route('shopper.discounts.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-600 focus:outline-none focus:border-brand-700 focus:shadow-outline-brand active:bg-brand-700 transition ease-in-out duration-150 inline-flex">{{ __('Create discount code') }}</a>
                </div>
            </div>
        </div>
    @else
        <div class="my-6 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0 flex flex-row items-center justify-between md:flex-col md:items-start">
                <h2 class="text-2xl font-bold leading-7 text-gray-600 sm:text-3xl sm:leading-9 sm:truncate">{{ __('Discounts') }}</h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('shopper.discounts.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-600 focus:outline-none focus:border-brand-700 focus:shadow-outline-brand active:bg-brand-700 transition ease-in-out duration-150">{{ __('Create discount') }}</a>
            </div>
        </div>

        <div
            x-data="{
                options: ['all', 'active', 'scheduled'],
                words: {'all': '{{ __("All") }}', 'active': '{{ __("Active") }}', 'scheduled': '{{ __("Scheduled") }}'},
                currentTab: 'all'
            }"
            class="bg-white shadow overflow-hidden sm:rounded-md"
        >
            <div class="bg-white border-b border-gray-200">
                <div class="sm:hidden p-4">
                    <select x-model="currentTab" aria-label="Selected tab" class="form-select form-select-shopper block w-full pl-3 pr-10 py-2 text-base leading-6 sm:text-sm sm:leading-5 transition ease-in-out duration-150">
                        <template x-for="option in options" :key="option">
                            <option
                                x-bind:value="option"
                                x-text="words[option]"
                                x-bind:selected="option === currentTab"
                            ></option>
                        </template>
                    </select>
                </div>
                <div class="hidden sm:block">
                    <div class="">
                        <nav class="-mb-px flex">
                            <button @click="currentTab = 'all'" type="button" class="whitespace-no-wrap ml-8 py-4 px-3 border-b-2 border-transparent font-medium text-base leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-brand-500 text-brand-500 focus:text-brand-500 focus:border-brand-600': currentTab === 'all' }">
                                {{ __('All') }}
                            </button>
                            <button @click="currentTab = 'active'" type="button" class="whitespace-no-wrap ml-8 py-4 px-3 border-b-2 border-transparent font-medium text-base leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-brand-500 text-brand-500 focus:text-brand-500 focus:border-brand-600': currentTab === 'active' }">
                                {{ __('Active') }}
                            </button>
                            <button @click="currentTab = 'scheduled'" type="button" class="whitespace-no-wrap ml-8 py-4 px-3 border-b-2 border-transparent font-medium text-base leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-brand-500 text-brand-500 focus:text-brand-500 focus:border-brand-600': currentTab === 'scheduled' }">
                                {{ __('Scheduled') }}
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="px-4 py-4 sm:px-6">
                <label for="filter" class="sr-only">{{ __('Search discounts') }}</label>
                <div class="flex rounded-md shadow-sm">
                    <div class="relative flex-grow focus-within:z-10">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                            </svg>
                        </div>
                        <input id="filter" wire:model.debounce.300ms="search" autocomplete="off" class="form-input block w-full rounded-none rounded-l-md pl-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5" placeholder="{{ __('Search discounts code') }}" />
                        <span wire:loading wire:target="search" class="spinner right-0 top-0 mt-5 mr-6"></span>
                    </div>
                    <button wire:click="sort('{{ $direction }}')" type="button" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"/>
                        </svg>
                        <span class="ml-2">{{ __('Sort') }}</span>
                    </button>
                </div>
            </div>
            <div class="bg-white">
                <div x-show="currentTab === 'all'">
                    <ul class="divide-y divide-gray-200">
                        @foreach($discounts as $discount)
                            <li>
                                <a href="#" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                                    <div class="px-4 py-4 flex items-center sm:px-6">
                                        <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                                            <div>
                                                <div class="text-sm leading-5 font-semibold text-brand-500 truncate">
                                                    {{ $discount->code }}
                                                </div>
                                                <div class="mt-2 flex">
                                                    <ul class="divide-x divide-y-100 flex items-center text-sm leading-5 text-gray-500">
                                                        <li class="pr-2"><span>{{ $discount->total_use }}/{{ $discount->usage_limit ?? __('unlimited') }} {{ __("used") }}</span></li>
                                                        @if($discount->usage_limit_per_user)
                                                            <li class="px-2"><span>{{ __('Once per user') }}</span></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="mt-4 flex-shrink-0 sm:mt-0 flex space-x-6 items-center">
                                                <div class="flex-shrink-0 flex">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $discount->is_active ? 'bg-green-100 text-green-800': 'bg-orange-100 text-orange-800' }}">
                                                        {{ $discount->is_active ? __('Visible'): __('Not Visible') }}
                                                    </span>
                                                </div>
                                                <div class="flex-shrink-0 flex">
                                                    @if($discount->date_start > now())
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-yellow-100 text-yellow-800">
                                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                                                <circle cx="4" cy="4" r="3" />
                                                            </svg>
                                                            {{ __("Scheduled") }}
                                                        </span>
                                                    @endif
                                                    @if($discount->date_start <= now())
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-teal-100 text-teal-800">
                                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-teal-400" fill="currentColor" viewBox="0 0 8 8">
                                                                <circle cx="4" cy="4" r="3" />
                                                            </svg>
                                                            {{ __("Active For users") }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    @if($discount->date_end)
                                                        <span class="text-sm text-gray-500">{{ $discount->date_start->format('d M') }}</span>
                                                        <span>-</span>
                                                        <span class="text-sm text-gray-500">{{ $discount->date_end->format('d M') }}</span>
                                                    @else
                                                        <span class="text-sm text-gray-500">{{ __("From :date", ['date' => $discount->date_start->format('d M')]) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-5 flex-shrink-0">
                                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div x-cloak x-show="currentTab === 'active'">
                    Active Tab
                </div>
                <div x-cloak x-show="currentTab === 'scheduled'">
                    Scheduled Tab
                </div>
            </div>
            <div class="bg-white px-4 py-3 border-t border-gray-200 flex items-center justify-between sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $discounts->links('shopper::components.livewire.wire-mobile-pagination-links') }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm leading-5 text-gray-700">
                            {{ __('Showing') }}
                            <span class="font-medium">{{ ($discounts->currentPage() - 1) * $discounts->perPage() + 1 }}</span>
                            {{ __('to') }}
                            <span class="font-medium">{{ ($discounts->currentPage() - 1) * $discounts->perPage() + count($discounts->items()) }}</span>
                            {{ __('of') }}
                            <span class="font-medium"> {!! $discounts->total() !!}</span>
                            {{ __('results') }}
                        </p>
                    </div>
                    {{ $discounts->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
