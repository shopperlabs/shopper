<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.index')" :current="__('shopper::words.locations')">
        <x-untitledui-chevron-left class="h-4 w-4 shrink-0 text-gray-300 dark:text-gray-600" aria-hidden="true" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.index')" :title="__('shopper::words.settings')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="my-6">
        <x-slot name="title">
            {{ __('shopper::words.locations') }}
        </x-slot>

        <x-slot name="action">
            @can('add_inventories')
                @if ($inventories->count() < (int) config('shopper.admin.inventory-limit') + 1)
                    <div class="flex">
                        <x-shopper::buttons.primary :link="route('shopper.settings.inventories.create')">
                            {{ __('shopper::words.actions_label.add_new', ['name' => mb_strtolower(__('shopper::words.location'))]) }}
                        </x-shopper::buttons.primary>
                    </div>
                @endif
            @endcan
        </x-slot>
    </x-shopper::heading>

    <div class="mt-10 lg:grid lg:grid-cols-3 lg:gap-6">
        <div class="lg:col-span-1">
            <div>
                <x-filament::section.heading>
                    {{ __('shopper::words.locations') }}
                </x-filament::section.heading>
                <x-filament::section.description class="mt-2">
                    {{ __('shopper::pages/settings.location.description') }}
                </x-filament::section.description>
                <x-filament::section.description class="mt-3">
                    {{ __('shopper::pages/settings.location.count', ['count' => $inventories->count()]) }}
                </x-filament::section.description>
            </div>
        </div>
        <div class="mt-5 lg:col-span-2 lg:mt-0">
            <x-shopper::card class="overflow-hidden">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($inventories as $inventory)
                        <li>
                            <x-shopper::link
                                href="{{ route('shopper.settings.inventories.edit', $inventory) }}"
                                class="block hover:bg-gray-50 focus:outline-none dark:hover:bg-gray-900/20"
                            >
                                <div class="p-4 sm:p-6">
                                    <div class="flex items-center">
                                        <div class="hidden shrink-0 lg:block">
                                            <span
                                                class="flex h-10 w-10 items-center justify-center rounded-md bg-white ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-white/10"
                                            >
                                                <x-untitledui-building-05
                                                    class="h-6 w-6 text-gray-500 dark:text-gray-400"
                                                    aria-hidden="true"
                                                />
                                            </span>
                                        </div>
                                        <div class="flex-1 lg:ml-4">
                                            <div class="flex items-center justify-between">
                                                <p
                                                    class="truncate text-sm font-medium leading-5 text-primary-600 dark:text-primary-500"
                                                >
                                                    {{ $inventory->name }}
                                                </p>
                                                @if ($inventory->is_default)
                                                    <div class="ml-2 flex shrink-0">
                                                        <x-filament::badge color="gray">
                                                            {{ __('shopper::words.default') }}
                                                        </x-filament::badge>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="mt-2 sm:flex sm:justify-between">
                                                <div class="sm:flex sm:space-x-4">
                                                    <div
                                                        class="flex items-center text-sm text-gray-500 dark:text-gray-400"
                                                    >
                                                        {{ isoToEmoji($inventory->country->cca2) }}
                                                        {{ $inventory->country->name }}
                                                    </div>
                                                    <div
                                                        class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400 sm:mt-0"
                                                    >
                                                        <x-untitledui-marker-pin-02
                                                            class="mr-1.5 h-5 w-5 shrink-0 text-gray-400 dark:text-gray-500"
                                                            aria-hidden="true"
                                                        />
                                                        {{ $inventory->city }}
                                                    </div>
                                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                        <x-untitledui-phone
                                                            class="mr-1.5 h-5 w-5 shrink-0 text-gray-400 dark:text-gray-500"
                                                            aria-hidden="true"
                                                        />
                                                        {{ $inventory->phone_number ?? __('shopper::words.number_not_set') }}
                                                    </div>
                                                </div>
                                                <div
                                                    class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400 sm:mt-0"
                                                >
                                                    <x-untitledui-calendar
                                                        class="mr-1.5 h-5 w-5 shrink-0 text-gray-400 dark:text-gray-500"
                                                    />
                                                    <span>
                                                        {{ __('shopper::words.added_on') }}
                                                        <time datetime="{{ $inventory->created_at->format('d-m-Y') }}">
                                                            {{ $inventory->created_at->formatLocalized('%d %B %G') }}
                                                        </time>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </x-shopper::link>
                        </li>
                    @endforeach
                </ul>
            </x-shopper::card>
        </div>
    </div>

    <x-shopper::learn-more :name="__('shopper::words.location')" link="locations" />
</x-shopper::container>
