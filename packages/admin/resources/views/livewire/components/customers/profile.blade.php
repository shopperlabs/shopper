<x-shopper::container class="grid gap-8 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <div class="space-y-1">
            <x-filament::section.heading>
                {{ __('shopper::pages/customers.profile.title') }}
            </x-filament::section.heading>
            <x-filament::section.description class="max-w-2xl">
                {{ __('shopper::pages/customers.profile.description') }}
            </x-filament::section.description>
        </div>
        <div class="bg-white rounded-lg ring-1 ring-gray-200 max-w-4xl dark:bg-gray-900 dark:ring-white/10">
            <dl class="divide-y divide-gray-200 dark:divide-white/10">
                <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                    <dt class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::forms.label.first_name') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                        <div class="grow">
                            <span>{{ $customer->first_name }}</span>
                        </div>
                    </dd>
                </div>
                <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                    <dt class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::forms.label.last_name') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                        <div class="grow">
                            <span>{{ $customer->last_name }}</span>
                        </div>
                    </dd>
                </div>
                <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                    <dt class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::forms.label.photo') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                        <span class="grow">
                            <img class="size-8 rounded-full" src="{{ $customer->picture }}" alt="" />
                        </span>
                    </dd>
                </div>
                <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                    <dt class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::forms.label.email') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                        <div class="grow">
                            <span>{{ $customer->email }}</span>
                        </div>
                    </dd>
                </div>
                <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                    <dt class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::forms.label.birth_date') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                        <div class="grow">
                            <p class="flex items-center">
                                <x-untitledui-calendar-heart
                                    class="mr-2 size-5 text-gray-500 dark:text-gray-400"
                                    stroke-width="1.5"
                                    aria-hidden="true"
                                />
                                <span>{{ $customer->birth_date_formatted }}</span>
                            </p>
                        </div>
                    </dd>
                </div>
                <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                    <dt class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::forms.label.gender') }}
                    </dt>
                    <dd
                        class="flex items-center space-x-4 text-sm leading-5 text-gray-900 dark:text-white sm:col-span-2 sm:mt-0"
                    >
                        <div class="grow">
                            <span class="capitalize">{{ $customer->gender }}</span>
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    <div class="space-y-6">
        <div class="space-y-1">
            <h3 class="font-heading text-lg font-medium leading-6 text-gray-900 dark:text-white">
                {{ __('shopper::pages/customers.profile.account') }}
            </h3>
            <p class="max-w-2xl text-sm leading-5 text-gray-500 dark:text-gray-400">
                {{ __('shopper::pages/customers.profile.account_description') }}
            </p>
        </div>
        <div class="bg-white rounded-lg ring-1 ring-gray-200 max-w-4xl dark:bg-gray-900 dark:ring-white/10">
            <dl class="divide-y divide-gray-200 dark:divide-white/10">
                <div class="space-y-1 p-4 sm:flex sm:items-center sm:justify-between sm:py-5">
                    <dt class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/customers.profile.marketing') }}
                    </dt>
                    <dd class="flex text-sm leading-5 text-gray-900 sm:col-span-2 sm:mt-0">
                        <x-shopper::badge
                            :style="$customer->opt_in ? 'success' : 'gray'"
                            :value="$customer->opt_in ? __('shopper::forms.actions.enable') : __('shopper::forms.actions.disable')"
                        />
                    </dd>
                </div>
                <div class="space-y-1 p-4 sm:flex sm:items-center sm:justify-between sm:py-5">
                    <dt class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/customers.profile.two_factor') }}
                    </dt>
                    <dd class="flex text-sm leading-5 text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                        <x-shopper::badge
                            :style="$customer->two_factor_secret ? 'success' : 'gray'"
                            :value="$customer->two_factor_secret ? __('shopper::forms.actions.enable') : __('shopper::forms.actions.disable')"
                        />
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</x-shopper::container>
