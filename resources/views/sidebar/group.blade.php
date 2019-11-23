@if($group->shouldShowHeading())
    <li class="menu__section">
        <h4 class="menu__section-text">{{ $group->getName() }}</h4>
        <i class="menu__section-icon flaticon-more-v2"></i>
    </li>
@endif


@foreach($items as $item)
    {!! $item !!}
@endforeach
