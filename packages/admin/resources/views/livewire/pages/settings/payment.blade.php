<div
    x-data="{
        options: ['general'],
        words: { 'general': '{{ __('shopper::words.general') }}' },
        currentTab: 'general',
        activeTab(tab) {
            return this.currentTab === tab
        },
    }"
>
    <x-shopper::container>
        <x-shopper::breadcrumb
            :back="route('shopper.settings.index')"
            :current="__('shopper::pages/settings.payment.title')"
        >
            <x-untitledui-chevron-left class="h-4 w-4 shrink-0 text-gray-300 dark:text-gray-600" />
            <x-shopper::breadcrumb.link
                :link="route('shopper.settings.index')"
                :title="__('shopper::words.settings')"
            />
        </x-shopper::breadcrumb>
        <x-shopper::heading class="my-6">
            <x-slot name="title">
                {{ __('shopper::pages/settings.payment.title') }}
            </x-slot>

            <x-slot name="action">
                <x-shopper::buttons.primary
                    wire:click="$dispatch(
                        'openModal',
                        { component: 'shopper-modals.payment-method-form' }
                    )"
                    type="button"
                >
                    {{ __('shopper::pages/settings.payment.create_payment') }}
                </x-shopper::buttons.primary>
            </x-slot>
        </x-shopper::heading>
    </x-shopper::container>

    <div class="relative space-y-4 border-gray-200 px-4 dark:border-gray-700 lg:border-y lg:px-0">
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
                    class="whitespace-no-wrap border-b-2 px-1 pb-4 text-sm font-medium leading-5 focus:outline-none"
                    :class="activeTab('general') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-gray-300 dark:hover:border-gray-400 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500'"
                >
                    {{ __('General') }}
                </button>
            </nav>
        </div>
    </div>

    <div class="mt-6 pb-10">
        <div x-show="currentTab === 'general'">
            <div class="w-full px-4 lg:max-w-xl 2xl:px-6">
                <x-shopper::forms.search
                    label="Search payments"
                    :placeholder="__('shopper::layout.forms.placeholder.search_payment')"
                />
            </div>
            <div class="mt-5">
                <div class="inline-block min-w-full align-middle">
                    <table class="min-w-full">
                        <thead>
                            <x-shopper::tables.table-row class="border-t">
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
                                <x-shopper::tables.table-head class="hidden text-right lg:table-cell">
                                    {{ __('shopper::layout.forms.label.updated_at') }}
                                </x-shopper::tables.table-head>
                                <x-shopper::tables.table-head class="pr-6 text-right" />
                            </x-shopper::tables.table-row>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700" x-max="1">
                            @forelse ($methods as $method)
                                <tr wire:key="{{ $method->id }}">
                                    <td
                                        class="whitespace-no-wrap px-6 py-3 text-sm font-medium leading-5 text-gray-900 dark:text-white"
                                    >
                                        <div class="flex items-center space-x-3 lg:pl-2">
                                            <div
                                                class="{{ $method->is_enabled ? 'bg-green-600' : 'bg-gray-400' }} h-2.5 w-2.5 shrink-0 rounded-full"
                                            ></div>
                                            <div class="flex items-center">
                                                @if ($method->logo_url)
                                                    <img
                                                        class="h-8 w-8 rounded-md object-cover object-center"
                                                        src="{{ $method->logo_url }}"
                                                        alt=""
                                                    />
                                                @else
                                                    <div
                                                        class="flex h-8 w-8 items-center justify-center rounded-md bg-gray-100 dark:bg-gray-800"
                                                    >
                                                        <x-untitledui-image
                                                            class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                                            aria-hidden="true"
                                                        />
                                                    </div>
                                                @endif
                                                <span class="ml-2 truncate">
                                                    <span>{{ $method->title }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-no-wrap px-6 py-3 text-right text-sm font-medium leading-5">
                                        <div class="flex items-center">
                                            <span
                                                x-data="{ on: @js($method->is_enabled) }"
                                                role="checkbox"
                                                tabindex="0"
                                                wire:click="toggleStatus({{ $method->id }}, {{ $method->is_enabled ? 1 : 0 }})"
                                                x-on:toggle-saved-{{ $method->id }}.window="on = !on"
                                                @keydown.space.prevent="on = !on"
                                                :aria-checked="on.toString()"
                                                @focus="focused = true"
                                                @blur="focused = false"
                                                class="group relative inline-flex h-5 w-10 shrink-0 cursor-pointer items-center justify-center focus:outline-none"
                                            >
                                                <span
                                                    aria-hidden="true"
                                                    :class="{ 'bg-primary-600': on, 'bg-gray-200 dark:bg-gray-700': !on }"
                                                    class="absolute mx-auto h-4 w-9 rounded-full bg-gray-200 transition-colors duration-200 ease-in-out"
                                                ></span>
                                                <span
                                                    aria-hidden="true"
                                                    :class="{ 'translate-x-5': on, 'translate-x-0': !on }"
                                                    class="group-focus:shadow-outline absolute left-0 inline-block h-5 w-5 translate-x-0 transform rounded-full border border-gray-200 bg-white shadow transition-transform duration-200 ease-in-out group-focus:border-primary-300"
                                                ></span>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="whitespace-no-wrap hidden px-6 py-3 text-sm leading-5 lg:table-cell">
                                        @if ($method->link_url)
                                            <a
                                                href="{{ Illuminate\Support\Str::startsWith($method->link_url, ['http://', 'https://']) ? $method->link_url : 'http://' . $method->link_url }}"
                                                target="_blank"
                                                class="inline-flex items-center text-sm font-medium leading-5 text-gray-500 hover:text-gray-400 dark:text-gray-400 dark:hover:text-gray-300"
                                            >
                                                {{ $method->link_url }}
                                                <x-untitledui-link-external-01
                                                    class="ml-2 h-5 w-5"
                                                    aria-hidden="true"
                                                />
                                            </a>
                                        @else
                                            <span
                                                class="inline-flex h-0.5 w-6 rounded bg-gray-700 dark:bg-gray-500"
                                            ></span>
                                        @endif
                                    </td>
                                    <td
                                        class="whitespace-no-wrap hidden px-6 py-3 text-right text-sm leading-5 text-gray-500 dark:text-gray-400 lg:table-cell"
                                    >
                                        <time datetime="{{ $method->created_at->format('Y-m-d') }}" class="capitalize">
                                            {{ $method->created_at->formatLocalized('%d %B, %Y') }}
                                        </time>
                                    </td>
                                    <td class="pr-6">
                                        <x-shopper::dropdown customAlignmentClasses="right-12 -bottom-1">
                                            <x-slot name="trigger">
                                                <button
                                                    id="payment-options-menu-{{ $method->id }}"
                                                    aria-has-popup="true"
                                                    :aria-expanded="open"
                                                    type="button"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-transparent text-gray-400 transition duration-150 ease-in-out hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:focus:bg-gray-700"
                                                >
                                                    <x-untitledui-dots-vertical class="h-5 w-5" aria-hidden="true" />
                                                </button>
                                            </x-slot>

                                            <x-slot name="content">
                                                <div class="py-1">
                                                    <button
                                                        wire:click="$dispatch(
                                                            'openModal',
                                                            {
                                                                component: 'shopper-modals.payment-method-form',
                                                                arguments: { paymentId: {{ $method->id }} }
                                                            }
                                                        )"
                                                        type="button"
                                                        class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                                    >
                                                        <x-untitledui-edit-03
                                                            class="mr-2 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                                            aria-hidden="true"
                                                        />
                                                        {{ __('shopper::layout.forms.actions.edit') }}
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-100 dark:border-gray-600"></div>
                                                <div class="py-1">
                                                    <button
                                                        wire:click="removePayment({{ $method->id }})"
                                                        wire:confirm="Are you sure you want to delete this payment provider?"
                                                        type="button"
                                                        class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
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
                                    <td
                                        colspan="5"
                                        class="whitespace-no-wrap px-6 py-12 text-sm font-medium leading-5 text-gray-900 dark:text-white"
                                    >
                                        <div class="flex flex-col items-center justify-center space-y-2">
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
            <div
                class="flex items-center justify-between rounded-b-md border-t border-gray-200 px-4 py-3 dark:border-gray-700 sm:px-6"
            >
                <div class="flex flex-1 justify-between sm:hidden">
                    {{ $methods->links('shopper::livewire.wire-mobile-pagination-links') }}
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm leading-5 text-gray-700 dark:text-gray-300">
                            {{ __('shopper::words.showing') }}
                            <span class="font-medium">
                                {{ ($methods->currentPage() - 1) * $methods->perPage() + 1 }}
                            </span>
                            {{ __('shopper::words.to') }}
                            <span class="font-medium">
                                {{ ($methods->currentPage() - 1) * $methods->perPage() + count($methods->items()) }}
                            </span>
                            {{ __('shopper::words.of') }}
                            <span class="font-medium">{!! $methods->total() !!}</span>
                            {{ __('shopper::words.results') }}
                        </p>
                    </div>
                    {{ $methods->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
