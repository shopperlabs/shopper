<div class="lg:grid lg:grid-cols-3 lg:gap-x-12 lg:gap-y-6">
    <div class="lg:col-span-1">
        <div>
            <h3 class="font-heading text-lg font-semibold leading-6 text-gray-900 dark:text-white">
                {{ __('shopper::pages/settings.legal.terms_of_use') }}
            </h3>
            <p class="mt-2 text-sm leading-5 text-gray-500 dark:text-gray-400">
                {{ __('shopper::pages/settings.legal.summary', ['policy' => __('shopper::pages/settings.legal.terms_of_use')]) }}
            </p>
        </div>
    </div>

    <livewire:shopper-settings.legal.form :legal="$legal" />
</div>
