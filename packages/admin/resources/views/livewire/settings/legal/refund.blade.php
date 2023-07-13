<div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
    <div class="lg:col-span-1">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                {{ __('shopper::pages/settings.legal.refund') }}
            </h3>
            <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                {{ __('shopper::pages/settings.legal.summary', ['policy' => __('shopper::pages/settings.legal.refund')]) }}
            </p>
        </div>
    </div>
    @include('shopper::livewire.settings.legal._form')
</div>
