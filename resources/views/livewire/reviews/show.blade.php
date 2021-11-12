<div>
    <x-shopper-breadcrumb back="shopper.reviews.index">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.reviews.index')" title="Products reviews" />
    </x-shopper-breadcrumb>

    <x-shopper-heading class="mt-3">
        <x-slot name="title">
            {{ $review->reviewrateable->name }}
        </x-slot>
    </x-shopper-heading>

    <div class="mt-6 bg-white shadow rounded-lg overflow-hidden p-4 sm:p-5 dark:bg-secondary-800">
        <div class="flex items-center justify-between">
            <div class="space-y-">
                <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">
                    {{ __('Product Review') }}
                </h3>
                <p class="max-w-2xl text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('Review for this product.') }}
                </p>
            </div>
            <div class="ml-4">
                <x-shopper-delete-action
                    :title="$review->title ?? __('review')"
                    action="review"
                    message="Are you sure you want to delete this review? This review will can't be recover no more."
                    wire:click="remove"
                    wire:target="remove"
                />
            </div>
        </div>
        <div class="mt-6 border-t border-secondary-200 dark:border-secondary-700">
            <dl class="divide-y divide-secondary-200 dark:divide-secondary-700">
                <div class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('Product') }}
                    </dt>
                    <dd class="text-sm flex flex-col leading-5 text-secondary-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <span class="flex-grow">{{ $review->reviewrateable->name }}</span>
                        <p class="mt-1 flex items-center space-x-4 text-secondary-500 leading-5 text-sm dark:text-secondary-400">
                            @if($review->reviewrateable->sku)
                                <span>{{ $review->reviewrateable->sku }}</span> -
                            @endif
                            <span class="text-primary-500 border-b border-dashed border-primary-500 pb-0 5">
                                {{ $review->reviewrateable->formatted_price ?? __('Price N/A') }}
                            </span>
                        </p>
                    </dd>
                </div>
                <div class="py-4 space-y-1 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('Rating') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-secondary-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <div class="flex-grow">
                            <span class="flex items-center">
                                <svg fill="{{ $review->rating >= 1 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                </svg>
                                <svg fill="{{ $review->rating >= 2 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                </svg>
                                <svg fill="{{ $review->rating >= 3 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                </svg>
                                <svg fill="{{ $review->rating >= 4 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                </svg>
                                <svg fill="{{ $review->rating === 5 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                </svg>
                            </span>
                        </div>
                    </dd>
                </div>
                <div class="py-4 space-y-1 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('Content') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-secondary-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <div class="flex-grow">
                            <p class="text-sm text-secondary-900 font-medium leading-5 dark:text-white">{{ $review->title }}</p>
                            <p class="mt-1 text-sm text-secondary-500 leading-5 dark:text-secondary-400">{{ $review->content }}</p>
                        </div>
                    </dd>
                </div>
                <div class="py-4 space-y-1 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('Reviewer') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-secondary-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <div class="flex-grow">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <img class="h-8 w-8 rounded-full" src="{{ $review->author->picture }}" alt="">
                                </div>
                                <div class="ml-4 truncate">
                                    <div class="text-sm leading-5 font-medium text-secondary-900 dark:text-white">{{ $review->author->full_name }}</div>
                                    <div class="text-xs leading-4 text-secondary-500 truncate dark:text-secondary-400">{{ $review->author->email }}</div>
                                </div>
                            </div>
                        </div>
                    </dd>
                </div>
                <div class="py-4 space-y-1 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('Created At') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-secondary-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <span class="flex-grow">
                          {{ $review->created_at->formatLocalized('%d %B %Y') }}
                        </span>
                    </dd>
                </div>
                <div class="py-4 space-y-1 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('Approved status') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-secondary-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <span class="flex-grow">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 {{ $review->approved ? 'bg-green-100 text-green-800': 'bg-orange-100 text-orange-800' }}">
                                {{ $review->approved ? __('Published') : __('Pending') }}
                            </span>
                        </span>
                        <span class="flex-shrink-0">
                            <span role="checkbox" tabindex="0" x-on:click="on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="true" x-data="{ on: @entangle('approved') }" x-bind:class="{ 'bg-secondary-200 dark:bg-secondary-700': !on, 'bg-primary-600': on }" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline sm:ml-auto bg-primary-600">
                                <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200 translate-x-5"></span>
                            </span>
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
