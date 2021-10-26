<x-livewire-tables::table.cell class="max-w-md whitespace-no-wrap text-sm leading-5 font-medium text-gray-900 dark:text-white">
    <div class="flex items-center lg:pl-2">
        <div class="flex-shrink-0 h-10 w-10">
            <img class="h-10 w-10 rounded-lg" src="{{ $row->picture }}" alt="">
        </div>
        <a href="{{ route('shopper.customers.show', $row) }}" class="ml-4">
            <div class="text-sm leading-5 font-medium text-gray-900 dark:text-white">
                {{ $row->full_name }}
            </div>
            <div class="flex items-center">
                @if($row->email_verified_at)
                    <x-heroicon-s-shield-check class="w-5 h-5 text-green-500" />
                @else
                    <x-heroicon-s-shield-exclamation class="w-5 h-5 text-red-500" />
                @endif
                <span class="ml-1.5 text-sm leading-5 text-gray-500 dark:text-gray-400">{{ $row->email }}</span>
            </div>
        </a>
    </div>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell class="text-gray-500 font-medium">
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $row->opt_in ? 'bg-green-100 text-green-800': 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
        {{ $row->opt_in ? __('Subscribed') : __('Not subscribed') }}
    </span>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell class="hidden md:table-cell whitespace-no-wrap text-sm leading-5 text-gray-500 dark:text-gray-400">
    <time datetime="{{ $row->created_at->format('Y-m-d') }}" class="capitalize">{{ $row->created_at->formatLocalized('%d %B %Y') }}</time>
</x-livewire-tables::table.cell>
{{--
<div class="p-4 sm:p-6 sm:pb-4">
    <div class="flex items-center space-x-4">
        <x-shopper-input.search label="Search customer" placeholder="Search customer by first name / last name" />
        <div class="flex items-center space-x-4">
            <div class="relative z-10 inline-flex shadow-sm rounded-md">
                <div x-data="{ open: false }" @keydown.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
                    <x-shopper-default-button @click="open = !open" type="button" class="relative rounded-r-none rounded-l-md dark:border-gray-700">
                        {{ __('Account Status') }}
                        <x-heroicon-s-chevron-down class="-mr-1 ml-2 h-5 w-5" />
                    </x-shopper-default-button>
                    <div x-cloak x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg"
                    >
                        <div class="rounded-md bg-white shadow-xs dark:bg-gray-700" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <div class="py-1">
                                <div class="flex items-center py-2 px-4">
                                    <x-shopper-input.radio wire:model="activeAccount" id="activeAccount_enabled" name="activeAccount" type="radio" value="1" />
                                    <label for="activeAccount_enabled" class="cursor-pointer ml-3">
                                        <span class="block text-sm leading-5 font-medium text-gray-700 dark:text-gray-300">{{ __('Active account') }}</span>
                                    </label>
                                </div>
                                <div class="flex items-center py-2 px-4">
                                    <x-shopper-input.radio wire:model="activeAccount" id="activeAccount_disabled" name="activeAccount" type="radio" value="0" />
                                    <label for="activeAccount_disabled" class="cursor-pointer ml-3">
                                        <span class="block text-sm leading-5 font-medium text-gray-700 dark:text-gray-300">{{ __('Inactive account') }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="border-t border-gray-100 dark:border-gray-600"></div>
                            <div class="py-1">
                                <button wire:click="resetActiveAccountFilter" type="button" class="block px-4 py-2 text-sm text-left leading-5 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-500">{{ __('Clear') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-data="{ open: false }" @keydown.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
                    <x-shopper-default-button @click="open = !open" type="button" class="-ml-px rounded-l-none rounded-r-md focus:z-10">
                        {{ __('Email Subscription') }}
                        <x-heroicon-s-chevron-down class="-mr-1 ml-2 h-5 w-5" />
                    </x-shopper-default-button>
                    <div x-cloak x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                        <div class="rounded-md bg-white shadow-xs dark:bg-gray-700" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <div class="py-1">
                                <div class="flex items-center py-2 px-4">
                                    <x-shopper-input.radio wire:model="emailSubscription" id="emailSubscription_enabled" name="emailSubscription" value="1" />
                                    <label for="emailSubscription_enabled" class="cursor-pointer ml-3">
                                        <span class="block text-sm leading-5 font-medium text-gray-700 dark:text-gray-300">{{ __('Subscribed') }}</span>
                                    </label>
                                </div>
                                <div class="flex items-center py-2 px-4">
                                    <x-shopper-input.radio wire:model="emailSubscription" id="emailSubscription_disabled" name="emailSubscription" value="0" />
                                    <label for="emailSubscription_disabled" class="cursor-pointer ml-3">
                                        <span class="block text-sm leading-5 font-medium text-gray-700 dark:text-gray-300">{{ __('Not subscribed') }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="border-t border-gray-100 dark:border-gray-600"></div>
                            <div class="py-1">
                                <button wire:click="resetEmailFilter" type="button" class="block px-4 py-2 text-sm text-left leading-5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500">{{ __('Clear') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="hidden sm:block">
    <div class="align-middle inline-block min-w-full">
        <table class="min-w-full">
            <thead>
            <tr class="border-t border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-700">
                <x-shopper-tables.table-head>
                    <span class="lg:pl-2">{{ __('Full Name') }}</span>
                </x-shopper-tables.table-head>
                <x-shopper-tables.table-head class="hidden md:table-cell text-right">
                    <span class="lg:pl-2">{{ __('Email') }}</span>
                </x-shopper-tables.table-head>
                <x-shopper-tables.table-head class="hidden md:table-cell text-right">
                    <span class="lg:pl-2">{{ __('Registered At') }}</span>
                </x-shopper-tables.table-head>
                <x-shopper-tables.table-head class="pr-6 text-right" />
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:border-gray-700" x-max="1">
            @forelse($customers as $customer)
                <tr>


                    <td class="pr-6">
                        <x-shopper-dropdown customAlignmentClasses="right-7 top-0">
                            <x-slot name="trigger">
                                <button id="product-options-menu" aria-has-popup="true" :aria-expanded="open" type="button" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 dark:focus:bg-gray-700">
                                    <x-heroicon-s-dots-vertical class="w-5 h-5" />
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="py-1">
                                    <a href="{{ route('shopper.customers.show', $customer) }}" class="group flex items-center px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white" role="menuitem">
                                        <x-heroicon-s-pencil-alt class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" />
                                        {{ __('Detail') }}
                                    </a>
                                </div>
                                <div class="border-t border-gray-100 dark:border-gray-600"></div>
                                <div class="py-1">
                                    <button wire:click="remove({{ $customer->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white" role="menuitem">
                                        <x-heroicon-s-trash class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" />
                                        {{ __('Delete') }}
                                    </button>
                                </div>
                            </x-slot>
                        </x-shopper-dropdown>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900 dark:text-white">
                        <div class="flex justify-center items-center space-x-2">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="font-medium py-8 text-gray-400 text-xl">{{ __('No customer found') }}...</span>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>--}}
