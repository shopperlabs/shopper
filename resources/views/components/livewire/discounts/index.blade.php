<div>
    @if($total === 0)
        <div class="mt-6 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-600 sm:text-3xl sm:leading-9 sm:truncate pb-4 border-b border-gray-200">{{ __('Discounts') }}</h2>
            </div>
        </div>
        <div class="empty-categories w-full flex flex-col lg:flex-row items-center justify-end relative py-12 md:py-24">
            <img src="{{ asset('shopper/images/empty/discount.svg') }}" class="w-full object-cover relative flex lg:absolute lg:top-0" alt="Empty state">
            <div class="w-full lg:w-1/2 relative z-90">
                <div class="w-full pl-0 lg:pl-20 lg:pt-20 xl:pt-24">
                    <h3 class="text-gray-700 font-medium text-xl mb-2">{{ __('Create discount codes.') }}</h3>
                    <p class="text-gray-500 text-lg mb-3">{{ __('Manage discounts and promotions code for all your products and orders.') }}</p>
                    <a href="{{ route('shopper.discounts.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-600 focus:outline-none focus:border-brand-700 focus:shadow-outline-brand active:bg-brand-700 transition ease-in-out duration-150 inline-flex">{{ __('Create discount code') }}</a>
                </div>
            </div>
        </div>
    @else

    @endif
</div>
