@if ($brandLogo = shopper()->getBrandLogo())
    {!! $brandLogo !!}
@elseif (filled($brandPath = config('shopper.admin.brand')))
    <img {{ $attributes }} src="{{ asset($brandPath) }}" alt="{{ config('app.name') }}" />
@else
    <img {{ $attributes }} src="{{ asset('vendor/shopper/images/shopper-icon.svg') }}" alt="Shopper" />
@endif
