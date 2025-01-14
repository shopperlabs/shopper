<div class="flex h-full flex-col divide-y divide-gray-200 dark:divide-white/10">
    <div class="h-0 flex-1 overflow-y-auto py-6">
        <div class="px-4 sm:px-6">
            <x-shopper::heading class="mt-5">
                <x-slot name="title">
                    {{ $review->reviewrateable->name }}
                </x-slot>
            </x-shopper::heading>

            <div class="mt-8">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <x-filament::section.heading>
                            {{ __('shopper::pages/products.reviews.review') }}
                        </x-filament::section.heading>
                        <x-filament::section.description class="max-w-2xl">
                            {{ __('shopper::pages/products.reviews.subtitle') }}
                        </x-filament::section.description>
                    </div>
                </div>
                <div class="mt-6 border-t border-gray-200 dark:border-white/10">
                    <dl class="divide-y divide-gray-200 dark:divide-white/10">
                        <div class="space-y-1 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/products.single') }}
                            </dt>
                            <dd
                                class="flex flex-col text-sm leading-5 text-gray-900 dark:text-white sm:col-span-2 sm:mt-0"
                            >
                                <span class="grow">{{ $review->reviewrateable->name }}</span>
                                <p
                                    class="mt-1 flex items-center space-x-4 text-sm leading-5 text-gray-500 dark:text-gray-400"
                                >
                                    @if ($review->reviewrateable->sku)
                                        <span>{{ $review->reviewrateable->sku }} -</span>
                                    @endif
                                </p>
                            </dd>
                        </div>
                        <div class="space-y-1 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/products.reviews.rating') }}
                            </dt>
                            <dd
                                class="flex space-x-4 text-sm leading-5 text-gray-900 dark:text-white sm:col-span-2 sm:mt-0"
                            >
                                <div class="grow">
                                    <span class="flex items-center gap-1">
                                        @foreach ([1, 2, 3, 4, 5] as $star)
                                            {{-- format-ignore-start --}}
                                            <x-heroicon-s-star
                                                @class([
                                                    'size-4 shrink-0',
                                                    'text-yellow-400' => $review->rating >= $star,
                                                    'text-gray-300' => $review->rating < $star,
                                                ])
                                                aria-hidden="true"
                                            />
                                            {{-- format-ignore-end --}}
                                        @endforeach
                                    </span>
                                </div>
                            </dd>
                        </div>
                        <div class="space-y-1 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/products.reviews.review_content') }}
                            </dt>
                            <dd
                                class="flex space-x-4 text-sm leading-5 text-gray-900 dark:text-white sm:col-span-2 sm:mt-0"
                            >
                                <div class="grow">
                                    <p class="text-sm font-medium leading-5 text-gray-900 dark:text-white">
                                        {{ $review->title }}
                                    </p>
                                    <p class="mt-1 text-sm leading-5 text-gray-500 dark:text-gray-400">
                                        {{ $review->content }}
                                    </p>
                                </div>
                            </dd>
                        </div>
                        <div class="space-y-1 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/products.reviews.reviewer') }}
                            </dt>
                            <dd
                                class="flex space-x-4 text-sm leading-5 text-gray-900 dark:text-white sm:col-span-2 sm:mt-0"
                            >
                                <div class="grow">
                                    <div class="flex items-center">
                                        <div class="shrink-0">
                                            <img
                                                class="size-8 rounded-full"
                                                src="{{ $review->author->picture }}"
                                                alt=""
                                            />
                                        </div>
                                        <div class="ml-4 truncate">
                                            <div class="text-sm font-medium leading-5 text-gray-900 dark:text-white">
                                                {{ $review->author->full_name }}
                                            </div>
                                            <div class="truncate text-sm leading-5 text-gray-500 dark:text-gray-400">
                                                {{ $review->author->email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </dd>
                        </div>
                        <div class="space-y-1 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                                {{ __('shopper::forms.label.created_at') }}
                            </dt>
                            <dd class="flex text-sm leading-5 text-gray-500 dark:text-gray-400 sm:col-span-2 sm:mt-0">
                                <span class="grow">
                                    {{ $review->created_at->translatedFormat('j F Y') }}
                                </span>
                            </dd>
                        </div>
                        <div class="space-y-1 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/products.reviews.approved_status') }}
                            </dt>
                            <dd
                                class="flex items-center justify-between space-x-4 text-sm leading-5 text-gray-900 dark:text-white sm:col-span-2 sm:mt-0"
                            >
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
