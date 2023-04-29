@props(['time', 'format' => 'M jS, Y'])

<time datetime="{{ $time->format('Y-m-d') }}">
    {{ $time->format($format) }}
</time>
