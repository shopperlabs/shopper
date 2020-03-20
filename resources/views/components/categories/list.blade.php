<div>

    @if(count($categories) === 0)
        <div class="mt-6 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-primary-text sm:text-3xl sm:leading-9 sm:truncate pb-4 border-b border-gray-200">{{ __('Categories') }}</h2>
            </div>
        </div>
        <div class="empty-categories relative w-full flex flex-col md:flex-row items-center justify-end relative py-12 md:py-24">
            <img src="{{ asset('shopper/images/empty/categories.svg') }}" class="w-full object-cover relative flex lg:absolute lg:top-0" alt="Empty state">
            <div class="w-full lg:w-1/2 relative z-90">
                <div class="w-full pl-0 lg:pl-20 lg:pt-20 xl:pt-24">
                    <h3 class="text-primary-text font-medium text-xl mb-4">Organize your products into categories</h3>
                    <p class="text-gray-500 text-lg mb-3">Create categories to help your customers find products.</p>
                    <a href="{{ route('shopper.categories.create') }}" class="btn btn-primary inline-flex px-6">Create categories</a>
                </div>
            </div>
        </div>
    @endif

</div>
