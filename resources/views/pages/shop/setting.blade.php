@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Shop Setting'))

@section('content')

    @component('shopper::components.breadcrumb')
        <span class="text-gray-500">{{ __('My Shop') }}</span>
    @endcomponent

    <div class="mt-2 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">{{ __('Settings') }}</h2>
        </div>
    </div>

@endsection
