<a
    href="{{ $item->getUrl() }}"
    class="group flex items-center pl-5 pr-4 py-2 mt-1 border-l-4 border-transparent text-sm font-medium focus:outline-none transition ease-in-out duration-200 @if($item->getItemClass()){{ $item->getItemClass() }}@endif @if($active) border-blue-700 text-gray-900 bg-gray-100 focus:text-gray-800 focus:bg-gray-200 focus:border-blue-800 @else text-gray-500 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-200 focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 @endif"
    @if($item->getNewTab())target="_blank"@endif
>
    {!! $item->getIcon() !!}
    <span class="truncate">{{ $item->getName() }}</span>

    @foreach($badges as $badge)
        {!! $badge !!}
    @endforeach
</a>
