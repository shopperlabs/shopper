@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Categories'))

@section('content')

    @component('shopper::components.breadcrumb')
        <span class="text-primary-text">{{ __('Categories') }}</span>
    @endcomponent

    @livewire('category-list')

@endsection
