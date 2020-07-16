<a
    href="{{ $item->getUrl() }}"
    class="group flex items-center px-4 py-2 text-sm leading-5 font-medium rounded-md focus:outline-none transition ease-in-out duration-200 @if($item->getItemClass()){{ $item->getItemClass() }}@endif @if($active) text-brand-400 bg-white shadow @else text-gray-500 hover:text-brand-500 @endif"
    @if($item->getNewTab())target="_blank"@endif
>
    {!! $item->getIcon() !!}
    <span class="truncate">{{ $item->getName() }}</span>

    @foreach($badges as $badge)
        {!! $badge !!}
    @endforeach
</a>
