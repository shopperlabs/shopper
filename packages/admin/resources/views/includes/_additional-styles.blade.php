@if (filled(shopper()->getStyles()))
    <!-- Additional CSS -->
    @foreach (shopper()->getStyles() as $css)
        @if (\Illuminate\Support\Str::of($css)->startsWith(['http://', 'https://']))
            <link rel="stylesheet" type="text/css" href="{!! $css !!}" />
        @else
            <link rel="stylesheet" type="text/css" href="{{ asset($css) }}" />
        @endif
    @endforeach
@endif

@stack('styles')
