<div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
    <div class="lg:col-span-1">
        <div>
            <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white font-heading">
                {{ __('shopper::pages/settings.legal.privacy') }}
            </h3>
            <p class="mt-2 text-sm leading-5 text-gray-500 dark:text-gray-400">
                {{ __('shopper::pages/settings.legal.summary', ['policy' => __('shopper::pages/settings.legal.privacy')]) }}
            </p>
        </div>
    </div>

    <livewire:shopper-settings.legal.form :legal="$legal" />
</div>