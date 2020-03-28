@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Collections'))

@section('content')

    @component('shopper::components.breadcrumb')
        <span class="text-primary-text">{{ __('Collections') }}</span>
    @endcomponent

    <livewire:collection-list />

@endsection
