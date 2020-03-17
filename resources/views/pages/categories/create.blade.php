@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Create category'))

@section('content')

    @component('shopper::components.breadcrumb')
        <a href="{{ route('shopper.categories.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Categories') }}</a>
        <svg class="flex-shrink-0 mx-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
        </svg>
        <span class="text-primary-text">{{ __('Create Category') }}</span>
    @endcomponent



@endsection
