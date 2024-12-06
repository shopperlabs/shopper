@props([
    'items' => false,
    'key' => 'id',
    'value' => 'name',
])

<select
    {!! $attributes->twMerge(['class' => 'block w-full py-1.5 px-3 border-0 ring-1 ring-gray-300 rounded-lg bg-white dark:bg-gray-800 dark:text-gray-300 dark:ring-white/10 focus:ring-2 focus:outline-none focus:ring-primary-600 dark:focus:ring-primary-500 sm:text-sm sm:leading-6']) !!}
>
    @if ($items)
        @foreach ($items as $item)
            <option value="{{ $item->{$key} }}">{{ $item->{$value} }}</option>
        @endforeach
    @else
        {{ $slot }}
    @endif
</select>
