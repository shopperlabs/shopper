@if ($brandLogo = shopper()->getBrandLogo())
    {!! $brandLogo !!}
@elseif (filled($brandPath = config('shopper.admin.brand')))
    <img {{ $attributes }} src="{{ asset($brandPath) }}" alt="{{ config('app.name') }}" />
@else
    <img
        {{ $attributes }}
        src="{{ asset(shopper()->prefix() . '/images/shopper-icon.svg') }}"
        alt="Laravel Shopper"
    />
@endif
