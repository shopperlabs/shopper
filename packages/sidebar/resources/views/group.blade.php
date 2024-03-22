<li @class([$group->getClass()])>
    @if($group->shouldShowHeading())
        <h5 @class([$group->getHeadingClass()])>{{ $group->getName() }}</h5>
    @endif

    <ul role="list" @class([$group->getGroupItemsClass()])>
        @foreach($items as $item)
            {!! $item !!}
        @endforeach
    </ul>
</li>
