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
