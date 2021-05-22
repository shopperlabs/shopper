<div>
    @if($group->shouldShowHeading())
        <h5 class="menu-heading text-xs leading-4 text-blue-800 dark:text-blue-600 uppercase tracking-wider mb-2 font-semibold ml-5">{{ $group->getName() }}</h5>
    @endif

    @foreach($items as $item)
        {!! $item !!}
    @endforeach
</div>
