@if(! empty(config('shopper.admin.resources.scripts')))
    <!-- Additional Javascript -->
    @foreach(config('shopper.admin.resources.scripts') as $js)
        @if (starts_with($js, ['http://', 'https://']))
            <script type="text/javascript" src="{!! $js !!}"></script>
        @else
            <script type="text/javascript" src="{{ asset($js) }}"></script>
        @endif
    @endforeach
@endif

@foreach (\Shopper\Facades\Shopper::getScripts() as $name => $path)
    @if (\Illuminate\Support\Str::of($path)->startsWith(['http://', 'https://']))
        <script defer src="{{ $path }}"></script>
    @elseif (\Illuminate\Support\Str::of($path)->startsWith('<'))
        {!! $path !!}
    @else
        <script
            defer
            src="{{ route('shopper.asset', ['file' => "{$name}.js"]) }}"
        ></script>
    @endif
@endforeach

@stack('scripts')
