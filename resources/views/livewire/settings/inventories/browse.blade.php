<div>
    <x-shopper::breadcrumb back="shopper.settings.index">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.index')" title="Settings" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-3">
        <x-slot name="title">
            {{ __('Locations') }}
        </x-slot>

        <x-slot name="action">
            @can('add_inventories')
                @if($inventories->count() < 5)
                    <div class="flex">
                    <span class="shadow-sm rounded-md">
                        <x-shopper::buttons.primary :link="route('shopper.settings.inventories.create')">
                            {{ __('Add location') }}
                        </x-shopper::buttons.primary>
                    </span>
                    </div>
                @endif
            @endcan
        </x-slot>
    </x-shopper::heading>

    <div class="mt-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-semibold leading-6 text-secondary-900 dark:text-white">{{ __('Locations') }}</h3>
                    <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('Manage the places you stock inventory, fulfill orders, and sell products.') }}
                    </p>
                    <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('You’re using :count of 4 locations available.', ['count' => $inventories->count()]) }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="bg-white shadow rounded-md overflow-hidden dark:bg-secondary-800">
                    <ul class="divide-y divide-secondary-200 dark:divide-secondary-700">
                        @foreach($inventories as $inventory)
                            <li>
                                <a href="{{ route('shopper.settings.inventories.edit', $inventory) }}" class="block hover:bg-secondary-50 focus:outline-none dark:hover:bg-secondary-700">
                                    <div class="p-4 sm:p-6">
                                        <div class="flex items-center">
                                            <div class="shrink-0 hidden lg:block">
                                                <span class="flex items-center justify-center h-12 w-12 bg-secondary-100 text-secondary-400 rounded-md dark:bg-secondary-700 dark:text-secondary-500">
                                                    <x-heroicon-o-location-marker class="w-6 h-6" />
                                                </span>
                                            </div>
                                            <div class="flex-1 lg:ml-4">
                                                <div class="flex items-center justify-between">
                                                    <div class="text-sm leading-5 font-medium text-primary-600 truncate dark:text-primary-500/50">
                                                        {{ $inventory->name }}
                                                    </div>
                                                    @if($inventory->is_default)
                                                        <div class="ml-2 shrink-0 flex">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-medium rounded-full bg-secondary-100 text-secondary-800 border-2 border-white dark:bg-secondary-700 dark:text-secondary-300 dark:border-secondary-800">
                                                                {{ __('Default') }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="mt-2 sm:flex sm:justify-between">
                                                    <div class="sm:flex sm:space-x-4">
                                                        <div class="flex items-center text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                                            <x-heroicon-s-flag class="shrink-0 mr-1.5 h-5 w-5 text-secondary-400 " />
                                                            {{ $inventory->country->name }}
                                                        </div>
                                                        <div class="mt-2 flex items-center text-sm leading-5 text-secondary-500 sm:mt-0 dark:text-secondary-400">
                                                            <x-heroicon-s-location-marker class="shrink-0 mr-1.5 h-5 w-5 text-secondary-400 dark:text-secondary-500" />
                                                            {{ $inventory->city }}
                                                        </div>
                                                        <div class="mt-2 flex items-center text-sm leading-5 text-secondary-500 sm:mt-0">
                                                            <x-heroicon-s-phone class="shrink-0 mr-1.5 h-5 w-5 text-secondary-400 dark:text-secondary-500" />
                                                            {{ $inventory->phone_number ?? __('Number not set') }}
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 flex items-center text-sm leading-5 text-secondary-500 sm:mt-0 dark:text-secondary-400">
                                                        <x-heroicon-s-calendar class="shrink-0 mr-1.5 h-5 w-5 text-secondary-400 dark:text-secondary-500" />
                                                        <span>
                                                            {{ __('Added on') }}
                                                            <time datetime="{{ $inventory->created_at->format('d-m-Y') }}">{{ $inventory->created_at->formatLocalized('%d %B %G') }}</time>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <x-shopper::learn-more name="locations" link="locations" />
</div>
