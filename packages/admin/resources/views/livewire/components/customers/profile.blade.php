<x-shopper::container class="grid gap-8 lg:grid-cols-3">
    <x-shopper::card
        :title="__('shopper::pages/customers.profile.title')"
        :description="__('shopper::pages/customers.profile.description')"
        class="max-w-4xl lg:col-span-2 [&>div:first-of-type]:p-0"
    >
        <dl class="divide-y divide-gray-200 dark:divide-white/10">
            <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                    {{ __('shopper::forms.label.first_name') }}
                </dt>
                <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:col-span-2 sm:mt-0 dark:text-white">
                    <div class="grow">
                        <span>{{ $customer->first_name }}</span>
                    </div>
                </dd>
            </div>
            <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                    {{ __('shopper::forms.label.last_name') }}
                </dt>
                <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:col-span-2 sm:mt-0 dark:text-white">
                    <div class="grow">
                        <span>{{ $customer->last_name }}</span>
                    </div>
                </dd>
            </div>
            <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                    {{ __('shopper::forms.label.photo') }}
                </dt>
                <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:col-span-2 sm:mt-0 dark:text-white">
                    <span class="grow">
                        <img class="size-8 rounded-full" src="{{ $customer->picture }}" alt="" />
                    </span>
                </dd>
            </div>
            <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                    {{ __('shopper::forms.label.email') }}
                </dt>
                <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:col-span-2 sm:mt-0 dark:text-white">
                    <div class="grow">
                        <span>{{ $customer->email }}</span>
                    </div>
                </dd>
            </div>
            <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                    {{ __('shopper::forms.label.birth_date') }}
                </dt>
                <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:col-span-2 sm:mt-0 dark:text-white">
                    <div class="grow">
                        <p class="flex items-center gap-2">
                            <x-untitledui-calendar-heart
                                class="size-5 text-gray-500 dark:text-gray-400"
                                stroke-width="1.5"
                                aria-hidden="true"
                            />
                            <span>
                                {{ $customer->birth_date_formatted }}
                            </span>
                        </p>
                    </div>
                </dd>
            </div>
            @if ($customer->gender)
                <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                        {{ __('shopper::forms.label.gender') }}
                    </dt>
                    <dd
                        class="flex items-center space-x-4 text-sm leading-5 text-gray-900 sm:col-span-2 sm:mt-0 dark:text-white"
                    >
                        <div class="grow">
                            <span class="capitalize">
                                {{ $customer->gender->getLabel() }}
                            </span>
                        </div>
                    </dd>
                </div>
            @endif
        </dl>
    </x-shopper::card>

    <div>
        <x-shopper::card
            :title="__('shopper::pages/customers.profile.account')"
            :description="__('shopper::pages/customers.profile.account_description')"
            class="[&>div:first-of-type]:p-0"
        >
            <dl class="divide-y divide-gray-200 dark:divide-white/10">
                <div class="space-y-1 p-4 sm:flex sm:items-center sm:justify-between">
                    <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/customers.profile.marketing') }}
                    </dt>
                    <dd class="flex text-sm leading-5 text-gray-900 sm:col-span-2 sm:mt-0">
                        <x-filament::badge :color="$customer->opt_in ? 'success' : 'gray'" size="sm">
                            {{ $customer->opt_in ? __('shopper::forms.actions.enable') : __('shopper::forms.actions.disable') }}
                        </x-filament::badge>
                    </dd>
                </div>
                <div class="space-y-1 p-4 sm:flex sm:items-center sm:justify-between">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/customers.profile.two_factor') }}
                    </dt>
                    <dd class="flex text-sm sm:col-span-2 sm:mt-0">
                        <x-filament::badge :color="$customer->store_two_factor_secret ? 'success' : 'gray'" size="sm">
                            {{ $customer->store_two_factor_secret ? __('shopper::forms.actions.enable') : __('shopper::forms.actions.disable') }}
                        </x-filament::badge>
                    </dd>
                </div>
            </dl>
        </x-shopper::card>
    </div>
</x-shopper::container>
