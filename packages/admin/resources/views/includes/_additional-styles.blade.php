@if(! empty(config('shopper.admin.resources.stylesheets')))
    <!-- Additional CSS -->
    @foreach(config('shopper.admin.resources.stylesheets') as $css)
        @if (starts_with($css, ['http://', 'https://']))
            <link rel="stylesheet" type="text/css" href="{!! $css !!}">
        @else
            <link rel="stylesheet" type="text/css" href="{{ asset($css) }}">
        @endif
    @endforeach
@endif

@foreach (\Shopper\Facades\Shopper::getStyles() as $name => $path)
    @if (\Illuminate\Support\Str::of($path)->startsWith(['http://', 'https://']))
        <link rel="stylesheet" href="{{ $path }}" />
    @elseif (\Illuminate\Support\Str::of($path)->startsWith('<'))
        {!! $path !!}
    @else
        <link
            rel="stylesheet"
            href="{{ route('shopper.asset', ['file' => "{$name}.css"]) }}"
        />
    @endif
@endforeach
