<div class="mt-10 bg-secondary-50 p-4 sm:p-5 rounded-md border border-secondary-200 dark:bg-secondary-800 dark:border-secondary-700">
    <p class="text-sm text-secondary-500 font-medium leading-5 dark:text-secondary-400">
        {{ __('shopper::words.sponsor.description') }}
    </p>
    <div class="mt-4">
        <div class="-mx-2 -my-1.5 flex">
            <a href="https://github.com/Qoraiche/laravel-mail-editor" target="_blank" class="px-3 py-2 rounded-md text-sm leading-5 font-medium text-secondary-700 hover:bg-secondary-50 focus:outline-none focus:bg-secondary-50 dark:text-secondary-300 dark:hover:bg-secondary-700">
                {{ __('shopper::words.sponsor.repo') }}
            </a>
            <x-shopper::buttons.default link="https://www.paypal.com/paypalme/streamaps" target="_blank" class="ml-3">
                <x-heroicon-o-heart class="w-5 h-5 text-pink-500 mr-1" />
                {{ __('shopper::words.sponsor.action') }}
            </x-shopper::buttons.default>
        </div>
    </div>
</div>
