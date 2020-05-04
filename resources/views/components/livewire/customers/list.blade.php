<div>
    @if($customers->isEmpty())
        <div class="mt-6 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-primary-text sm:text-3xl sm:leading-9 sm:truncate pb-4 border-b border-gray-200">{{ __('Customers') }}</h2>
            </div>
        </div>
        <div class="empty-categories relative w-full flex flex-col md:flex-row items-center justify-end relative py-12 md:py-24">
            <img src="{{ asset('shopper/images/empty/categories.svg') }}" class="w-full object-cover relative flex lg:absolute lg:top-0" alt="Empty state">
            <div class="w-full lg:w-1/2 relative z-90">
                <div class="w-full pl-0 lg:pl-20 lg:pt-20 xl:pt-24">
                    <h3 class="text-primary-text font-medium text-xl mb-2">{{ __('Understand your customers') }}</h3>
                    <p class="text-gray-500 text-lg mb-3">{{ __('When a customer places an order, you’ll find their details and purchase history here..') }}</p>
                    <a href="{{ route('shopper.customers.create') }}" class="btn btn-primary inline-flex">{{ __('Add customer') }}</a>
                </div>
            </div>
        </div>
    @else
        <div class="my-6 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0 flex flex-row items-center justify-between md:flex-col md:items-start">
                <h2 class="text-2xl font-bold leading-7 text-primary-text sm:text-3xl sm:leading-9 sm:truncate">{{ __('Customers') }}</h2>
                <div class="md:mt-2 ml-4 md:ml-0">
                    <a href="#" class="text-gray-400 text-sm inline-flex items-center hover:text-gray-500 focus:text-gray-600 leading-5 transition duration-150 ease-in-out">
                        <svg fill="currentColor" viewBox="0 0 20 20" class="w-5 h-5 mr-2">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ __('Export') }}</span>
                    </a>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('shopper.customers.create') }}" class="btn btn-primary">{{ __('Add product') }}</a>
            </div>
        </div>

        <div class="bg-white shadow rounded-md">
            <div class="bg-white border-b border-gray-200 rounded-t-md">
                <div class="sm:hidden p-4">
                    <select aria-label="Selected tab" class="form-select form-select-shopper block w-full pl-3 pr-10 py-2 text-base leading-6 sm:text-sm sm:leading-5 transition ease-in-out duration-150">
                        <option>{{ __('All') }}</option>
                    </select>
                </div>
                <div class="hidden sm:block">
                    <div class="">
                        <nav class="-mb-px flex">
                            <a href="#" class="whitespace-no-wrap ml-8 py-4 px-3 border-b-2 border-brand-500 font-medium text-sm leading-5 text-brand-400 focus:outline-none focus:text-brand-500 focus:border-brand-500">
                                {{ __('All') }}
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="px-4 py-4 sm:px-6">
                <label for="filter" class="sr-only">{{ __('Search customers') }}</label>
                <div class="flex rounded-md shadow-sm">
                    <div class="relative flex-grow focus-within:z-10">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                            </svg>
                        </div>
                        <input id="filter" wire:model.debounce.300ms="search" class="form-input block w-full rounded-none rounded-l-md pl-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5" placeholder="{{ __('Search customers') }}" />
                        <span wire:loading class="spinner right-0 top-0 mt-5 mr-6"></span>
                    </div>
                    <div class="relative inline-flex text-left" x-data="{ status: false }">
                        <button @click="status = true" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                            {{ __('Account status') }}
                            <svg class="ml-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div
                            @click.away="status = false"
                            x-show="status"
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
                                        <label class="px-4 py-2 flex items-center cursor-pointer" for="verified">
                                            <input id="verified" value="verified" name="verified" type="radio" class="form-radio h-4 w-4 text-brand-500 transition duration-150 ease-in-out" />
                                            <span class="block text-sm leading-5 font-medium text-gray-700 ml-3">{{ __("Verified account") }}</span>
                                        </label>
                                        <label class="px-4 py-2 flex items-center cursor-pointer" for="unverified">
                                            <input id="unverified" value="unverified" name="verified" type="radio" class="form-radio h-4 w-4 text-brand-500 transition duration-150 ease-in-out" />
                                            <span class="block text-sm leading-5 font-medium text-gray-700 ml-3">{{ __("Unverified account") }}</span>
                                        </label>
                                        <button type="button" class="block px-4 mt-3 text-sm leading-5 text-gray-400">{{ __("Clear") }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button wire:click="sort('{{ $direction }}')" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"/>
                        </svg>
                        <span class="ml-2">{{ __('Sort') }}</span>
                    </button>
                </div>
            </div>
            <div class="bg-white">
                <ul class="divide-y divide-gray-200">
                    @foreach($customers as $customer)
                        <li>
                            <a href="{{ route('shopper.customers.show', $customer) }}" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                                <div class="flex items-center px-4 py-4 sm:px-6">
                                    <div class="min-w-0 flex-1 flex items-center">
                                        <div class="flex-shrink-0">
                                            <img class="h-12 w-12 rounded-full" src="{{ $customer->picture }}" alt="{{ $customer->full_name }}" />
                                        </div>
                                        <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4">
                                            <div>
                                                <div class="text-sm leading-5 font-medium text-brand-500 truncate">{{ $customer->full_name }}</div>
                                                <div class="mt-2 flex items-center text-sm leading-5 text-gray-500">
                                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884zM18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="truncate">{{ $customer->email }}</span>
                                                </div>
                                            </div>
                                            <div class="hidden md:block">
                                                <div>
                                                    <div class="text-sm leading-5 text-gray-900">
                                                        {{ __("Joined at") }}
                                                        <time datetime="{{ $customer->created_at->format('y-m-d') }}">{{ $customer->created_at->format('F j, Y') }}</time>
                                                    </div>
                                                    <div class="mt-2 flex items-center text-sm leading-5 text-gray-500">
                                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 {{ $customer->isVerified() ? 'text-green-400' : 'text-red-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            @if($customer->isVerified())
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            @else
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                            @endif
                                                        </svg>
                                                        {{ $customer->isVerified() ? __("Account verified") : __("Account unverified") }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-white rounded-b-md px-4 py-3 flex items-center justify-between sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $customers->links('shopper::components.livewire.wire-mobile-pagination-links') }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm leading-5 text-gray-700">
                            {{ __('Showing') }}
                            <span class="font-medium">{{ ($customers->currentPage() - 1) * $customers->perPage() + 1 }}</span>
                            {{ __('to') }}
                            <span class="font-medium">{{ ($customers->currentPage() - 1) * $customers->perPage() + count($customers->items()) }}</span>
                            {{ __('of') }}
                            <span class="font-medium"> {!! $customers->total() !!}</span>
                            {{ __('results') }}
                        </p>
                    </div>
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
