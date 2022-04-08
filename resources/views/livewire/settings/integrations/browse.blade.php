<div>
    <x-shopper::breadcrumb back="shopper.settings.index">
        <svg class="shrink-0 h-5 w-5 text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.settings.index') }}" class="text-secondary-500 hover:text-secondary-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Settings') }}</a>
    </x-shopper::breadcrumb>

    <div class="mt-2 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-6 text-secondary-900 sm:text-3xl sm:leading-9 sm:truncate pb-5 border-b border-secondary-200">{{ __('Integrations') }}</h2>
        </div>
    </div>

    <div class="mt-8 grid gap-4 sm:grid-cols-2 md:grid-cols-3 md:gap-6">
        <div class="rounded-md shadow overflow-hidden bg-white">
            <div class="p-4 sm:p-5 lg:pb-6 relative">
                @if($github)
                    <div class="absolute right-4 top-4 shrink-0 w-3 h-3 rounded-full bg-green-600"></div>
                @endif
                <div class="flex items-start">
                    <div class="shrink-0">
                        <svg class="w-8 h-8" fill="#212121" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-base leading-6 font-medium text-secondary-800">Github</h3>
                        <p class="text-xs leading-4 text-primary-600">{{ __("Developer service") }}</p>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-5 text-secondary-500">
                    {{ __("Integrate directly with github to create issues in relation to a problem encountered on a service and track your issue.") }}
                </p>
            </div>
            <div class="bg-secondary-50 grid grid-cols-2">
                @if($github)
                    <div class="inline-flex items-center bg-transparent justify-center w-full py-3 px-4 text-green-600 leading-6 text-base font-medium sm:text-sm sm:leading-5">
                        <svg class="w-5 h-5 mr-1.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        {{ __("Added") }}
                    </div>
                    <a href="{{ route('shopper.settings.integrations.github') }}" class="inline-flex items-center border-l border-secondary-100 text-center py-3 px-4 text-secondary-700 leading-6 text-base font-medium sm:text-sm sm:leading-5 hover:text-secondary-500 focus:outline-none focus:text-secondary-900 transition duration-150 ease-in-out">
                        {{ __("Getting Started") }}
                        <svg class="h-5 w-5 ml-2 -mr-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                @else
                    <button type="button" class="inline-flex items-center bg-transparent justify-center w-full py-3 px-4 text-primary-600 leading-6 text-base font-medium sm:text-sm sm:leading-5 hover:text-primary-500 focus:outline-none focus:text-primary-700 transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                        </svg>
                        {{ __("Enable") }}
                    </button>
                    <a href="https://docs.laravelshopper.io/docs/integrations/github" class="border-l border-secondary-100 text-center py-3 px-4 text-secondary-700 leading-6 text-base font-medium sm:text-sm sm:leading-5 hover:text-secondary-500 focus:outline-none focus:text-secondary-900 transition duration-150 ease-in-out">
                        {{ __("Learn More") }}
                    </a>
                @endif
            </div>
        </div>
        <div class="rounded-md shadow overflow-hidden bg-white">
            <div class="p-4 sm:p-5 lg:pb-6 relative">
                <div class="flex items-start">
                    <div class="shrink-0">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 32 32">
                            <path d="M11.732 0a3.199 3.199 0 1 0 .002 6.399h3.2V3.2A3.202 3.202 0 0 0 11.732 0c.002 0 .002 0 0 0zm0 8.533H3.2a3.2 3.2 0 0 0-.001 6.4h8.533a3.2 3.2 0 1 0 0-6.4z" fill="#36C5F0"/>
                            <path d="M32 11.733a3.2 3.2 0 0 0-6.401 0v3.2h3.2a3.2 3.2 0 0 0 3.201-3.2zm-8.533 0V3.199a3.2 3.2 0 0 0-6.4 0v8.534a3.2 3.2 0 1 0 6.4 0z" fill="#2EB67D"/>
                            <path d="M20.266 32a3.2 3.2 0 1 0 0-6.399h-3.2v3.2a3.202 3.202 0 0 0 3.2 3.199zm0-8.535H28.8a3.2 3.2 0 0 0 0-6.4h-8.533a3.2 3.2 0 0 0 0 6.4z" fill="#ECB22E"/>
                            <path d="M0 20.266a3.2 3.2 0 0 0 6.401 0v-3.2h-3.2A3.2 3.2 0 0 0 0 20.267zm8.533 0v8.533a3.2 3.2 0 0 0 6.4.002v-8.532a3.2 3.2 0 0 0-6.4-.003s0 .001 0 0z" fill="#E01E5A"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-base leading-6 font-medium text-secondary-800">Slack</h3>
                        <p class="text-xs leading-4 text-primary-600">{{ __("Developer service") }}</p>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-5 text-secondary-500">
                    {{ __("Add slack notifications on actions that are done on your platform so you don't miss any changes in your store.") }}
                </p>
            </div>
            <div class="bg-secondary-50 grid grid-cols-2">
                <button type="button" class="inline-flex items-center bg-transparent justify-center w-full py-3 px-4 text-primary-600 leading-6 text-base font-medium sm:text-sm sm:leading-5 hover:text-primary-500 focus:outline-none focus:text-primary-700 transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    {{ __("Enable") }}
                </button>
                <button type="button" class="border-l border-secondary-100 text-center py-3 px-4 text-secondary-700 leading-6 text-base font-medium sm:text-sm sm:leading-5 hover:text-secondary-500 focus:outline-none focus:text-secondary-900 transition duration-150 ease-in-out">
                    {{ __("Learn More") }}
                </button>
            </div>
        </div>
        <div class="rounded-md shadow overflow-hidden bg-white">
            <div class="p-4 sm:p-5 lg:pb-6 relative">
                <div class="flex items-start">
                    <div class="shrink-0">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 32 32">
                            <path d="M10.668 16A5.333 5.333 0 1 1 16 21.334 5.333 5.333 0 0 1 10.668 16zm-2.884 0a8.216 8.216 0 1 0 16.432 0 8.216 8.216 0 1 0-16.432 0zm14.837-8.542a1.92 1.92 0 1 0 1.92-1.919 1.92 1.92 0 0 0-1.92 1.92zM9.536 29.024c-1.56-.071-2.408-.331-2.971-.55a4.973 4.973 0 0 1-1.84-1.198 4.936 4.936 0 0 1-1.197-1.839c-.22-.563-.48-1.411-.55-2.971-.078-1.687-.094-2.193-.094-6.466s.017-4.778.093-6.466c.071-1.56.333-2.406.55-2.971.291-.747.638-1.28 1.197-1.84a4.931 4.931 0 0 1 1.84-1.197c.564-.22 1.412-.48 2.972-.55 1.687-.078 2.193-.094 6.464-.094 4.27 0 4.778.017 6.466.093 1.56.071 2.406.333 2.971.55.747.29 1.28.638 1.84 1.198.561.56.907 1.093 1.197 1.84.22.563.48 1.411.55 2.971.078 1.688.094 2.193.094 6.466s-.016 4.778-.093 6.466c-.071 1.56-.332 2.408-.55 2.971a4.958 4.958 0 0 1-1.197 1.84c-.56.558-1.094.905-1.84 1.196-.564.22-1.412.48-2.972.55-1.687.078-2.193.094-6.466.094s-4.778-.016-6.464-.093zM9.404.097C7.7.174 6.536.445 5.52.84a7.848 7.848 0 0 0-2.834 1.846A7.817 7.817 0 0 0 .84 5.52C.445 6.537.174 7.7.097 9.404.018 11.11 0 11.654 0 16c0 4.345.018 4.89.097 6.596.077 1.704.348 2.867.743 3.884a7.82 7.82 0 0 0 1.846 2.834A7.867 7.867 0 0 0 5.52 31.16c1.018.395 2.18.666 3.884.743 1.706.078 2.25.097 6.596.097 4.345 0 4.89-.018 6.596-.097 1.704-.077 2.867-.348 3.884-.743a7.868 7.868 0 0 0 2.834-1.846 7.837 7.837 0 0 0 1.846-2.834c.395-1.017.667-2.18.743-3.884.078-1.707.096-2.25.096-6.596 0-4.345-.018-4.89-.096-6.596-.077-1.704-.348-2.868-.743-3.884a7.868 7.868 0 0 0-1.846-2.834A7.829 7.829 0 0 0 26.481.84C25.463.445 24.3.173 22.598.097 20.892.019 20.346 0 16 0c-4.345 0-4.89.018-6.597.097z" fill="url(#paint0_radial)"/>
                            <path d="M10.668 16A5.333 5.333 0 1 1 16 21.334 5.333 5.333 0 0 1 10.668 16zm-2.884 0a8.216 8.216 0 1 0 16.432 0 8.216 8.216 0 1 0-16.432 0zm14.837-8.542a1.92 1.92 0 1 0 1.92-1.919 1.92 1.92 0 0 0-1.92 1.92zM9.536 29.024c-1.56-.071-2.408-.331-2.971-.55a4.973 4.973 0 0 1-1.84-1.198 4.936 4.936 0 0 1-1.197-1.839c-.22-.563-.48-1.411-.55-2.971-.078-1.687-.094-2.193-.094-6.466s.017-4.778.093-6.466c.071-1.56.333-2.406.55-2.971.291-.747.638-1.28 1.197-1.84a4.931 4.931 0 0 1 1.84-1.197c.564-.22 1.412-.48 2.972-.55 1.687-.078 2.193-.094 6.464-.094 4.27 0 4.778.017 6.466.093 1.56.071 2.406.333 2.971.55.747.29 1.28.638 1.84 1.198.561.56.907 1.093 1.197 1.84.22.563.48 1.411.55 2.971.078 1.688.094 2.193.094 6.466s-.016 4.778-.093 6.466c-.071 1.56-.332 2.408-.55 2.971a4.958 4.958 0 0 1-1.197 1.84c-.56.558-1.094.905-1.84 1.196-.564.22-1.412.48-2.972.55-1.687.078-2.193.094-6.466.094s-4.778-.016-6.464-.093zM9.404.097C7.7.174 6.536.445 5.52.84a7.848 7.848 0 0 0-2.834 1.846A7.817 7.817 0 0 0 .84 5.52C.445 6.537.174 7.7.097 9.404.018 11.11 0 11.654 0 16c0 4.345.018 4.89.097 6.596.077 1.704.348 2.867.743 3.884a7.82 7.82 0 0 0 1.846 2.834A7.867 7.867 0 0 0 5.52 31.16c1.018.395 2.18.666 3.884.743 1.706.078 2.25.097 6.596.097 4.345 0 4.89-.018 6.596-.097 1.704-.077 2.867-.348 3.884-.743a7.868 7.868 0 0 0 2.834-1.846 7.837 7.837 0 0 0 1.846-2.834c.395-1.017.667-2.18.743-3.884.078-1.707.096-2.25.096-6.596 0-4.345-.018-4.89-.096-6.596-.077-1.704-.348-2.868-.743-3.884a7.868 7.868 0 0 0-1.846-2.834A7.829 7.829 0 0 0 26.481.84C25.463.445 24.3.173 22.598.097 20.892.019 20.346 0 16 0c-4.345 0-4.89.018-6.597.097z" fill="url(#paint1_radial)"/>
                            <defs>
                                <radialGradient id="paint0_radial" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(4.251 32.151) scale(41.7733)">
                                    <stop offset=".09" stop-color="#FA8F21"/>
                                    <stop offset=".78" stop-color="#D82D7E"/>
                                </radialGradient>
                                <radialGradient id="paint1_radial" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(19.407 33.585) scale(32.9231)">
                                    <stop offset=".64" stop-color="#8C3AAA" stop-opacity="0"/>
                                    <stop offset="1" stop-color="#8C3AAA"/>
                                </radialGradient>
                            </defs>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-base leading-6 font-medium text-secondary-800">Instagram</h3>
                        <p class="text-xs leading-4 text-primary-600">{{ __("Product service") }}</p>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-5 text-secondary-500">
                    {{ __("Linked your instagram account to your store to be able to communicate with your page easily and add products.") }}
                </p>
            </div>
            <div class="bg-secondary-50 grid grid-cols-2">
                <button type="button" class="inline-flex items-center bg-transparent justify-center w-full py-3 px-4 text-primary-600 leading-6 text-base font-medium sm:text-sm sm:leading-5 hover:text-primary-500 focus:outline-none focus:text-primary-700 transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    {{ __("Enable") }}
                </button>
                <button type="button" class="border-l border-secondary-100 text-center py-3 px-4 text-secondary-700 leading-6 text-base font-medium sm:text-sm sm:leading-5 hover:text-secondary-500 focus:outline-none focus:text-secondary-900 transition duration-150 ease-in-out">
                    {{ __("Learn More") }}
                </button>
            </div>
        </div>
        <div class="rounded-md shadow overflow-hidden bg-white">
            <div class="p-4 sm:p-5 lg:pb-6 relative">
                <div class="flex items-start">
                    <div class="shrink-0">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 32 32">
                            <path d="M16.006 0C24.838 0 32.01 7.162 32.01 16.006c0 8.832-7.162 16.005-16.006 16.005C7.163 32.011 0 24.838 0 16.005 0 7.163 7.162 0 16.006 0z" fill="#0072F4"/>
                            <path d="M17.95 11.018h2.065V7.964h-2.428v.011c-2.933.1-3.537 1.758-3.592 3.494h-.01v1.516h-2v2.988h2v7.997h3.01v-7.997h2.47l.473-2.988h-2.944v-.923c.011-.571.396-1.044.956-1.044z" fill="#fff"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-base leading-6 font-medium text-secondary-800">Facebook</h3>
                        <p class="text-xs leading-4 text-primary-600">{{ __("Product service") }}</p>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-5 text-secondary-500">
                    {{ __("Associate your website with your facebook store and publish your products directly from your store and track your sales.") }}
                </p>
            </div>
            <div class="bg-secondary-50 grid grid-cols-2">
                <button type="button" class="inline-flex items-center bg-transparent justify-center w-full py-3 px-4 text-primary-600 leading-6 text-base font-medium sm:text-sm sm:leading-5 hover:text-primary-500 focus:outline-none focus:text-primary-700 transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    {{ __("Enable") }}
                </button>
                <button type="button" class="border-l border-secondary-100 text-center py-3 px-4 text-secondary-700 leading-6 text-base font-medium sm:text-sm sm:leading-5 hover:text-secondary-500 focus:outline-none focus:text-secondary-900 transition duration-150 ease-in-out">
                    {{ __("Learn More") }}
                </button>
            </div>
        </div>
        <div class="rounded-md shadow overflow-hidden bg-white">
            <div class="p-4 sm:p-5 lg:pb-6 relative">
                <div class="flex items-start">
                    <div class="shrink-0">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 32 32">
                            <path d="M16 32c8.837 0 16-7.163 16-16S24.837 0 16 0 0 7.163 0 16s7.163 16 16 16z" fill="#039BE5"/>
                            <path d="M7.321 15.653l15.427-5.948c.716-.258 1.341.175 1.11 1.258v-.002l-2.626 12.375c-.195.877-.716 1.09-1.445.677l-4-2.948-1.93 1.859c-.213.213-.393.393-.806.393l.284-4.07 7.413-6.698c.323-.284-.072-.444-.497-.161l-9.162 5.768-3.949-1.232c-.857-.272-.876-.857.181-1.27z" fill="#fff"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-base leading-6 font-medium text-secondary-800">Telegram</h3>
                        <p class="text-xs leading-4 text-primary-600">{{ __("Product service") }}</p>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-5 text-secondary-500">
                    {{ __("Create a channel, publish the products of your store and inform your customers about your promotions.") }}
                </p>
            </div>
            <div class="bg-secondary-50 grid grid-cols-2">
                <button type="button" class="inline-flex items-center bg-transparent justify-center w-full py-3 px-4 text-primary-600 leading-6 text-base font-medium sm:text-sm sm:leading-5 hover:text-primary-500 focus:outline-none focus:text-primary-700 transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    {{ __("Enable") }}
                </button>
                <button type="button" class="border-l border-secondary-100 text-center py-3 px-4 text-secondary-700 leading-6 text-base font-medium sm:text-sm sm:leading-5 hover:text-secondary-500 focus:outline-none focus:text-secondary-900 transition duration-150 ease-in-out">
                    {{ __("Learn More") }}
                </button>
            </div>
        </div>
        <div class="rounded-md shadow overflow-hidden bg-white">
            <div class="p-4 sm:p-5 lg:pb-6 relative">
                @if($twitter)
                    <div class="absolute right-4 top-4 shrink-0 w-3 h-3 rounded-full bg-green-600"></div>
                @endif
                <div class="flex items-start">
                    <div class="shrink-0">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 32 32">
                            <path d="M31.816 6.06a.696.696 0 0 0-.835-.147c-.287.131-.584.238-.889.322a6.26 6.26 0 0 0 1.058-2.036.696.696 0 0 0-1.072-.748 14.622 14.622 0 0 1-3.347 1.309 7.001 7.001 0 0 0-8.773-.678 6.904 6.904 0 0 0-3.005 6.38A16.473 16.473 0 0 1 3.416 4.248a.72.72 0 0 0-.59-.26.695.695 0 0 0-.557.34A6.734 6.734 0 0 0 1.5 9.553c.239.912.65 1.77 1.209 2.528a3.697 3.697 0 0 1-.707-.452.696.696 0 0 0-1.134.54 7.194 7.194 0 0 0 3.706 6.178 5.155 5.155 0 0 1-.896-.192.696.696 0 0 0-.834.951 7.592 7.592 0 0 0 5.27 4.314 12.258 12.258 0 0 1-7.34 1.51.686.686 0 0 0-.74.476.696.696 0 0 0 .32.821 20.849 20.849 0 0 0 10.281 2.922 17.659 17.659 0 0 0 9.759-2.984c5.514-3.66 8.95-10.23 8.471-16.108a12.994 12.994 0 0 0 3.025-3.152.696.696 0 0 0-.075-.845z" fill="#03A9F4" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-base leading-6 font-medium text-secondary-800">Twitter</h3>
                        <p class="text-xs leading-4 text-primary-600">{{ __("Product service") }}</p>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-5 text-secondary-500">
                    {{ __("Link your Twitter account to your store and publish your products directly to your account from your website.") }}
                </p>
            </div>
            <div class="bg-secondary-50 grid grid-cols-2">
                @if($twitter)
                    <div class="inline-flex items-center bg-transparent justify-center w-full py-3 px-4 text-green-600 leading-6 text-base font-medium sm:text-sm sm:leading-5">
                        <svg class="w-5 h-5 mr-1.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        {{ __("Added") }}
                    </div>
                    <a href="{{ route('shopper.settings.integrations.twitter') }}" class="inline-flex items-center border-l border-secondary-100 text-center py-3 px-4 text-secondary-700 leading-6 text-base font-medium sm:text-sm sm:leading-5 hover:text-secondary-500 focus:outline-none focus:text-secondary-900 transition duration-150 ease-in-out">
                        {{ __("Getting Started") }}
                        <svg class="h-5 w-5 ml-2 -mr-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                @else
                    <button data-modal="confirmationEnable('twitter', 'You are about to activate the integration with Twitter. This will create a new sales channel for posting products to your Twitter account.')" type="button" class="inline-flex items-center bg-transparent justify-center w-full py-3 px-4 text-primary-600 leading-6 text-base font-medium sm:text-sm sm:leading-5 hover:text-primary-500 focus:outline-none focus:text-primary-700 transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                        </svg>
                        {{ __("Enable") }}
                    </button>
                    <a href="https://docs.laravelshopper.io/docs/integrations/twitter" class="border-l border-secondary-100 text-center py-3 px-4 text-secondary-700 leading-6 text-base font-medium sm:text-sm sm:leading-5 hover:text-secondary-500 focus:outline-none focus:text-secondary-900 transition duration-150 ease-in-out">
                        {{ __("Learn More") }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <x-shopper::modal wire:model="confirmModalActivation" id="modal-enable" maxWidth="lg">
        <div class="px-4 pt-5 pb-4 sm:p-6">
            <div class="text-left">
                <h3 class="text-lg leading-6 font-medium text-secondary-900" id="modal-headline">
                    {{ __('Activation for :provider', ['provider' => ucfirst($currentProvider)]) }}
                </h3>
                <div class="mt-2">
                    <p class="text-sm text-secondary-500">
                        {{ $message }}
                    </p>
                </div>
            </div>
            <div class="mt-4 sm:flex">
                <x-shopper::buttons.primary wire:click="enableProvider" type="button" wire:loading.attr="disabled" class="capitalize w-full sm:w-auto sm:text-sm">
                    <x-shopper::loader wire:loading wire:target="enableProvider" class="text-white" />
                    {{ __('Enable :provider', ['provider' => $currentProvider]) }}
                </x-shopper::buttons.primary>
                <x-shopper::buttons.default wire:click="closeIntegrationModal" class="mt-3 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Cancel') }}
                </x-shopper::buttons.default>
            </div>
        </div>
    </x-shopper::modal>

</div>
