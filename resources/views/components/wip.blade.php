<div x-cloak x-show="modalDemo" class="fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div
            x-cloak
            x-show="modalDemo"
            x-description="Background overlay, show/hide based on modal state."
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity"
        >
            <div class="absolute z-50 inset-0 bg-gray-800 opacity-75"></div>
        </div>
        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
        <div
            x-cloak
            x-show="modalDemo"
            x-description="Modal panel, show/hide based on modal state."
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-headline"
        >
            <div class="divide-y divide-gray-200">
                <div class="px-4 pt-5 pb-4 sm:p-6">
                    <div>
                        <h3 class="text-xl leading-6 font-medium text-gray-900">
                            🛠 <span class="ml-4">{{ __("We are currently building this feature.") }}</span>
                        </h3>
                        <p class="mt-1 max-w-4xl text-sm leading-5 text-gray-500">{{ __("To stay informed on the progress of the project and be informed when this feature is finished, subscribe to our newsletter.") }}</p>
                    </div>
                    <div class="mt-5">
                        <p class="flex items-center text-base font-medium text-gray-900">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                            </svg>
                            {{ __("Sign up to get notified when it’s ready.") }}
                        </p>
                        <form action="https://laravelshopper.us2.list-manage.com/subscribe/post?u=d9bb29721fb442284ca02e956&amp;id=6800e9bbef" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" target="_blank" novalidate method="POST" class="mt-3 flex items-center">
                            <div class="relative flex-grow focus-within:z-10">
                                <input type="hidden" name="b_d9bb29721fb442284ca02e956_6800e9bbef" tabindex="-1" value="">
                                <input aria-label="{{ __("Email address") }}" type="email" value="" name="EMAIL" id="mce-EMAIL" required class="form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150" placeholder="{{ __("Enter your email") }}" />
                            </div>
                            <button class="-ml-px relative inline-flex items-center text-sm leading-5 font-medium rounded-r-md px-4 py-2 border border-transparent text-base leading-6 font-medium text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:bg-blue-400 transition duration-150 ease-in-out">
                                {{ __("Subscribe") }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="px-4 sm:px-6 py-5 bg-gray-50">
                <span class="flex w-full rounded-md shadow-sm">
                    <button @click="modalDemo = false" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        {{ __("Close") }}
                    </button>
                </span>
            </div>
        </div>
    </div>
</div>
