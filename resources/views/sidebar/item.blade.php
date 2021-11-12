<a
    href="{{ $item->getUrl() }}"
    class="group flex items-center pl-5 pr-4 py-2 mt-1 border-l-4 border-transparent text-sm font-medium focus:outline-none @if($item->getItemClass()){{ $item->getItemClass() }}@endif @if($active) border-primary-700 text-secondary-900 dark:text-white bg-secondary-100 dark:bg-secondary-800 @else text-secondary-500 dark:text-secondary-400 hover:text-secondary-600 dark:hover:text-white hover:bg-secondary-50 dark:hover:bg-secondary-800 hover:border-secondary-200 dark:hover:border-secondary-700 @endif"
    @if($item->getNewTab())target="_blank"@endif
>
    {!! $item->getIcon() !!}
    <span class="truncate">{{ $item->getName() }}</span>

    @foreach($badges as $badge)
        {!! $badge !!}
    @endforeach
</a>
