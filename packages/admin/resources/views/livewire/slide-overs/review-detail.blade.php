<div class="flex h-full flex-col divide-y divide-gray-200 dark:divide-gray-700">
    <div class="h-0 flex-1 overflow-y-auto py-6">
        <div class="px-4 sm:px-6">
            <x-shopper::heading class="mt-5">
                <x-slot name="title">
                    {{ $review->reviewrateable->name }}
                </x-slot>
            </x-shopper::heading>

            <div class="mt-8">
                <div class="flex items-center justify-between">
                    <div class="space-y-">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            {{ __('shopper::pages/products.reviews.review') }}
                        </h3>
                        <p class="max-w-2xl text-base text-gray-500 dark:text-gray-400">
                            {{ __('shopper::pages/products.reviews.subtitle') }}
                        </p>
                    </div>
                </div>
                <div class="mt-6 border-t border-gray-200 dark:border-gray-700">
                    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                                {{ __('shopper::words.product') }}
                            </dt>
                            <dd class="text-sm flex flex-col leading-5 text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                                <span class="grow">{{ $review->reviewrateable->name }}</span>
                                <p class="mt-1 flex items-center space-x-4 text-gray-500 leading-5 text-sm dark:text-gray-400">
                                    @if($review->reviewrateable->sku)
                                        <span>{{ $review->reviewrateable->sku }} - </span>
                                    @endif
                                    <span class="text-primary-500 border-b border-dashed border-primary-500 pb-0">
                                    {{ $review->reviewrateable->getPriceAmount()?->formatted ?? __('N/A') }}
                                </span>
                                </p>
                            </dd>
                        </div>
                        <div class="py-4 space-y-1 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/products.reviews.rating') }}
                            </dt>
                            <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                                <div class="grow">
                                <span class="flex items-center gap-1">
                                    @foreach([1,2,3,4,5] as $star)
                                        <x-heroicon-s-star
                                            @class([
                                                'h-4 w-4 shrink-0',
                                                'text-yellow-400' => $review->rating >= $star,
                                                'text-gray-300' => $review->rating < $star,
                                            ])
                                            aria-hidden="true"
                                        />
                                    @endforeach
                                </span>
                                </div>
                            </dd>
                        </div>
                        <div class="py-4 space-y-1 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/products.reviews.review_content') }}
                            </dt>
                            <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                                <div class="grow">
                                    <p class="text-sm text-gray-900 font-medium leading-5 dark:text-white">
                                        {{ $review->title }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500 leading-5 dark:text-gray-400">
                                        {{ $review->content }}
                                    </p>
                                </div>
                            </dd>
                        </div>
                        <div class="py-4 space-y-1 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/products.reviews.reviewer') }}
                            </dt>
                            <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                                <div class="grow">
                                    <div class="flex items-center">
                                        <div class="shrink-0">
                                            <img class="h-8 w-8 rounded-full" src="{{ $review->author->picture }}" alt="">
                                        </div>
                                        <div class="ml-4 truncate">
                                            <div class="text-sm leading-5 font-medium text-gray-900 dark:text-white">
                                                {{ $review->author->full_name }}
                                            </div>
                                            <div class="text-sm leading-5 text-gray-500 truncate dark:text-gray-400">
                                                {{ $review->author->email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </dd>
                        </div>
                        <div class="py-4 space-y-1 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                                {{ __('shopper::layout.forms.label.created_at') }}
                            </dt>
                            <dd class="flex text-sm leading-5 text-gray-500 sm:mt-0 sm:col-span-2 dark:text-gray-400">
                                <span class="grow">
                                  {{ $review->created_at->formatLocalized('%d %B %Y') }}
                                </span>
                            </dd>
                        </div>
                        <div class="py-4 space-y-1 sm:grid sm:py-5 sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/products.reviews.approved_status') }}
                            </dt>
                            <dd class="flex items-center justify-between space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                                <x-filament::badge :color="$review->approved ? 'success': 'warning'">
                                    {{ $review->approved ? __('shopper::pages/products.reviews.published') : __('shopper::pages/products.reviews.pending') }}
                                </x-filament::badge>

                                {{ $this->approvedAction }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
