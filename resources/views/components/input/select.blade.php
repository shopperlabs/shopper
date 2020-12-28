@props([
    'items' => false,
    'key' => 'id',
    'value' => 'name'
])

<select {!! $attributes->merge(['class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5']) !!}>
    @if($items)
        @foreach($items as $item)
            <option value="{{ $item->{$key} }}">{{ $item->{$value} }}</option>
        @endforeach
    @else
        {{ $slot }}
    @endif
</select>
