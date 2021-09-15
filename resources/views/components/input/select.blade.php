@props([
    'items' => false,
    'key' => 'id',
    'value' => 'name'
])

<select {!! $attributes->merge(['class' => 'block w-full py-2 px-3 border border-gray-300 dark:border-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 focus:ring-opacity-50 sm:text-sm']) !!}>
    @if($items)
        @foreach($items as $item)
            <option value="{{ $item->{$key} }}">{{ $item->{$value} }}</option>
        @endforeach
    @else
        {{ $slot }}
    @endif
</select>
