<div>
    <div class="flex-shrink-0 bg-white border-b border-blue-200">
        <div class="bg-blue-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3 flex-1 md:flex md:justify-between">
                    <p class="text-sm text-blue-700">
                        {{ __("If your project uses git don't forget to add the created files and commit them.") }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6 sm:px-8 pb-10">
        Templates

        <div class="mt-10 bg-gray-50 p-4 sm:p-5 rounded-md border border-gray-200">
            <p class="text-sm text-gray-600 font-medium leading-5">
                {{ __("Do you like this feature? It is inspired by Laravel Mail Eclipse. You can sponsor the author") }}
            </p>
            <div class="mt-4">
                <div class="-mx-2 -my-1.5 flex">
                    <a href="https://github.com/Qoraiche/laravel-mail-editor" target="_blank" class="px-3 py-2 rounded-md text-sm leading-5 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition ease-in-out duration-150">
                        {{ __('View the repo') }}
                    </a>
                    <a href="https://www.paypal.com/paypalme/streamaps" target="_blank" class="ml-3 inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 text-pink-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        {{ __('Sponsor') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
