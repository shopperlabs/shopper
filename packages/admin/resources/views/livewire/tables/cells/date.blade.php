@if($value)
    <time datetime='{{ $value->format('Y-m-d') }}' class='text-sm leading-5 capitalize text-secondary-700 dark:text-secondary-300'>{{ $value->formatLocalized('%d %B, %Y') }}</time>
@else
    <span class="inline-flex text-secondary-700 dark:text-secondary-500">&mdash;</span>
@endif
