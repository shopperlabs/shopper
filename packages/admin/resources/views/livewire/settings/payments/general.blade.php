<div
    x-data="{
        options: ['general', 'notchpay'],
        words: {'general': '{{ __('General') }}', 'notchpay': '{{ __('Notch Pay') }}'},
        currentTab: 'general',
        activeTab(tab) {
            return this.currentTab === tab;
        },
    }"
>
    <x-shopper::container>
        <x-shopper::breadcrumb :back="route('shopper.settings.index')" :current="__('shopper::pages/settings.payment.title')">
            <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
            <x-shopper::breadcrumb.link :link="route('shopper.settings.index')" :title="__('shopper::words.settings')" />
        </x-shopper::breadcrumb>
        <x-shopper::heading class="mt-5 border-b-0">
            <x-slot name="title">
                {{ __('shopper::pages/settings.payment.title') }}
            </x-slot>

            <x-slot name="action">
                <div class="flex space-x-3">
                    <span class="shadow-sm rounded-md">
                        <x-shopper::buttons.primary wire:click="$emit('openModal', 'shopper-modals.create-payment-method')" type="button">
                            {{ __('shopper::pages/settings.payment.create_payment') }}
                        </x-shopper::buttons.primary>
                    </span>
                </div>
            </x-slot>
        </x-shopper::heading>
    </x-shopper::container>

    <div class="relative border-secondary-200 space-y-4 px-4 lg:border-y lg:px-0 dark:border-secondary-700">
        <div class="lg:hidden">
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

        <div class="hidden lg:block">
            <nav class="-mb-px flex space-x-8 px-6">
                <button
                    @click="currentTab = 'general'"
                    type="button"
                    class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                    :class="activeTab('general') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-secondary-300 dark:hover:border-secondary-400 text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-secondary-500'"
                >
                    {{ __('General') }}
                </button>

                <button
                    @click="currentTab = 'notchpay'"
                    type="button"
                    class="flex items-center px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                    :class="activeTab('notchpay') ? 'border-green-600 text-green-600' : 'border-transparent hover:border-secondary-300 dark:hover:border-secondary-400 text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-secondary-500'"
                >
                    <x-shopper::icons.notchpay class="w-6 h-6 mr-2" />
                    {{ __('Notch Pay') }}
                </button>
            </nav>
        </div>
    </div>

    <div class="mt-6 pb-10">
        <div x-show="currentTab === 'general'">
            <div class="px-4 w-full 2xl:px-6 lg:max-w-xl">
                <x-shopper::forms.search label="Search payments" :placeholder="__('shopper::layout.forms.placeholder.search_payment')" />
            </div>
            <div class="mt-5">
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
                                <x-shopper::tables.table-head class="hidden lg:table-cell text-right">
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
                                                <img class="h-8 w-8 rounded-md object-cover object-center" src="{{ $method->logo_url }}" alt="">
                                            @else
                                                <div class="flex items-center justify-center h-8 w-8 bg-secondary-100 rounded-md dark:bg-secondary-800">
                                                    <x-untitledui-image class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
                                                </div>
                                            @endif
                                            <span class="ml-2 truncate">
                                                <span>{{ $method->title }}</span>
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
                                <td class="hidden lg:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5">
                                    @if($method->link_url)
                                        <a href="{!! starts_with($method->link_url, ['http://', 'https://']) ? $method->link_url : "https://{$method->link_url}" !!}" target="_blank" class="inline-flex items-center text-secondary-500 dark:text-secondary-400 hover:text-secondary-400 dark:hover:text-secondary-300 font-medium text-sm leading-5">
                                            {{ $method->link_url }}
                                            <x-untitledui-link-external-01 class="w-5 h-5 ml-2" />
                                        </a>
                                    @else
                                        <span class="inline-flex h-0.5 rounded w-6 bg-secondary-700 dark:bg-secondary-500"></span>
                                    @endif
                                </td>
                                <td class="hidden lg:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-secondary-500 text-right dark:text-secondary-400">
                                    <time datetime="{{ $method->created_at->format('Y-m-d') }}" class="capitalize">
                                        {{ $method->created_at->formatLocalized('%d %B, %Y') }}
                                    </time>
                                </td>
                                <td class="pr-6">
                                    <x-shopper::dropdown customAlignmentClasses="right-12 -bottom-1">
                                        <x-slot name="trigger">
                                            <button id="payment-options-menu-{{ $method->id }}" aria-has-popup="true" :aria-expanded="open" type="button" class="w-8 h-8 inline-flex items-center justify-center text-secondary-400 rounded-full bg-transparent hover:text-secondary-500 focus:outline-none focus:text-secondary-500 focus:bg-secondary-100 dark:focus:bg-secondary-700 transition ease-in-out duration-150">
                                                <x-untitledui-dots-vertical class="w-5 h-5" />
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <div class="py-1">
                                                <button wire:click="$emit('openModal', 'shopper-modals.update-payment-method', {{ json_encode([$method->id]) }})" wire:key="{{ $method->id }}" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-secondary-700 dark:text-secondary-400 hover:bg-secondary-100 dark:hover:bg-secondary-600 hover:text-secondary-900 dark:hover:text-white" role="menuitem">
                                                    <x-untitledui-edit-03 class="mr-2 h-5 w-5 text-secondary-400 group-hover:text-secondary-500" />
                                                    {{ __('shopper::layout.forms.actions.edit') }}
                                                </button>
                                            </div>
                                            <div class="border-t border-secondary-100 dark:border-secondary-600"></div>
                                            <div class="py-1">
                                                <button wire:click="removePayment({{ $method->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-secondary-700 dark:text-secondary-400 hover:bg-secondary-100 dark:hover:bg-secondary-600 hover:text-secondary-900 dark:hover:text-white" role="menuitem">
                                                    <x-untitledui-trash-03 class="mr-2 h-5 w-5 text-secondary-400 group-hover:text-secondary-500" />
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
                                    <div class="flex flex-col justify-center items-center space-y-2 py-6">
                                        <x-untitledui-coins-hand class="h-8 w-8 text-primary-500" />
                                        <span class="font-medium text-secondary-400 text-xl">
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
        <div x-cloak x-show="currentTab === 'notchpay'">
        </div>
    </div>
</div>
