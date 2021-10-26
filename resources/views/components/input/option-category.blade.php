<option value="{{ $category->id }}">
    {{ $name }} / {{ $category->name }}
</option>


@if($category->childs->isNotEmpty())
    @foreach($category->childs as $child)
        @include('shopper::components.input.option-category', ['name' => "{$name} / {$category->name}", 'category' => $child])
    @endforeach
@endif
