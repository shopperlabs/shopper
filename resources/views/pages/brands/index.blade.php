@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Brands'))

@section('content')

    @component('shopper::components.breadcrumb')
        <span class="text-primary-text">{{ __('Brands') }}</span>
    @endcomponent

    @livewire('brand-list')

@endsection
