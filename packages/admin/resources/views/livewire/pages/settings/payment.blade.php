<div
    x-data="{
        options: ['general'],
        words: {'general': '{{ __('General') }}'},
        currentTab: 'general',
        activeTab(tab) {
            return this.currentTab === tab;
        },
    }"
>
    <x-shopper::container>
        <x-shopper::breadcrumb :back="route('shopper.settings.index')" :current="__('shopper::pages/settings.payment.title')">
            <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-gray-300 dark:text-gray-600" />
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

    <div class="relative border-gray-200 space-y-4 px-4 lg:border-y lg:px-0 dark:border-gray-700">
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
                    :class="activeTab('general') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                >
                    {{ __('General') }}
                </button>
            </nav>
        </div>
    </div>

    <div class="mt-6 pb-10">
        <div x-show="currentTab === 'general'">
            <div class="px-4 w-full 2xl:px-6 lg:max-w-xl">
                <x-shopper::forms.search
                    label="Search payments"
                    :placeholder="__('shopper::layout.forms.placeholder.search_payment')"
                />
            </div>
            <div class="mt-5">
                <div class="align-middle inline-block min-w-full">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-t border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-700">
                                <x-shopper::tables.table-head>
                                    <span class="lg:pl-2">
                                        {{ __('shopper::layout.forms.label.title') }}
                                    </span>
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
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700" x-max="1">
                            @forelse($methods as $method)
                                <tr>
                                    <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900 dark:text-white">
                                        <div class="flex items-center space-x-3 lg:pl-2">
                                            <div class="shrink-0 w-2.5 h-2.5 rounded-full {{ $method->is_enabled ? 'bg-green-600': 'bg-gray-400' }}"></div>
                                            <div class="flex items-center">
                                                @if($method->logo_url)
                                                    <img class="h-8 w-8 rounded-md object-cover object-center" src="{{ $method->logo_url }}" alt="">
                                                @else
                                                    <div class="flex items-center justify-center h-8 w-8 bg-gray-100 rounded-md dark:bg-gray-800">
                                                        <x-untitledui-image class="w-5 h-5 text-gray-400 dark:text-gray-500" aria-hidden="true" />
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
                                            <span x-data="{ on: @entangle($method->is_enabled) }"
                                                  role="checkbox"
                                                  tabindex="0"
                                                  wire:click="toggleStatus({{ $method->id }}, {{ $method->is_enabled ? 1 : 0 }})"
                                                  x-on:toggle-saved-{{ $method->id }}.window="on = !on"
                                                  @keydown.space.prevent="on = !on"
                                                  :aria-checked="on.toString()"
                                                  @focus="focused = true"
                                                  @blur="focused = false"
                                                  class="group relative inline-flex items-center justify-center shrink-0 h-5 w-10 cursor-pointer focus:outline-none"
                                            >
                                                <span aria-hidden="true"
                                                      :class="{ 'bg-primary-600': on, 'bg-gray-200 dark:bg-gray-700': !on }"
                                                      class="absolute h-4 w-9 mx-auto rounded-full transition-colors ease-in-out duration-200 bg-gray-200"></span>
                                                <span aria-hidden="true"
                                                      :class="{ 'translate-x-5': on, 'translate-x-0': !on }"
                                                      class="absolute left-0 inline-block h-5 w-5 border border-gray-200 rounded-full bg-white shadow transform group-focus:shadow-outline group-focus:border-primary-300 transition-transform ease-in-out duration-200 translate-x-0"></span>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="hidden lg:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5">
                                        @if($method->link_url)
                                            <a href="{!! Illuminate\Support\Str::startsWith($method->link_url, ['http://', 'https://']) ? $method->link_url : "http://{$method->link_url}" !!}" target="_blank" class="inline-flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-400 dark:hover:text-gray-300 font-medium text-sm leading-5">
                                                {{ $method->link_url }}
                                                <x-untitledui-link-external-01 class="w-5 h-5 ml-2" aria-hidden="true" />
                                            </a>
                                        @else
                                            <span class="inline-flex h-0.5 rounded w-6 bg-gray-700 dark:bg-gray-500"></span>
                                        @endif
                                    </td>
                                    <td class="hidden lg:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500 text-right dark:text-gray-400">
                                        <time datetime="{{ $method->created_at->format('Y-m-d') }}" class="capitalize">
                                            {{ $method->created_at->formatLocalized('%d %B, %Y') }}
                                        </time>
                                    </td>
                                    <td class="pr-6">
                                        <x-shopper::dropdown customAlignmentClasses="right-12 -bottom-1">
                                            <x-slot name="trigger">
                                                <button id="payment-options-menu-{{ $method->id }}" aria-has-popup="true" :aria-expanded="open" type="button" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 dark:focus:bg-gray-700 transition ease-in-out duration-150">
                                                    <x-untitledui-dots-vertical class="w-5 h-5" aria-hidden="true" />
                                                </button>
                                            </x-slot>

                                            <x-slot name="content">
                                                <div class="py-1">
                                                    <button wire:click="$emit('openModal', 'shopper-modals.update-payment-method', {{ json_encode([$method->id]) }})" wire:key="{{ $method->id }}" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white" role="menuitem">
                                                        <x-untitledui-edit-03
                                                            class="mr-2 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                                            aria-hidden="true"
                                                        />
                                                        {{ __('shopper::layout.forms.actions.edit') }}
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-100 dark:border-gray-600"></div>
                                                <div class="py-1">
                                                    <button wire:click="removePayment({{ $method->id }})"
                                                            type="button"
                                                            class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white"
                                                            role="menuitem"
                                                    >
                                                        <x-untitledui-trash-03
                                                            class="mr-2 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                                            aria-hidden="true"
                                                        />
                                                        {{ __('shopper::layout.forms.actions.delete') }}
                                                    </button>
                                                </div>
                                            </x-slot>
                                        </x-shopper::dropdown>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900 dark:text-white">
                                        <div class="flex flex-col justify-center items-center space-y-2">
                                            <x-untitledui-credit-card-02
                                                class="h-10 w-10 text-primary-500"
                                                stroke-width="1.5"
                                                aria-hidden="true"
                                            />
                                            <span class="text-xl text-gray-400 dark:text-gray-500">
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
            <div class="px-4 py-3 flex items-center rounded-b-md justify-between border-t border-gray-200 sm:px-6 dark:border-gray-700">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $methods->links('shopper::livewire.wire-mobile-pagination-links') }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm leading-5 text-gray-700 dark:text-gray-300">
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
</div>
