<x-shopper::card
    :title="__('shopper::pages/customers.profile.title')"
    :description="__('shopper::pages/customers.profile.description')"
    class="[&>div:first-of-type]:p-0"
>
    <dl class="divide-y divide-sh-border">
        <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm leading-5 font-medium text-sh-fg-muted">
                {{ __('shopper::forms.label.first_name') }}
            </dt>
            <dd class="flex space-x-4 text-sm leading-5 text-sh-fg sm:col-span-2 sm:mt-0">
                <div class="grow">
                    <span>{{ $customer->first_name }}</span>
                </div>
            </dd>
        </div>
        <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm leading-5 font-medium text-sh-fg-muted">
                {{ __('shopper::forms.label.last_name') }}
            </dt>
            <dd class="flex space-x-4 text-sm leading-5 text-sh-fg sm:col-span-2 sm:mt-0">
                <div class="grow">
                    <span>{{ $customer->last_name }}</span>
                </div>
            </dd>
        </div>
        <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm leading-5 font-medium text-sh-fg-muted">
                {{ __('shopper::forms.label.photo') }}
            </dt>
            <dd class="flex space-x-4 text-sm leading-5 text-sh-fg sm:col-span-2 sm:mt-0">
                <span class="grow">
                    <img class="size-8 rounded-full" src="{{ $customer->picture }}" alt="" />
                </span>
            </dd>
        </div>
        <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm leading-5 font-medium text-sh-fg-muted">
                {{ __('shopper::forms.label.email') }}
            </dt>
            <dd class="flex space-x-4 text-sm leading-5 text-sh-fg sm:col-span-2 sm:mt-0">
                <div class="grow">
                    <span>{{ $customer->email }}</span>
                </div>
            </dd>
        </div>
        <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm leading-5 font-medium text-sh-fg-muted">
                {{ __('shopper::forms.label.birth_date') }}
            </dt>
            <dd class="flex space-x-4 text-sm leading-5 text-sh-fg sm:col-span-2 sm:mt-0">
                <div class="grow">
                    <p class="flex items-center gap-2">
                        <x-untitledui-calendar-heart
                            class="size-5 text-sh-fg-muted"
                            stroke-width="1.5"
                            aria-hidden="true"
                        />
                        <span>
                            {{ $customer->birth_date_formatted }}
                        </span>
                    </p>
                </div>
            </dd>
        </div>

        @if ($customer->gender)
            <div class="space-y-1 p-4 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm leading-5 font-medium text-sh-fg-muted">
                    {{ __('shopper::forms.label.gender') }}
                </dt>
                <dd class="flex items-center space-x-4 text-sm leading-5 text-sh-fg sm:col-span-2 sm:mt-0">
                    <div class="grow">
                        <span class="capitalize">
                            {{ $customer->gender->getLabel() }}
                        </span>
                    </div>
                </dd>
            </div>
        @endif
    </dl>
</x-shopper::card>
