@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

<div>
    <x-shopper-heading>
        <x-slot name="title">
            {{ __('Orders') }}
        </x-slot>
    </x-shopper-heading>

    @if($total === 0)
        <x-shopper-empty-state
            :title="__('Manage customers orders')"
            :content="__('When customers place orders, this is where all the processing will be done, the management of refunds and the tracking of their order.')"
            permission="add_orders"
            class="lg:pb-0"
        >
            <div class="flex-shrink-0">
                <svg class="w-auto h-64 lg:h-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 290.5 230">
                    <style>
                        .st0{fill:#22264d}.st1{fill:#3b82f6}.st2{fill:#ebe6e6}.st3{fill:#60a5fa}.st4{fill:snow}.st5{fill:#93c5fd}.st6{fill:#bd9a7b}.st7{fill:#ebc9aa}.st8{fill:#d6b392}.st10{fill:#fff}.st14{fill:#a0c4fa}.st16{fill:none;stroke:#fff;stroke-miterlimit:10;stroke-linecap:round}.st18{font-family:&apos;Montserrat-SemiBold&apos;}.st19{font-size:3.7727px}.st20{fill:none;stroke:#d9e6ff;stroke-miterlimit:10}.st21{fill:#d9e6ff}.st22{fill:#80bd82}.st25{fill:#fcc1ab}.st27{fill:#3f83f8}
                    </style>
                    <g id="OBJECTS">
                        <path class="st0" d="M171.7 185.5v30.1h41.2v-30.1h-41.2zm25.3 28.4h-9.5v-14.8h9.5v14.8z"/>
                        <path class="st1" d="M168.6 182.9h9.5v4.1h-9.5z"/>
                        <path class="st2" d="M178.1 182.9h9.5v4.1h-9.5z"/>
                        <path class="st1" d="M187.6 182.9h9.5v4.1h-9.5z"/>
                        <path class="st2" d="M197 182.9h9.5v4.1H197z"/>
                        <path class="st1" d="M206.5 182.9h9.5v4.1h-9.5z"/>
                        <path class="st3" d="M174 178.5h7.3l-3.2 4.4h-9.5z"/>
                        <path class="st4" d="M181.3 178.5h7.3l-1 4.4h-9.5z"/>
                        <path class="st3" d="M188.6 178.5h7.4l1 4.4h-9.4z"/>
                        <path class="st4" d="M196 178.5h7.3l3.2 4.4H197z"/>
                        <path class="st3" d="M203.3 178.5h7.3l5.4 4.4h-9.5z"/>
                        <path class="st1" d="M178.1 186.9c0 2.8-2.1 5.1-4.7 5.1s-4.7-2.3-4.7-5.1"/>
                        <path class="st2" d="M187.6 186.9c0 2.8-2.1 5.1-4.7 5.1s-4.7-2.3-4.7-5.1"/>
                        <path class="st1" d="M197 186.9c0 2.8-2.1 5.1-4.7 5.1s-4.7-2.3-4.7-5.1"/>
                        <path class="st2" d="M206.5 186.9c0 2.8-2.1 5.1-4.7 5.1s-4.7-2.3-4.7-5.1"/>
                        <path class="st1" d="M216 186.9c0 2.8-2.1 5.1-4.7 5.1s-4.7-2.3-4.7-5.1m-13.4-29.5c-.6 0-1.2.1-1.7.2-2.8.6-5 3.1-5 6.2 0 3.5 6.3 9.6 6.3 9.6l.4-.1s6.3-6.1 6.3-9.6-2.8-6.3-6.3-6.3zm.5 9.1h-.5c-1.5 0-2.7-1.2-2.7-2.7 0-1.2.8-2.2 1.8-2.6h.5c1.5 0 2.7 1.2 2.7 2.7.1 1.2-.7 2.2-1.8 2.6z"/>
                        <path class="st3" d="M193.2 157.4c-3.5 0-6.3 2.8-6.3 6.3s6.3 9.6 6.3 9.6 6.3-6.1 6.3-9.6-2.8-6.3-6.3-6.3zm.1 9.1c-1.5 0-2.7-1.2-2.7-2.7 0-1.5 1.2-2.7 2.7-2.7 1.5 0 2.7 1.2 2.7 2.7 0 1.5-1.2 2.7-2.7 2.7z"/>
                        <path class="st5" d="M193.1 161.1h-.2c-1.5 0-2.7 1.2-2.7 2.7 0 1.5 1.2 2.7 2.7 2.7h.2c-1.4-.1-2.5-1.3-2.5-2.7 0-1.4 1.1-2.6 2.5-2.7z"/>
                        <path class="st5" d="M193.2 157.4h-.2c3.4.1 6.1 2.9 6.1 6.3 0 3.1-5 8.3-6.1 9.4l.2.2s6.3-6.1 6.3-9.6-2.8-6.3-6.3-6.3z"/>
                        <path class="st6" d="M209.1 221.4l-8.1-1.3v-11.6l8.1 1.3z"/>
                        <path class="st7" d="M209.1 209.8l-8.1-1.3 6.9-1.3 8.1 1.3z"/>
                        <path class="st8" d="M209.1 221.4l6.9-1.3v-11.6l-6.9 1.3z"/>
                        <path class="st6" d="M210.8 210.1l-6.6-1v-9.5l6.6 1z"/>
                        <path class="st7" d="M210.8 200.6l-6.6-1 5.6-1.1 6.7 1.1z"/>
                        <path class="st8" d="M210.8 210.1l5.7-1v-9.5l-5.7 1z"/>
                        <path class="st6" d="M219.2 223.9l-6.6-1.1v-9.5l6.6 1.1z"/>
                        <path class="st7" d="M219.2 214.4l-6.6-1.1 5.6-1 6.7 1z"/>
                        <path class="st8" d="M219.2 223.9l5.7-1.1v-9.5l-5.7 1.1z"/>
                        <path d="M95.7 17.5C92.2 19.2 40.8 44 34.5 89c-5.1 35.9 20.5 70.9 51.2 73.2 27.8 2.1 35.3-15.8 69.2-12.1 23.5 2.6 45.1 1.1 55-4.5 21.4-12.1 29.2-39.4 29.1-61.6-.1-17.7-6.6-51.9-46.8-70.6-43.1-20-93.7 2.9-96.5 4.1z" fill="#eff6ff"/>
                        <path class="st10" d="M201.3 138.7H65.6c-3 0-5.5-2.5-5.5-5.5V36.8c0-3 2.5-5.5 5.5-5.5h135.8c3 0 5.5 2.5 5.5 5.5v96.4c-.1 3-2.5 5.5-5.6 5.5z"/>
                        <path class="st1" d="M201.7 31.3H65.2c-2.8 0-5.1 2.3-5.1 5.1v5.4h146.8v-5.4c-.1-2.8-2.4-5.1-5.2-5.1z"/>
                        <path d="M193.2 36.3c0 .5-.4.9-.9.9s-.9-.4-.9-.9.4-.9.9-.9.9.4.9.9z" fill="#f8b4b4"/>
                        <path d="M197.6 36.3c0 .5-.4.9-.9.9s-.9-.4-.9-.9.4-.9.9-.9.9.4.9.9z" fill="#faca15"/>
                        <path d="M202 36.3c0 .5-.4.9-.9.9s-.9-.4-.9-.9.4-.9.9-.9.9.4.9.9z" fill="#84e1bc"/>
                        <ellipse transform="rotate(-45.001 191.526 124.565)" class="st14" cx="191.5" cy="124.6" rx="9.6" ry="9.6"/>
                        <path d="M190.8 129.1c0 .4-.4.8-.8.8s-.8-.4-.8-.8.4-.8.8-.8.8.4.8.8zm3.3 0c0 .4-.4.8-.8.8s-.8-.4-.8-.8.4-.8.8-.8c.5 0 .8.4.8.8z" fill="none" stroke="#fff" stroke-miterlimit="10"/>
                        <path class="st16" d="M189.8 121.7h6c.4 0 .6.4.5.8l-2.3 4.1c0 .1-.1.2-.3.2H189c-.2 0-.3-.1-.4-.3l-.8-6.2h-1.3m6.8 6.4h1.8"/>
                        <path class="st16" d="M193.4 128.3h-4.5c-.5 0-1.1.1-1.1-.8s.6-.8 1.1-.8h3.5"/>
                        <path class="st3" d="M208.1 47l-2.4 3.9.7 2.7 14 8.5 4.6-7.8-14-8.5-2.9 1.2zm1.6 3.6c-.2.3-.6.4-1 .2-.3-.2-.4-.6-.2-1 .2-.3.6-.4 1-.2.3.2.4.7.2 1z"/>
                        <path d="M175.3 31.1c1.7-.4 3.5-.7 5.3-.7 1.8 0 3.6.4 5.2 1.2 1.6.8 2.9 2.2 3.8 3.8.9 1.6 1.5 3.3 2.3 4.8.8 1.6 1.8 3 3.1 4.3 1.2 1.2 2.7 2.2 4.3 3 3.2 1.5 6.7 2.2 10.2 2.4v.5c-3.6-.3-7.2-1-10.4-2.6-1.6-.8-3.1-1.8-4.4-3.1-1.3-1.3-2.3-2.8-3.1-4.4-.8-1.6-1.4-3.3-2.3-4.8-.8-1.5-2.1-2.9-3.7-3.7-1.5-.8-3.3-1.2-5.1-1.2-1.8 0-3.5.2-5.3.7l.1-.2z" fill="#2a2e54"/>
                        <path class="st6" d="M92.1 88.5l-14.7-2.3v-21l14.7 2.3z"/>
                        <path class="st7" d="M92.1 67.5l-14.7-2.3 12.5-2.3 14.7 2.3z"/>
                        <path class="st8" d="M92.1 88.5l12.5-2.3v-21l-12.5 2.3z"/>
                        <circle class="st3" cx="104.6" cy="72.2" r="3.7"/>
                        <text transform="translate(103.43 73.528)" class="st10 st18 st19">
                            $
                        </text>
                        <path class="st20" d="M113.8 122H71.6c-1.9 0-3.5-1.6-3.5-3.5V51.4c0-1.9 1.6-3.5 3.5-3.5h42.3c1.9 0 3.5 1.6 3.5 3.5v67.1c-.1 2-1.6 3.5-3.6 3.5zm-39.3-21.1H95M74.5 105H95m-20.5 4.1H95"/>
                        <path class="st3" d="M86 65.1l-4.3.8 3.5.5 4.3-.8z"/>
                        <path class="st1" d="M85.2 70.1v-3.7l-3.5-.5v3.5z"/>
                        <path class="st20" d="M167.4 122h-42.3c-1.9 0-3.5-1.6-3.5-3.5V51.4c0-1.9 1.6-3.5 3.5-3.5h42.3c1.9 0 3.5 1.6 3.5 3.5v67.1c0 2-1.5 3.5-3.5 3.5zm22.7-42.1h9.3m-9.3 3.3h9.3m-9.3 3.3h9.3"/>
                        <path class="st21" d="M179 79.1h8.2v8.2H179zm0-14.4h20.5v8.2H179z"/>
                        <path class="st20" d="M190.1 93.5h9.3m-9.3 3.3h9.3m-9.3 3.4h9.3"/>
                        <path class="st21" d="M179 92.8h8.2v8.2H179z"/>
                        <path class="st22" d="M83 198.1s8.6-15.6 6.9-21.9c0 0-6.7 11.7-6.9 21.9z"/>
                        <path d="M85 193s-4 7.9-5.1 14.5M78 190.6s4.5 8.2 4.1 16.9m0-19.1l-.8 19.3" fill="none" stroke="#80bd82" stroke-miterlimit="10"/>
                        <path class="st22" d="M82.1 190.6s1.2-24.4-5.1-32c-.1 0-1.4 18.4 5.1 32z"/>
                        <path d="M82.1 194.2s7.2-23.7 2.9-32.2c0 0-5.8 17.8-2.9 32.2zm-1.7 3.4s-4.1-24.2-12-30.7c0 0 2.7 18.2 12 30.7z" fill="#47a68b"/>
                        <path class="st14" d="M76.4 200.9h10.1l-1.2 11.6h-7.7z"/>
                        <path class="st25" d="M119.6 99.1s-19.1 3.9-19.6 4.7c-.5.8 7.2 4.5 7.2 4.5s11.6-1.6 15.3-4.2c3.8-2.5-2.9-5-2.9-5z"/>
                        <path class="st25" d="M117.4 100.4c1.6-3.7 5.7-13.5 5.7-13.5l2.9.3s-.5 13.6-2.4 16.2c-1.9 2.5-7.4-.2-6.2-3z"/>
                        <path d="M85.2 216.3c0 1.7 10 3.2 22.3 3.2s22.3-1.4 22.3-3.2c0-1.7-10-3.2-22.3-3.2-12.4 0-22.3 1.4-22.3 3.2z" opacity=".2" fill="#e2e2e2"/>
                        <path class="st25" d="M107.8 126.3l-8.2 48.2-13.7 32.8 4.6 2.1 19.2-32 13.2-45.5z"/>
                        <path class="st0" d="M86.6 205.9s3 2.8 5.2 1.6c0 0 .6.9 1.8 3 0 0 4.5 3.2 5.2 5.9 0 0-7.9-.7-15.6-4-.1-.1 1.3-4.6 3.4-6.5z"/>
                        <path class="st25" d="M122.4 130.8l-13 3.4.5 2.6c.9 5.5-3.9 3.7-1.9 8.9l17.6 29.7-1 31.4-.6 6.8 5.6 1.9 7.3-41.2-14.5-43.5z"/>
                        <path class="st0" d="M124.1 212.5s1.7 2.7 6 0c0 0 .1.5 2.9 2.9 0 0 6.2 1.1 8 3.5 0 0-9.4 2-18.5 1 .1-.1.3-4.6 1.6-7.4z"/>
                        <path class="st25" d="M106.6 103.3s.7-6.2.4-6.7c-.3-.5-4.6-5.1-4.4-8.9.2-3.8 4.2-7.4 9.2-6.4 4.9.9 6.1 3.7 6.4 6.3.1.8-.6 1.7-.2 3.8.7 3.3-.4 4.6-1.8 7.8-.1.2-.4.3-.7.4-.8.1-2.4 0-3.3-.8l.8 3.3c.1-.1-3.5 2.1-6.4 1.2z"/>
                        <path class="st0" d="M116.9 85s-1.7 4-5.2 6.5c0 0-1.5-1.7-2.6-.1-1.3 1.9 2.2 3.3 2.2 3.3l-4.3 2s-4.6-4-4.9-7.8c-.2-3.9 2.1-8 6.9-8.8 5.5-.9 10.5 3.5 8.9 6.7.2.1.2-.7-1-1.8z"/>
                        <path class="st25" d="M117.4 89.8s1.3 2 1.8 2.9c.4.7-1.1 1.5-1.6 1.4-.4-.2-.2-4.3-.2-4.3z"/>
                        <path class="st27" d="M100.2 103.8c1.7-1.3 3.9-.7 6.5-2.7h6.3s2.3 1.7 6 1.7c2.8 0 4.1 5.3 4.1 5.3-.9-.3-3.8 10.2-3.8 10.2l2.3 6.4-7 5.8c-7.9-.8-8.5-4.6-8.5-4.6l-5.7-13.8c-1.2-2.1-2.1-6.8-.2-8.3z"/>
                        <path class="st6" d="M140.3 102.9l-15.2-2.6V76.2l15.2 2.7z"/>
                        <path class="st7" d="M140.3 78.9l-15.2-2.7 13-2.7 15.2 2.7z"/>
                        <path class="st8" d="M140.3 102.9l13-2.6V76.2l-13 2.7z"/>
                        <path class="st25" d="M135.5 105s-14.4-1.9-15.3-1.5c-.9.4-1.5 6.9-1.5 6.9s11.1.5 15.6.4c4.5-.2 1.2-5.8 1.2-5.8zm13-8s.9-2.5 1.9-2.6c2.1-.2 3-1.4 3-.8-.1 1.2-1 1.8-2.5 1.9l-2.4 1.5z"/>
                        <path class="st25" d="M149.8 95.5s3.7-.4 4.3-.3c.9.1.7 1.1.4 1.5-.2.3.5.7-.1 1.6-.4.6.1.6-.1 1.2-.2.5-.9.6-.8 1 0 .9-.8.8-1.3 1.1-.5.2-2.6.4-4.3-1-2.2-1.7 1.9-5.1 1.9-5.1z"/>
                        <path class="st25" d="M133.6 104.8c3.7-1.6 14-6.2 14-6.2l1.9 2.2s-11.2 9.3-14.3 9.8c-3.1.6-4.4-4.6-1.6-5.8z"/>
                        <path class="st27" d="M113.4 101.9s5 .2 13.6 2c0 0 .1 6.4-.2 7.8.1.1-20.7-.5-13.4-9.8z"/>
                        <path class="st25" d="M123.1 86.7s.9-2.4 1.3-3.4 3.6-1.6 4.6-2.1c1.1-.6 0 1.9-2.2 2.4 0 0-.8 4.8-4 4.1"/>
                        <ellipse cx="116.6" cy="90.7" rx=".2" ry=".5" fill="#2a2c41"/>
                        <path d="M109.7 92.1s.5-.9 1.4-.1c0 0-1.2.9.2 1.3" fill="none" stroke="#2a2c41" stroke-width=".25" stroke-miterlimit="10"/>
                        <path class="st0" d="M104.8 82.6s-4.8 2.7-4.8 14.7c0 11.7-2.9 13-3.2 20.2-.3 7.3 13.5 9.6 16 .7 3.2-11.1-1.5-15.7-1.5-23.5l-3.9-.8-.8-12.3m-.5 44.3s3.9 2.4 15.5-1.2l11.8 32.7s-11.8 15.8-34.2 5.7c-.1 0-3.3-20.8 6.9-37.2z"/>
                        <path class="st27" d="M119.6 108.8s5.3 5.5.5 9.7c-3.9 3.4-.5-9.7-.5-9.7z"/>
                        <path class="st3" d="M157 83.4c0 2-1.6 3.7-3.7 3.7s-3.7-1.6-3.7-3.7 1.6-3.7 3.7-3.7 3.7 1.6 3.7 3.7z"/>
                        <text transform="translate(152.107 84.686)" class="st10 st18 st19">
                            $
                        </text>
                        <path class="st3" d="M134.5 76.4l-4.2.7 3.4.6 4.3-.8z"/>
                        <path class="st1" d="M133.7 81.5v-3.8l-3.4-.6v3.6z"/>
                    </g>
                </svg>
            </div>
        </x-shopper-empty-state>
    @else
        <div class="mt-6 bg-white shadow rounded-md">
            <div class="p-4 sm:p-6 sm:pb-4">
                <div class="relative z-20 flex items-center space-x-4">
                    <div class="flex flex-1">
                        <label for="filter" class="sr-only">{{ __('Search by order #, firstname or email...') }}</label>
                        <div class="flex flex-grow rounded-md shadow-sm">
                            <div class="relative flex-grow focus-within:z-10">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                                    </svg>
                                </div>
                                <x-shopper-input.text id="filter" wire:model.debounce.350ms="search" class="pl-10" placeholder="{{ __('Search by order #, firstname or email...') }}" />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <x-shopper-loader wire:loading wire:target="search" class="text-blue-600"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="whitespace-no-wrap text-sm font-medium text-gray-500">{{ __('Per Page') }}</span>
                        <x-shopper-input.select wire:model="perPage" class="w-20" aria-label="{{ __('Per Page items') }}">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </x-shopper-input.select>
                    </div>
                    <div
                        x-data="{ open: false }"
                        x-init="flatpickr('.date', { dateFormat: 'Y-m-d' }); flatpickr($refs.datepicker, { dateFormat: 'Y-m-d' });"
                        @keydown.window.escape="open = false"
                        class="relative inline-block text-left"
                    >
                        <div>
                            <x-shopper-default-button @click="open = !open" type="button" id="options-menu" aria-haspopup="true" aria-expanded="true" x-bind:aria-expanded="open">
                                {{ __("Filters") }}
                                <svg class="-mr-1 ml-2 h-5 w-5" x-description="Heroicon name: chevron-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </x-shopper-default-button>
                        </div>
                        <div x-cloak x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg">
                            <div class="rounded-md bg-white shadow-xs px-4 py-3 space-y-3">
                                <div>
                                    <label class="uppercase tracking-wider text-xs text-gray-500 leading-4 font-medium" for="orderStatus">{{ __('Select Status') }}</label>
                                    <x-shopper-input.select wire:model="status" id="orderStatus" class="mt-2">
                                        @foreach($orderStatus as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </x-shopper-input.select>
                                </div>
                                <div>
                                    <label class="uppercase tracking-wider text-xs text-gray-500 leading-4 font-medium" for="orderStatus">{{ __('Select Dates') }}</label>
                                    <div class="grid gap-2 grid-cols-2 mt-2">
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <input wire:model="dateStart" aria-label="{{ __('Start date') }}" id="dateStart" class="date form-input block w-full pl-10 sm:text-sm sm:leading-5" readonly autocomplete="off" placeholder="{{ __("Start date") }}" />
                                        </div>
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <input wire:model="dateEnd" aria-label="{{ __('End date') }}" id="endDate" x-ref="datepicker" class="form-input block w-full pl-10 sm:text-sm sm:leading-5" readonly autocomplete="off" placeholder="{{ __("End date") }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button wire:click="resetFilters" type="button" class="block py-2 text-sm text-left leading-5 text-gray-500 hover:text-blue-600">{{ __("Reset filters") }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="align-middle inline-block min-w-full">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-t border-gray-200">
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                #
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{ __("Date") }}
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{ __("Status") }}
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{ __("Customer") }}
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{ __("Purchased") }}
                            </th>
                            <th class="hidden md:table-cell px-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{ __("Total") }}
                            </th>
                            <th class="pr-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100" x-max="1">
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-6 py-3 table-cell whitespace-no-wrap text-sm leading-5 text-gray-500 font-medium">
                                    @can('read_orders')
                                        <a href="{{ route('shopper.orders.show', $order) }}" class="truncate text-gray-900 hover:text-gray-700">
                                            <span>{{ $order->number }}</span>
                                        </a>
                                    @else
                                        <span>{{ $order->number }}</span>
                                    @endcan
                                </td>
                                <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                    <time datetime="{{ $order->created_at->format('Y-m-d') }}">{{ $order->created_at->diffForHumans() }}</time>
                                </td>
                                <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 border-2 rounded-full text-xs font-medium {{ $order->status_classes }}">
                                        {{ $order->formatted_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 table-cell whitespace-no-wrap text-sm leading-5 text-gray-500 font-medium truncate">
                                    <div class="flex items-center">
                                        <img class="h-7 w-7 rounded-full" src="{{ $order->customer->picture }}" alt="">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-600">{{ $order->customer->full_name }}</p>
                                            <p class="text-xs leading-4 text-gray-400">{{ $order->customer->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                    @if($order->items_count === 1)
                                        <span class="font-medium text-gray-600">
                                            {{ $order->items->first()->name }}
                                        </span>
                                    @endif
                                    @if($order->items_count > 1)
                                        <span class="font-medium text-gray-600">{{ $order->items->first()->name }}</span>
                                        + {{ __(':number more', ['number' => $order->items_count - 1]) }}
                                    @endif
                                </td>
                                <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500 text-right">
                                    <span>{{ $order->total }}</span>
                                </td>
                                <td class="pr-6">
                                    @can('read_orders')
                                        <div x-data="{ open: false }" x-on:item-removed.window="open = false" @keydown.escape="open = false" @click.away="open = false" class="relative flex justify-end items-center">
                                            <button id="project-options-menu-0" aria-has-popup="true" :aria-expanded="open" type="button" @click="open = !open" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                <svg class="w-5 h-5" x-description="Heroicon name: dots-vertical" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                            <div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="mx-3 origin-top-right absolute right-7 top-0 w-48 mt-1 rounded-md shadow-lg" style="display: none;">
                                                <div class="relative z-10 rounded-md bg-white shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="project-options-menu-0">
                                                    <div class="py-1">
                                                        <x-shopper-dropdown-link :href="route('shopper.orders.show', $order)" role="menuitem">
                                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                            </svg>
                                                            {{ __("View order") }}
                                                        </x-shopper-dropdown-link>
                                                    </div>
                                                    @can('delete_orders')
                                                        <div class="border-t border-gray-100"></div>
                                                        <div class="py-1">
                                                            <x-shopper-dropdown-button wire:click="archived({{ $order->id }})" type="button">
                                                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                                                                    <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                                                                </svg>
                                                                {{ __("Archived") }}
                                                            </x-shopper-dropdown-button>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                    <div class="flex justify-center items-center space-x-2">
                                        <svg class="h-8 w-8 text-cool-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <span class="font-medium py-8 text-cool-gray-400 text-xl">{{ __("No orders found") }}...</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-white px-4 py-3 flex items-center rounded-b-md justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $orders->links('shopper::livewire.wire-mobile-pagination-links') }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm leading-5 text-gray-700">
                            {{ __('Showing') }}
                            <span class="font-medium">{{ ($orders->currentPage() - 1) * $orders->perPage() + 1 }}</span>
                            {{ __('to') }}
                            <span class="font-medium">{{ ($orders->currentPage() - 1) * $orders->perPage() + count($orders->items()) }}</span>
                            {{ __('of') }}
                            <span class="font-medium"> {!! $orders->total() !!}</span>
                            {{ __('results') }}
                        </p>
                    </div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    @endif

    <x-shopper-learn-more name="orders" link="https://docs.laravelshopper.io/docs/orders" />
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush
