<div
    x-data="{
        options: ['general', 'notchpay'],
        words: {'general': '{{ __('General') }}', 'notchpay': '{{ __('Notch Pay') }}'},
        currentTab: 'general'
    }"
>

    <x-shopper::breadcrumb :back="route('shopper.settings.index')">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400 dark:text-secondary-500" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.index')" :title="__('shopper::words.settings')" />
    </x-shopper::breadcrumb>

    <div class="mt-3 relative pb-5 border-b border-secondary-200 space-y-4 sm:pb-0 dark:border-secondary-700">
        <div class="space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
            <h3 class="text-2xl font-bold leading-6 text-secondary-900 sm:text-3xl sm:leading-9 sm:truncate dark:text-white">
                {{ __('shopper::pages/settings.payment.title') }}
            </h3>
            <div class="flex space-x-3 md:absolute md:top-3 md:right-0">
                <span class="shadow-sm rounded-md">
                    <x-shopper::buttons.primary wire:click="$emit('openModal', 'shopper-modals.create-payment-method')" type="button">
                        {{ __('shopper::pages/settings.payment.create_payment') }}
                    </x-shopper::buttons.primary>
                </span>
            </div>
        </div>
        <div>
            <div class="sm:hidden">
                <x-shopper::forms.select x-model="currentTab" aria-label="Selected tab">
                    <template x-for="option in options" :key="option">
                        <option
                            x-bind:value="option"
                            x-text="words[option]"
                            x-bind:selected="option === currentTab"
                        ></option>
                    </template>
                </x-shopper::forms.select>
            </div>
            <!-- Tabs at small breakpoint and up -->
            <div class="hidden sm:block">
                <nav class="-mb-px flex space-x-8">
                    <button @click="currentTab = 'general'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" aria-current="page" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'general' }">
                        {{ __('General') }}
                    </button>

                    <button @click="currentTab = 'notchpay'" type="button" class="inline-flex items-center whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" :class="{ 'border-green-500 text-green-600 focus:text-green-800 focus:border-green-700': currentTab === 'notchpay' }">
                        <svg class="w-6 h-6 mr-2" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                            <path d="M18.756 25.823c0 .634-.335 1.027-.984 1.03-.698 0-1.003-.404-1.006-1.083 0-.924 0-1.847-.01-2.768a2.867 2.867 0 0 0-.14-1.038c-.103-.24-.405-.546-.614-.544a.808.808 0 0 0-.37.194.831.831 0 0 0-.227.356c-.096.83-.08 1.676-.106 2.514-.016.487 0 .978-.053 1.462-.064.596-.418.923-1.018.91-.6-.015-.944-.357-.97-.962-.028-.711 0-1.41 0-2.117h.02c0-.678-.039-1.359 0-2.033.094-1.434 1.16-2.427 2.572-2.448 1.643-.027 2.775.855 2.87 2.375.085 1.38.046 2.768.036 4.152Z" fill="#37384E"/>
                            <path d="M30.656 28.572a3.678 3.678 0 0 1-2.283.29c-2.222-.468-4.409-1.105-6.608-1.683-.968-.255-1.457-.91-1.47-1.943 0-.463.017-.925 0-1.384-.032-.646.174-.934.842-.692.388.166.8.267 1.22.297.374 0 .873-.094 1.082-.356.174-.206.106-.795-.07-1.067a848.431 848.431 0 0 0-6.577-10.149c-.466-.711-1.115-.697-1.589.029a941.24 941.24 0 0 0-6.542 10.079 1.429 1.429 0 0 0-.084 1.156c.206.464.698.534 1.174.296.127-.037.257-.063.389-.08 1.808-.534 1.57-.374 1.57 1.245 0 1.886-.304 2.268-2.107 2.73-1.973.506-3.94 1.03-5.911 1.536-1.708.44-3.088-.311-3.583-1.936-.284-.962.02-1.795.537-2.59l8.896-13.657c1.297-2 2.598-4.012 3.913-6.01 1.462-2.218 3.626-2.254 5.059-.048a7774.566 7774.566 0 0 1 12.882 19.81c1.015 1.554.74 3.364-.74 4.127Z" fill="#218366"/>
                        </svg>
                        {{ __('Notch Pay') }}
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <div class="mt-10">
        <div x-show="currentTab === 'general'">
            <div class="mt-6 bg-white shadow sm:rounded-md dark:bg-secondary-800">
                <div class="p-4 sm:p-6 sm:pb-4">
                    <x-shopper::forms.search label="Search payments" :placeholder="__('shopper::layout.forms.placeholder.search_payment')" />
                </div>
                <div class="hidden sm:block">
                    <div class="align-middle inline-block min-w-full">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-t border-secondary-200 bg-secondary-50 dark:border-secondary-700 dark:bg-secondary-700">
                                    <x-shopper::tables.table-head>
                                        <span class="lg:pl-2">{{ __('shopper::layout.forms.label.title') }}</span>
                                    </x-shopper::tables.table-head>
                                    <x-shopper::tables.table-head>
                                        {{ __('shopper::layout.forms.label.status') }}
                                    </x-shopper::tables.table-head>
                                    <x-shopper::tables.table-head>
                                        {{ __('shopper::layout.forms.label.website') }}
                                    </x-shopper::tables.table-head>
                                    <x-shopper::tables.table-head class="hidden md:table-cell text-right">
                                        {{ __('shopper::layout.forms.label.updated_at') }}
                                    </x-shopper::tables.table-head>
                                    <x-shopper::tables.table-head class="pr-6 text-right" />
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-secondary-100 dark:divide-secondary-700" x-max="1">
                                @forelse($methods as $method)
                                    <tr>
                                        <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-secondary-900 dark:text-white">
                                            <div class="flex items-center space-x-3 lg:pl-2">
                                                <div class="shrink-0 w-2.5 h-2.5 rounded-full {{ $method->is_enabled ? 'bg-green-600': 'bg-secondary-400' }}"></div>
                                                <div class="flex items-center">
                                                    @if($method->logo_url)
                                                        <img class="h-8 w-8 rounded object-cover object-center" src="{{ $method->logo_url }}" alt="">
                                                    @else
                                                        <div class="flex items-center justify-center h-8 w-8 bg-secondary-200 rounded dark:bg-secondary-700">
                                                            <x-heroicon-o-photograph class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
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
                                                <span x-data="{ on: @if($method->is_enabled) true @else false @endif }" role="checkbox" tabindex="0" wire:click="toggleStatus({{ $method->id }}, {{ $method->is_enabled ? 1 : 0 }})" x-on:toggle-saved-{{ $method->id }}.window="on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" @focus="focused = true" @blur="focused = false" class="group relative inline-flex items-center justify-center shrink-0 h-5 w-10 cursor-pointer focus:outline-none">
                                                    <span aria-hidden="true" :class="{ 'bg-primary-600': on, 'bg-secondary-200 dark:bg-secondary-700': !on }" class="absolute h-4 w-9 mx-auto rounded-full transition-colors ease-in-out duration-200 bg-secondary-200"></span>
                                                    <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="absolute left-0 inline-block h-5 w-5 border border-secondary-200 rounded-full bg-white shadow transform group-focus:shadow-outline group-focus:border-primary-300 transition-transform ease-in-out duration-200 translate-x-0"></span>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5">
                                            @if($method->link_url)
                                                <a href="{!! starts_with($method->link_url, ['http://', 'https://']) ? $method->link_url : "https://{$method->link_url}" !!}" target="_blank" class="inline-flex items-center text-secondary-500 dark:text-secondary-400 hover:text-secondary-400 dark:hover:text-secondary-300 font-medium text-sm leading-5">
                                                    {{ $method->link_url }}
                                                    <svg class="w-5 h-5 -mr-1 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="inline-flex h-0.5 rounded w-6 bg-secondary-700 dark:bg-secondary-500"></span>
                                            @endif
                                        </td>
                                        <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-secondary-500 text-right dark:text-secondary-400">
                                            <time datetime="{{ $method->created_at->format('Y-m-d') }}" class="capitalize">{{ $method->created_at->formatLocalized('%d %B, %Y') }}</time>
                                        </td>
                                        <td class="pr-6">
                                            <x-shopper::dropdown customAlignmentClasses="right-12 -bottom-1">
                                                <x-slot name="trigger">
                                                    <button id="payment-options-menu-{{ $method->id }}" aria-has-popup="true" :aria-expanded="open" type="button" class="w-8 h-8 inline-flex items-center justify-center text-secondary-400 rounded-full bg-transparent hover:text-secondary-500 focus:outline-none focus:text-secondary-500 focus:bg-secondary-100 dark:focus:bg-secondary-700 transition ease-in-out duration-150">
                                                        <x-heroicon-s-dots-vertical class="w-5 h-5" />
                                                    </button>
                                                </x-slot>

                                                <x-slot name="content">
                                                    <div class="py-1">
                                                        <button wire:click="$emit('openModal', 'shopper-modals.update-payment-method', {{ json_encode([$method->id]) }})" wire:key="{{ $method->id }}" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-secondary-700 dark:text-secondary-400 hover:bg-secondary-100 dark:hover:bg-secondary-600 hover:text-secondary-900 dark:hover:text-white" role="menuitem">
                                                            <x-heroicon-s-pencil-alt class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500" />
                                                            {{ __('shopper::layout.forms.actions.edit') }}
                                                        </button>
                                                    </div>
                                                    <div class="border-t border-secondary-100 dark:border-secondary-600"></div>
                                                    <div class="py-1">
                                                        <button wire:click="removePayment({{ $method->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-secondary-700 dark:text-secondary-400 hover:bg-secondary-100 dark:hover:bg-secondary-600 hover:text-secondary-900 dark:hover:text-white" role="menuitem">
                                                            <x-heroicon-s-trash class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500" />
                                                            {{ __('shopper::layout.forms.actions.delete') }}
                                                        </button>
                                                    </div>
                                                </x-slot>
                                            </x-shopper::dropdown>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-secondary-900 dark:text-white">
                                            <div class="flex justify-center items-center space-x-2">
                                                <x-heroicon-o-credit-card class="h-8 w-8 text-secondary-400" />
                                                <span class="font-medium py-8 text-secondary-400 text-xl">
                                                    {{ __('shopper::pages/settings.payment.no_method') }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-4 py-3 flex items-center rounded-b-md justify-between border-t border-secondary-200 sm:px-6 dark:border-secondary-700">
                    <div class="flex-1 flex justify-between sm:hidden">
                        {{ $methods->links('shopper::livewire.wire-mobile-pagination-links') }}
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm leading-5 text-secondary-700 dark:text-secondary-300">
                                {{ __('shopper::words.showing') }}
                                <span class="font-medium">{{ ($methods->currentPage() - 1) * $methods->perPage() + 1 }}</span>
                                {{ __('shopper::words.to') }}
                                <span class="font-medium">{{ ($methods->currentPage() - 1) * $methods->perPage() + count($methods->items()) }}</span>
                                {{ __('shopper::words.of') }}
                                <span class="font-medium"> {!! $methods->total() !!}</span>
                                {{ __('shopper::words.results') }}
                            </p>
                        </div>
                        {{ $methods->links() }}
                    </div>
                </div>
            </div>
        </div>
        <div x-cloak x-show="currentTab === 'notchpay'">
        </div>
    </div>

</div>