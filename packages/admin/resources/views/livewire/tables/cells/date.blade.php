@if($value)
    <time datetime='{{ $value->format('Y-m-d') }}' class='text-sm leading-5 capitalize text-gray-700 dark:text-gray-300'>{{ $value->formatLocalized('%d %B, %Y') }}</time>
@else
    <span class="inline-flex text-gray-700 dark:text-gray-500">&mdash;</span>
@endif
