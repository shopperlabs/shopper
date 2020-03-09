@extends('shopper::auth.layout')
@section('title', __('Login'))

@section('content')

    <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 lg:mt-24">
        <div class="max-w-md w-full">
            <div>
                @include('shopper::auth.partials.logo')
                <h2 class="mt-6 text-center text-3xl leading-9 font-semibold text-gray-900">
                    {{ __('Login to your account') }}
                </h2>
            </div>
            <div id="login-form" data-url="{{ route('shopper.login') }}"></div>
        </div>
    </div>

@endsection
