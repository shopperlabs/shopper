@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Products'))

@section('content')

    <x:breadcrumb>
        <span class="text-primary-text">{{ __('Products') }}</span>
    </x:breadcrumb>


@endsection
