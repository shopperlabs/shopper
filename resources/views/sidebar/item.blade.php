<a
    href="{{ $item->getUrl() }}"
    class="group flex items-center pl-5 pr-4 py-2 mt-1 border-l-4 border-transparent text-sm font-medium focus:outline-none @if($item->getItemClass()){{ $item->getItemClass() }}@endif @if($active) border-blue-700 text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800 @else text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-gray-200 dark:hover:border-gray-700 @endif"
    @if($item->getNewTab())target="_blank"@endif
>
    {!! $item->getIcon() !!}
    <span class="truncate">{{ $item->getName() }}</span>

    @foreach($badges as $badge)
        {!! $badge !!}
    @endforeach
</a>
