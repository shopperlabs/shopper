<div>
    <div>
        <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">
            {{ __('Similar Products') }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-secondary-500 dark:text-secondary-400">
            {{ __('All products that can be identified as similar or complementary to your product.') }}
        </p>
    </div>

    <section aria-labelledby="similar_products_heading">
        <div class="mt-5 bg-white dark:bg-secondary-800 p-4 sm:p-6 shadow rounded-md">
            <x-shopper::wip-placeholder />
        </div>
    </section>
</div>
