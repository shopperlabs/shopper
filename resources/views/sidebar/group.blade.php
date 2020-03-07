@if($group->shouldShowHeading())
    <h5 class="menu-heading text-gray-700 uppercase mb-2 font-medium ml-8 text-xs">{{ $group->getName() }}</h5>
@endif

@foreach($items as $item)
    {!! $item !!}
@endforeach
