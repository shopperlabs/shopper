<div>
    <div class="px-4 sm:px-6 lg:px-8">
        <span class="text-sm text-blue-600 uppercase font-medium lg:hidden">{{ __("Step :step of 3", ['step' => 1]) }}</span>
        <h1 class="text-gray-900 font-bold text-2xl leading-5 mt-2">{{ __("Shop configuration") }}</h1>
        <div class="mt-8">
            <span class="text-sm font-medium text-blue-600">{{ __("Step 1 - Shop size") }}</span>
            <h3 class="text-base mt-1.5 font-bold text-gray-900 leading-5">{{ __("You must specify the size of your shop") }}</h3>
            <p class="mt-4 text-gray-500 leading-4 text-sm lg:max-w-xl">
                {{ __("Don't Worry. You can change these setting at any time. Shopper allows you to start with the smallest level so that you can see the evolution of your shop.") }}
            </p>
        </div>
    </div>

    <div class="mt-6 lg:mt-8 px-4 sm:px-6 lg:px-8 grid gap-4 sm:gap-6 lg:grid-cols-3 lg:gap-8">
        @foreach($sizes as $size)
            <button type="button" class="relative bg-white flex w-full rounded-lg shadow-md p-4 sm:p-6 cursor-pointer">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-8 w-8 rounded-md bg-blue-600 text-white">
                        @if($size->max_products <= 100)
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M15.96 12.463c.669 0 1.213.555 1.213 1.236v1.91c0 .681-.544 1.235-1.213 1.235h-2.446c-.67 0-1.214-.554-1.214-1.235v-1.91c0-.681.544-1.236 1.214-1.236h2.446zm.33 3.146v-1.91a.335.335 0 0 0-.33-.337h-2.446c-.18 0-.331.15-.331.337v1.91c0 .183.147.337.33.337h2.447c.18 0 .33-.15.33-.337zm4.881-6.617c0 1.079-.64 2.003-1.552 2.412.004.015.004.03.004.048v7.849c0 .247-.199.449-.442.449H5.158a.447.447 0 0 1-.441-.45v-7.904a2.63 2.63 0 0 1-1.545-2.404.46.46 0 0 1 .06-.22l2.673-4.794a.435.435 0 0 1 .383-.228h11.765c.159 0 .306.09.383.228l2.677 4.793c.04.068.06.143.06.221zM6.544 4.652l-2.09 3.749h15.434l-2.093-3.749H6.544zm9.438 4.644h-3.34c.14.808.835 1.422 1.67 1.422s1.53-.614 1.67-1.422zm-4.278 0H8.361a1.711 1.711 0 0 0 1.673 1.422c.835 0 1.53-.614 1.67-1.422zm-7.62 0c.14.808.834 1.426 1.67 1.422.834 0 1.533-.614 1.673-1.422H4.083zm6.502 9.555h.004v-4.849a.634.634 0 0 0-.63-.64H8.678a.634.634 0 0 0-.629.64v4.85h2.538zm8.154 0v-7.234c-.051 0-.1.004-.15.004-.89 0-1.678-.46-2.141-1.161a2.56 2.56 0 0 1-2.137 1.16c-.886 0-1.674-.46-2.137-1.16-.467.7-1.25 1.16-2.14 1.16-.89 0-1.678-.46-2.141-1.16-.464.7-1.25 1.16-2.14 1.16-.052 0-.104 0-.155-.003v7.238h1.563v-4.853c0-.85.68-1.539 1.512-1.539h1.283c.835 0 1.512.693 1.512 1.54v4.848h7.271zm-.15-8.129c.834 0 1.53-.618 1.673-1.423h-3.344c.14.81.835 1.423 1.67 1.423z" fill="#fff"/>
                            </svg>
                        @endif
                        @if($size->max_products > 100 && $size->max_products <= 1000)
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M21.667 21.333a.333.333 0 0 1 0 .667H2.333a.333.333 0 1 1 0-.667h3v-19A.333.333 0 0 1 5.667 2h8.666a.333.333 0 0 1 .334.333V10h4.666a.333.333 0 0 1 .334.333v11h2zM9 21.333v-3A.333.333 0 0 0 8.667 18H8a.333.333 0 0 0-.333.333v3H9zm1.333-11a.333.333 0 0 1 .334-.333H14V2.667H6v18.666h1v-3a1 1 0 0 1 1-1h.667a1 1 0 0 1 1 1v3h.666v-11zm8.667 11V10.667h-8v10.666h8zM12.667 6.667A.333.333 0 0 1 13 7v2a.333.333 0 0 1-.333.333h-2A.333.333 0 0 1 10.333 9V7a.333.333 0 0 1 .334-.333h2zm-.334 2V7.333H11v1.334h1.333zm.334-5.334a.333.333 0 0 1 .333.334v2a.333.333 0 0 1-.333.333h-2a.333.333 0 0 1-.334-.333v-2a.333.333 0 0 1 .334-.334h2zm-.334 2V4H11v1.333h1.333zm-3 1.334A.333.333 0 0 1 9.667 7v2a.333.333 0 0 1-.334.333h-2A.333.333 0 0 1 7 9V7a.333.333 0 0 1 .333-.333h2zm-.333 2V7.333H7.667v1.334H9zm.333-5.334a.333.333 0 0 1 .334.334v2A.333.333 0 0 1 9.333 6h-2A.333.333 0 0 1 7 5.667v-2a.333.333 0 0 1 .333-.334h2zm-.333 2V4H7.667v1.333H9zM9.333 10a.333.333 0 0 1 .334.333v2a.333.333 0 0 1-.334.334h-2A.333.333 0 0 1 7 12.333v-2A.333.333 0 0 1 7.333 10h2zM9 12v-1.333H7.667V12H9zm.333 1.333a.333.333 0 0 1 .334.334v2a.333.333 0 0 1-.334.333h-2A.333.333 0 0 1 7 15.667v-2a.333.333 0 0 1 .333-.334h2zm-.333 2V14H7.667v1.333H9zm6.667 2a.333.333 0 0 1-.334-.333v-2a.333.333 0 0 1 .334-.333h2A.333.333 0 0 1 18 15v2a.333.333 0 0 1-.333.333h-2zm.333-2v1.334h1.333v-1.334H16zM15.667 14a.333.333 0 0 1-.334-.333v-2a.333.333 0 0 1 .334-.334h2a.333.333 0 0 1 .333.334v2a.333.333 0 0 1-.333.333h-2zM16 12v1.333h1.333V12H16zm-.333 8.667a.333.333 0 0 1-.334-.334v-2a.333.333 0 0 1 .334-.333h2a.333.333 0 0 1 .333.333v2a.333.333 0 0 1-.333.334h-2zm.333-2V20h1.333v-1.333H16zm-3.667-1.334A.333.333 0 0 1 12 17v-2a.333.333 0 0 1 .333-.333h2a.333.333 0 0 1 .334.333v2a.333.333 0 0 1-.334.333h-2zm.334-2v1.334H14v-1.334h-1.333zM12.333 14a.333.333 0 0 1-.333-.333v-2a.333.333 0 0 1 .333-.334h2a.333.333 0 0 1 .334.334v2a.333.333 0 0 1-.334.333h-2zm.334-2v1.333H14V12h-1.333zm-.334 8.667a.333.333 0 0 1-.333-.334v-2a.333.333 0 0 1 .333-.333h2a.333.333 0 0 1 .334.333v2a.333.333 0 0 1-.334.334h-2zm.334-2V20H14v-1.333h-1.333z"/>
                            </svg>
                        @endif
                        @if($size->max_products > 1000)
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 8.667h.667v.666H9v-.666zM9 10h.667v.667H9V10zm0 1.333h.667V12H9v-.667zm0 1.334h.667v.666H9v-.666zm1.333-4H11v.666h-.667v-.666zm0 1.333H11v.667h-.667V10zm0 1.333H11V12h-.667v-.667zm0 1.334H11v.666h-.667v-.666zm1.334-4h.666v.666h-.666v-.666zm0 1.333h.666v.667h-.666V10zm0 1.333h.666V12h-.666v-.667zm0 1.334h.666v.666h-.666v-.666zm1.333-4h.667v.666H13v-.666zm-2.667-1.334H11V8h-.667v-.667zm1.334 0h.666V8h-.666v-.667zm1.333 0h.667V8H13v-.667zM13 10h.667v.667H13V10zm0 1.333h.667V12H13v-.667zm0 1.334h.667v.666H13v-.666zm1.333-4H15v.666h-.667v-.666zm0 1.333H15v.667h-.667V10zm0 1.333H15V12h-.667v-.667zm0 1.334H15v.666h-.667v-.666zM21.667 7a.333.333 0 0 1 .333.333v12a.334.334 0 0 1-.333.334h-.697c.02.11.03.221.03.333v1.667a.334.334 0 0 1-.333.333H3.333A.334.334 0 0 1 3 21.667V20a2 2 0 0 1 .03-.333h-.697A.334.334 0 0 1 2 19.333v-12A.333.333 0 0 1 2.333 7h5V2.333A.333.333 0 0 1 7.667 2h8.667a.333.333 0 0 1 .333.333V7h5zm-5.334 9.333a.667.667 0 1 0-1.334.002.667.667 0 0 0 1.334-.002zm-8-.666A.667.667 0 1 0 8.335 17a.667.667 0 0 0-.002-1.334zm-2 5.666V20a1.333 1.333 0 1 0-2.666 0v1.333h2.666zm-2-4a.668.668 0 1 0 1.335-.001.668.668 0 0 0-1.335.001zm2.05 1.226a2.005 2.005 0 0 1 .986-1.31 1.315 1.315 0 0 1-.036-1.788V7.667H2.667V19h.604c.182-.315.446-.574.764-.751a1.333 1.333 0 1 1 1.93 0c.152.085.293.19.419.31zm3.284 2.774V19A1.333 1.333 0 1 0 7 19v2.333h2.667zm4 0V19a1.667 1.667 0 1 0-3.334 0v2.333h3.334zM11 15.667a1.001 1.001 0 0 0 2 0 1 1 0 1 0-2 0zm3.03 2.187c.175-.25.405-.457.672-.605A1.33 1.33 0 0 1 15.667 15c.112.002.224.017.333.047V2.667H8v12.38a1.324 1.324 0 0 1 1.298 2.202c.267.148.497.355.672.605.223-.393.555-.714.956-.923a1.668 1.668 0 0 1 0-2.55 1.667 1.667 0 0 1 2.148 2.55c.4.209.733.53.956.923zM17 20v-1a1.333 1.333 0 0 0-2.667 0v2.333H17V20zm3.333 1.333V20a1.333 1.333 0 1 0-2.666 0v1.333h2.666zm-2-4a.668.668 0 1 0 1.336-.002.668.668 0 0 0-1.336.002zm3 1.667V7.667h-4.666v7.794a1.315 1.315 0 0 1-.036 1.788 2.006 2.006 0 0 1 .985 1.31c.126-.12.267-.225.42-.31a1.333 1.333 0 1 1 1.928 0c.319.177.583.436.765.751h.604zm-3.666-9.333h.666V15h-.666V9.667zm2 0h.666V15h-.666V9.667zm-16 0h.666V15h-.666V9.667zm2 0h.666V15h-.666V9.667zM14 3.333a.334.334 0 0 1 .333.334v2.667a.332.332 0 0 1-.333.333h-4a.333.333 0 0 1-.333-.334V3.667A.333.333 0 0 1 10 3.332h4zM13.667 6V4h-3.334v2h3.334z"/>
                            </svg>
                        @endif
                    </div>
                </div>
                <div class="ml-3 text-left">
                    <h5 class="text-base leading-5 font-medium text-gray-900">{{ __($size->name) }} <span class="text-gray-400 text-xs">({{ __($size->size) }})</span></h5>
                    <p class="mt-2 text-sm leading-4 text-gray-500">
                        {{ __($size->description) }}
                    </p>
                </div>
            </button>
        @endforeach
    </div>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="mt-8 lg:mt-10 pt-8 lg:pt-10 border-t border-gray-200">
            <span class="text-sm font-medium text-blue-600">{{ __("Step 2 - Shop information") }}</span>
            <h3 class="text-base mt-1.5 font-bold text-gray-900 leading-5">{{ __("Tell us about your Shop") }}</h3>
            <p class="mt-4 text-gray-500 text-sm lg:max-w-xl">
                {{ __("This information will be useful if you want users of your site to directly contact you by email or by your phone number.") }}
            </p>
        </div>
    </div>

    <div class="mt-6 lg:mt-8 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md sm:rounded-md p-4 lg:p-6">
            <div>
                <div>
                    <div>
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start">
                            <label for="name" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                                {{ __("Store name") }} <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <div class="relative rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M22 5H2a1 1 0 0 0-1 1v4a3 3 0 0 0 2 2.82V22a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-9.18A3 3 0 0 0 23 10V6a1 1 0 0 0-1-1zm-7 2h2v3a1 1 0 1 1-2 0zm-4 0h2v3a1 1 0 1 1-2 0zM7 7h2v3a1 1 0 1 1-2 0zm-3 4a1 1 0 0 1-1-1V7h2v3a1 1 0 0 1-1 1zm10 10h-4v-2a2 2 0 1 1 4 0zm5 0h-3v-2a4 4 0 1 0-8 0v2H5v-8.18a3.17 3.17 0 0 0 1-.6 3 3 0 0 0 4 0 3 3 0 0 0 4 0 3 3 0 0 0 4 0c.293.26.632.464 1 .6zm2-11a1 1 0 1 1-2 0V7h2zM4.3 3H20a1 1 0 1 0 0-2H4.3a1 1 0 1 0 0 2z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <input id="name" class="form-input pl-10 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="email" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                                {{ __("Email address") }} <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <div class="relative rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                    </div>
                                    <input id="email" type="email" class="form-input block w-full pl-10 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                </div>
                            </div>
                        </div>

                        <div
                           x-data
                           wire:ignore
                           x-init="
                                phoneNumber = document.querySelector('#phone_number');
                                iti = intlTelInput(document.querySelector('#phone_number'), {
                                    nationalMode: true,
                                    initialCountry: 'auto',
                                    geoIpLookup: function(success, failure) {
                                        $.get('https://ipinfo.io', function() {}, 'jsonp').always(function(resp) {
                                            var countryCode = (resp && resp.country) ? resp.country : '';
                                            success(countryCode);
                                        });
                                    },
                                    utilsScript: 'https://unpkg.com/intl-tel-input@17.0.3/build/js/utils.js'
                                });
                                var handleChange = () => {
                                    if (iti.isValidNumber()) {
                                        phoneNumber.value = iti.getNumber();
                                    }
                                  };
                                phoneNumber.addEventListener('change', handleChange);
                                phoneNumber.addEventListener('keyup', handleChange);
                           "
                           class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5"
                        >
                            <label for="phone_number" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                                {{ __("Phone number") }} <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <div class="relative rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                                    <input id="phone_number" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off">
                                    @error('phone_number')
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            @error('phone_number')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="photo" class="block text-sm leading-5 font-medium text-gray-700">
                                Photo
                            </label>
                            <div class="mt-2 sm:mt-0 sm:col-span-2">
                                <div class="flex items-center">
                                    <span class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="photograph w-8 h-8 text-gray-300">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </span>
                                    <span class="ml-5 rounded-md shadow-sm">
                                        <button type="button" class="py-2 px-3 border border-gray-300 rounded-md text-sm leading-4 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                                            {{ __("Change") }}
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="description" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                                {{ __("About") }}
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <div class="flex rounded-md shadow-sm sm:max-w-lg overflow-x-auto lg:w-full lg:overflow-visible">
                                    <x:input.rich-text wire:model.lazy="description" id="description" />
                                </div>
                                <p class="mt-2 text-sm text-gray-500">{{ __("You can view this information on the About page on your website.") }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
