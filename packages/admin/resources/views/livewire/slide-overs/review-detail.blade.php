<x-shopper::slideover-card>
    <div class="h-0 flex-1 overflow-y-auto py-4">
        <div class="px-4">
            <div class="flex items-start justify-between">
                <h2 class="font-heading text-sh-fg text-2xl font-bold">
                    {{ $review->reviewrateable->name }}
                </h2>
                <x-livewire-slide-over::close-icon />
            </div>

            <div class="mt-8">
                <x-shopper::section-heading
                    :title="__('shopper::pages/products.reviews.review')"
                    :description="__('shopper::pages/products.reviews.subtitle')"
                />
                <div class="border-sh-border mt-6 border-t">
                    <dl class="divide-sh-border divide-y">
                        <div class="space-y-1 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sh-fg-muted text-sm font-medium">
                                {{ __('shopper::pages/products.single') }}
                            </dt>
                            <dd class="text-sh-fg flex flex-col text-sm sm:col-span-2 sm:mt-0">
                                <span class="grow">
                                    {{ $review->reviewrateable->name }}
                                </span>
                                <p class="text-sh-fg-muted mt-1 flex items-center space-x-4 text-sm">
                                    @if ($review->reviewrateable->sku)
                                        <span>
                                            {{ $review->reviewrateable->sku }}
                                            -
                                        </span>
                                    @endif
                                </p>
                            </dd>
                        </div>
                        <div class="space-y-1 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sh-fg-muted text-sm font-medium">
                                {{ __('shopper::pages/products.reviews.rating') }}
                            </dt>
                            <dd class="text-sh-fg flex space-x-4 text-sm sm:col-span-2 sm:mt-0">
                                <div class="grow">
                                    <span class="flex items-center gap-1">
                                        <x-shopper::rating-stars :rating="$review->rating" />
                                    </span>
                                </div>
                            </dd>
                        </div>
                        <div class="space-y-1 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sh-fg-muted text-sm font-medium">
                                {{ __('shopper::pages/products.reviews.review_content') }}
                            </dt>
                            <dd class="text-sh-fg flex space-x-4 text-sm sm:col-span-2 sm:mt-0">
                                <div class="grow">
                                    <p class="text-sh-fg text-sm font-medium">
                                        {{ $review->title }}
                                    </p>
                                    <p class="text-sh-fg-muted mt-1 text-sm">
                                        {{ $review->content }}
                                    </p>
                                </div>
                            </dd>
                        </div>
                        <div class="space-y-1 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sh-fg-muted text-sm font-medium">
                                {{ __('shopper::pages/products.reviews.reviewer') }}
                            </dt>
                            <dd class="text-sh-fg flex space-x-4 text-sm sm:col-span-2 sm:mt-0">
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
                                            <div class="text-sh-fg text-sm font-medium">
                                                {{ $review->author->full_name }}
                                            </div>
                                            <div class="text-sh-fg-muted truncate text-sm">
                                                {{ $review->author->email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </dd>
                        </div>
                        <div class="space-y-1 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sh-fg-muted text-sm font-medium">
                                {{ __('shopper::forms.label.created_at') }}
                            </dt>
                            <dd class="text-sh-fg-muted flex text-sm sm:col-span-2 sm:mt-0">
                                <span class="grow">
                                    {{ $review->created_at->translatedFormat('j F Y') }}
                                </span>
                            </dd>
                        </div>
                        <div class="space-y-1 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sh-fg-muted text-sm font-medium">
                                {{ __('shopper::pages/products.reviews.approved_status') }}
                            </dt>
                            <dd class="text-sh-fg flex items-center text-sm sm:col-span-2 sm:mt-0">
                                <x-filament::badge :color="$review->approved ? 'success': 'warning'">
                                    {{ $review->approved ? __('shopper::pages/products.reviews.published') : __('shopper::pages/products.reviews.pending') }}
                                </x-filament::badge>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="border-sh-border flex shrink-0 flex-wrap items-center justify-end gap-2 border-t px-4 py-4">
        {{ $this->markAsSpamAction }}

        @if ($review->approved)
            {{ $this->rejectAction }}
        @else
            {{ $this->approveAction }}
        @endif
    </div>
</x-shopper::slideover-card>
