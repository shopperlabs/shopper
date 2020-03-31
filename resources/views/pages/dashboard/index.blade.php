@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Dashboard'))

@section('content')

    <x:breadcrumb>
        <span class="text-primary-text">{{ __('Overview') }}</span>
    </x:breadcrumb>

    <div class="mt-2 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-primary-text sm:text-3xl sm:leading-9 sm:truncate">{{ __('Overview') }}</h2>
        </div>
    </div>

@endsection
