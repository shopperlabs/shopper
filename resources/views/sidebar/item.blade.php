<a
    href="{{ $item->getUrl() }}"
    class="group flex items-center px-4 py-2 text-sm leading-5 font-medium rounded-md hover:text-gray-900 focus:outline-none transition ease-in-out duration-150 @if($item->getItemClass()){{ $item->getItemClass() }}@endif @if($active) text-gray-900 bg-gray-200 focus:bg-gray-300 @else text-gray-600 hover:bg-gray-100 focus:bg-gray-100 md:hover:bg-gray-50 md:hover:shadow-smooth @endif"
    @if($item->getNewTab())target="_blank"@endif
>
    {!! $item->getIcon() !!}
    <span class="truncate">{{ $item->getName() }}</span>

    @foreach($badges as $badge)
        {!! $badge !!}
    @endforeach
</a>
