@props(['address', 'isDefault' => false])

<x-shopper::card>
    <div class="space-y-2">
        <div class="flex items-start justify-between gap-2">
            <h5 class="text-sh-fg text-sm font-semibold">
                {{ $address->full_name }}
            </h5>
            @if ($isDefault)
                <x-filament::badge color="primary" size="sm" icon="untitledui-star">
                    {{ __('shopper::pages/customers.addresses.default') }}
                </x-filament::badge>
            @endif
        </div>

        <div class="text-sh-fg-secondary space-y-0.5 text-sm">
            <p>{{ $address->street_address }}</p>
            @if ($address->street_address_plus)
                <p>{{ $address->street_address_plus }}</p>
            @endif
            <p>{{ $address->postal_code }}, {{ $address->city }}</p>
            @if ($address->country)
                <p class="inline-flex items-center gap-2">
                    <img
                        src="{{ $address->country->svg_flag }}"
                        class="size-4 rounded-full object-cover object-center"
                        alt=""
                    />
                    <span>{{ $address->country->translated_name }}</span>
                </p>
            @endif
        </div>

        @if ($address->phone_number)
            <div class="text-sh-fg-secondary inline-flex items-center gap-2 text-sm">
                <x-untitledui-phone class="text-sh-fg-muted size-4" aria-hidden="true" />
                <span>{{ $address->phone_number }}</span>
            </div>
        @endif
    </div>
</x-shopper::card>
