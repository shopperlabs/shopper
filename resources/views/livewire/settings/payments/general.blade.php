<div
    x-data="{
        options: ['general', 'stripe'],
        words: {'general': '{{ __("General") }}', 'stripe': '{{ __("Stripe") }}'},
        currentTab: 'general'
    }"
>

    <x:shopper-breadcrumb back="shopper.settings.index">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.settings.index')" title="Settings" />
    </x:shopper-breadcrumb>

    <div class="mt-3 relative pb-5 border-b border-gray-200 space-y-4 sm:pb-0 dark:border-gray-700">
        <div class="space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
            <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate dark:text-white">
                {{ __('Payment Methods') }}
            </h3>
            <div class="flex space-x-3 md:absolute md:top-3 md:right-0">
                <span class="shadow-sm rounded-md">
                    <x-shopper-button wire:click="$emit('openModal', 'shopper-modals.create-payment-method')" type="button">
                        {{ __('Create custom payment method') }}
                    </x-shopper-button>
                </span>
            </div>
        </div>
        <div>
            <div class="sm:hidden">
                <x-shopper-input.select x-model="currentTab" aria-label="Selected tab">
                    <template x-for="option in options" :key="option">
                        <option
                            x-bind:value="option"
                            x-text="words[option]"
                            x-bind:selected="option === currentTab"
                        ></option>
                    </template>
                </x-shopper-input.select>
            </div>
            <!-- Tabs at small breakpoint and up -->
            <div class="hidden sm:block">
                <nav class="-mb-px flex space-x-8">
                    <button @click="currentTab = 'general'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" aria-current="page" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'general' }">
                        {{ __('General') }}
                    </button>

                    <button @click="currentTab = 'stripe'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'stripe' }">
                        {{ __('Stripe') }}
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <div class="mt-10">
        <div x-show="currentTab === 'general'">
            <div class="mt-6 bg-white shadow sm:rounded-md dark:bg-gray-800">
                <div class="p-4 sm:p-6 sm:pb-4">
                    <x-shopper-input.search label="Search payments" placeholder="Search payment by provider name" />
                </div>
                <div class="hidden sm:block">
                    <div class="align-middle inline-block min-w-full">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-t border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-700">
                                    <x-shopper-tables.table-head>
                                        <span class="lg:pl-2">{{ __('Title') }}</span>
                                    </x-shopper-tables.table-head>
                                    <x-shopper-tables.table-head>
                                        {{ __('Status') }}
                                    </x-shopper-tables.table-head>
                                    <x-shopper-tables.table-head>
                                        {{ __('Website') }}
                                    </x-shopper-tables.table-head>
                                    <x-shopper-tables.table-head class="hidden md:table-cell text-right">
                                        {{ __('Updated at') }}
                                    </x-shopper-tables.table-head>
                                    <x-shopper-tables.table-head class="pr-6 text-right" />
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700" x-max="1">
                                @forelse($methods as $method)
                                    <tr>
                                        <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900 dark:text-white">
                                            <div class="flex items-center space-x-3 lg:pl-2">
                                                <div class="flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $method->is_enabled ? 'bg-green-600': 'bg-gray-400' }}"></div>
                                                <div class="flex items-center">
                                                    @if($method->logo_url)
                                                        <img class="h-8 w-8 rounded object-cover object-center" src="{{ $method->logo_url }}" alt="">
                                                    @else
                                                        <div class="flex items-center justify-center h-8 w-8 bg-gray-200 rounded dark:bg-gray-700">
                                                            <x-heroicon-o-photograph class="w-5 h-5 text-gray-400 dark:text-gray-500" />
                                                        </div>
                                                    @endif
                                                    <span class="ml-2 truncate">
                                                        <span>{{ $method->title }} </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                            <div class="flex items-center">
                                                <span x-data="{ on: @if($method->is_enabled) true @else false @endif }" role="checkbox" tabindex="0" wire:click="toggleStatus({{ $method->id }}, {{ $method->is_enabled ? 1 : 0 }})" x-on:toggle-saved-{{ $method->id }}.window="on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" @focus="focused = true" @blur="focused = false" class="group relative inline-flex items-center justify-center flex-shrink-0 h-5 w-10 cursor-pointer focus:outline-none">
                                                    <span aria-hidden="true" :class="{ 'bg-blue-600': on, 'bg-gray-200 dark:bg-gray-700': !on }" class="absolute h-4 w-9 mx-auto rounded-full transition-colors ease-in-out duration-200 bg-gray-200"></span>
                                                    <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="absolute left-0 inline-block h-5 w-5 border border-gray-200 rounded-full bg-white shadow transform group-focus:shadow-outline group-focus:border-blue-300 transition-transform ease-in-out duration-200 translate-x-0"></span>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5">
                                            @if($method->link_url)
                                                <a href="{!! starts_with($method->link_url, ['http://', 'https://']) ? $method->link_url : "https://{$method->link_url}" !!}" target="_blank" class="inline-flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-400 dark:hover:text-gray-300 font-medium text-sm leading-5">
                                                    {{ $method->link_url }}
                                                    <svg class="w-5 h-5 -mr-1 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="inline-flex h-0.5 rounded w-6 bg-gray-700 dark:bg-gray-500"></span>
                                            @endif
                                        </td>
                                        <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500 text-right dark:text-gray-400">
                                            <time datetime="{{ $method->created_at->format('Y-m-d') }}" class="capitalize">{{ $method->created_at->formatLocalized('%d %B, %Y') }}</time>
                                        </td>
                                        <td class="pr-6">
                                            <x-shopper-dropdown customAlignmentClasses="right-12 -bottom-1">
                                                <x-slot name="trigger">
                                                    <button id="payment-options-menu-{{ $method->id }}" aria-has-popup="true" :aria-expanded="open" type="button" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 dark:focus:bg-gray-700 transition ease-in-out duration-150">
                                                        <x-heroicon-s-dots-vertical class="w-5 h-5" />
                                                    </button>
                                                </x-slot>

                                                <x-slot name="content">
                                                    <div class="py-1">
                                                        <button wire:click="$emit('openModal', 'shopper-modals.update-payment-method', {{ json_encode([$method->id]) }})" wire:key="{{ $method->id }}" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white" role="menuitem">
                                                            <x-heroicon-s-pencil-alt class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" />
                                                            {{ __('Edit') }}
                                                        </button>
                                                    </div>
                                                    <div class="border-t border-gray-100 dark:border-gray-600"></div>
                                                    <div class="py-1">
                                                        <button wire:click="removePayment({{ $method->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white" role="menuitem">
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
                                        <td colspan="5" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900 dark:text-white">
                                            <div class="flex justify-center items-center space-x-2">
                                                <x-heroicon-o-credit-card class="h-8 w-8 text-gray-400" />
                                                <span class="font-medium py-8 text-gray-400 text-xl">{{ __('No payments methods found') }}...</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-4 py-3 flex items-center rounded-b-md justify-between border-t border-gray-200 sm:px-6 dark:border-gray-700">
                    <div class="flex-1 flex justify-between sm:hidden">
                        {{ $methods->links('shopper::livewire.wire-mobile-pagination-links') }}
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm leading-5 text-gray-700 dark:text-gray-300">
                                {{ __('Showing') }}
                                <span class="font-medium">{{ ($methods->currentPage() - 1) * $methods->perPage() + 1 }}</span>
                                {{ __('to') }}
                                <span class="font-medium">{{ ($methods->currentPage() - 1) * $methods->perPage() + count($methods->items()) }}</span>
                                {{ __('of') }}
                                <span class="font-medium"> {!! $methods->total() !!}</span>
                                {{ __('results') }}
                            </p>
                        </div>
                        {{ $methods->links() }}
                    </div>
                </div>
            </div>
        </div>
        <div x-cloak x-show="currentTab === 'stripe'">
            <livewire:shopper-settings.payments.stripe />
        </div>
    </div>

</div>
