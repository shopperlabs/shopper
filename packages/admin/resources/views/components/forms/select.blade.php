@props([
    'items' => false,
    'key' => 'id',
    'value' => 'name'
])

<select {!! $attributes->twMerge(['class' => 'block w-full py-2 px-3 border border-gray-300 dark:border-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 rounded-md focus:outline-none focus:ring focus:ring-primary-500 focus:border-primary-500 focus:ring-opacity-50 sm:text-sm']) !!}>
    @if($items)
        @foreach($items as $item)
            <option value="{{ $item->{$key} }}">{{ $item->{$value} }}</option>
        @endforeach
    @else
        {{ $slot }}
    @endif
</select>
