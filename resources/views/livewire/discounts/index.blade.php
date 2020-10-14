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
                options: ['all'],
                words: {'all': '{{ __("All") }}'},
                currentTab: 'all'
            }"
            x-init="flatpickr($refs.input, {dateFormat: 'Y-m-d'});"
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
                    <div>
                        <nav class="-mb-px flex">
                            <button @click="currentTab = 'all'" type="button" class="whitespace-no-wrap ml-8 py-4 px-3 border-b-2 border-transparent font-medium text-base leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-brand-500 text-brand-500 focus:text-brand-500 focus:border-brand-600': currentTab === 'all' }">
                                {{ __('All') }}
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="flex items-start px-4 py-4 sm:px-6">
                <div class="flex-1">
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
                        <div class="relative inline-flex text-left" x-data="{ status: false }">
                            <button @click="status = true" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                {{ __('Status') }}
                                <svg class="ml-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <div
                                    @click.away="status = false"
                                    x-show="status"
                                    x-on:change-status.window="status = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 transform scale-100"
                                    x-transition:leave-end="opacity-0 transform scale-95"
                            >
                                <div class="origin-top-right absolute z-30 right-0 mt-12 w-56 rounded-md shadow-lg">
                                    <div class="rounded-md bg-white shadow-xs">
                                        <div class="py-2">
                                            <button wire:click="changeStatus('1')" type="button" class="w-full flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900 @if($is_active === '1') bg-gray-50 text-gray-900 @endif" role="menuitem">
                                            <span class="inline-flex items-center text-sm font-medium leading-5 text-gray-700">
                                                {{ __("Active ") }}
                                            </span>
                                            </button>
                                            <button wire:click="changeStatus('0')" type="button" class="w-full flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900 @if($is_active === '0') bg-gray-50 text-gray-900 @endif" role="menuitem">
                                            <span class="inline-flex items-center text-sm font-medium leading-5 text-gray-700">
                                                {{ __("Not Active") }}
                                            </span>
                                            </button>
                                            <button wire:click="clear" type="button" class="block px-4 mt-3 text-sm leading-5 text-gray-400">{{ __("Clear") }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button wire:click="sort('{{ $direction }}')" type="button" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"/>
                            </svg>
                            <span class="ml-2">{{ __('Sort') }}</span>
                        </button>
                    </div>
                </div>
                <div class="ml-4 relative flex items-center rounded-md shadow-sm">
                    <label for="date" class="block text-sm font-medium leading-5 text-gray-700 sr-only">{{ __("Date") }}</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input
                        wire:model="date"
                        x-ref="input"
                        id="date"
                        class="form-input block w-full pl-10 sm:text-sm sm:leading-5"
                        autocomplete="off"
                        placeholder="{{ __("Choose date") }}"
                        readonly
                    >
                    <button wire:click="resetDate" type="button" class="absolute z-30 inset-y-0 right-0 pr-3 flex items-center @if($date === '') hidden @endif">
                        <svg class="text-gray-500 h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="bg-white">
                <ul class="divide-y divide-gray-200">
                    @foreach($discounts as $discount)
                        <li>
                            <a href="{{ route('shopper.discounts.edit', $discount) }}" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
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
