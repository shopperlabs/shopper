@if (! empty(config('shopper.admin.resources.scripts')))
    <!-- Additional Javascript -->
    @foreach (config('shopper.admin.resources.scripts') as $js)
        @if (\Illuminate\Support\Str::of($js)->startsWith(['http://', 'https://']))
            <script type="text/javascript" src="{!! $js !!}"></script>
        @else
            <script type="text/javascript" src="{{ asset($js) }}"></script>
        @endif
    @endforeach
@endif

@stack('scripts')
