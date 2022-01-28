<div class="px-4 pt-5 pb-4 sm:p-6">
    <div>
        <h3 class="text-xl leading-6 font-medium text-secondary-900 dark:text-white">
            🛠 <span class="ml-4">{{ __('We are currently building this feature.') }}</span>
        </h3>
        <p class="mt-1 max-w-4xl text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ __('To stay informed on the progress of the project and be informed when this feature is finished, subscribe to our newsletter.') }}</p>
    </div>
    <div class="mt-5">
        <p class="flex items-center text-base font-medium text-secondary-900 dark:text-white">
            <x-heroicon-o-inbox-in class="h-5 w-5 mr-2" />
            {{ __("Sign up to get notified when it’s ready.") }}
        </p>
        <form action="https://laravelshopper.us2.list-manage.com/subscribe/post?u=d9bb29721fb442284ca02e956&amp;id=6800e9bbef" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" target="_blank" novalidate method="POST" class="mt-3 flex items-center">
            <div class="relative grow focus-within:z-10">
                <input type="hidden" name="b_d9bb29721fb442284ca02e956_6800e9bbef" tabindex="-1" value="">
                <x-shopper-input.text aria-label="{{ __('Email address') }}" type="email" value="" name="EMAIL" id="mce-EMAIL" class="rounded-l-md rounded-r-none" required placeholder="{{ __('Enter your email') }}" />
            </div>
            <x-shopper-button class="-ml-px relative rounded-l-none rounded-r-md" type="submit">
                {{ __('Subscribe') }}
            </x-shopper-button>
        </form>
    </div>
</div>
