@extends('shopper::auth.layout')
@section('title', __('Forgot Password'))

@section('content')

    <div class="flex justify-between items-center">
        <a href="{{ route('shopper.login-view') }}" class="flex items-center">
            <svg class="h-6 w-6 mr-2 text-gray-700" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.41 11H21a1 1 0 110 2H5.41l5.3 5.3a1 1 0 01-1.42 1.4l-7-7a1 1 0 010-1.4l7-7a1 1 0 011.42 1.4L5.4 11h.01z" fill="currentColor" />
            </svg>
            <span>Back</span>
        </a>
    </div>
    <div class="w-full md:w-3/4 lg:w-125 mb-16 mx-auto">
        <h1 class="text-2xl font-medium text-gray-700 mb-4">{{ __('Forgot Password') }}</h1>
        <p class="text-sm text-gray-600 mb-6">{{ __("Enter the email address you used when creating your account and we will send you instructions to reset your password.") }}</p>
        <div class="p-8 bg-white shadow-smooth rounded-md">
            <form method="POST" action="{{ route('shopper.password.email') }}">
                @csrf
                <div class="w-full mb-3">
                    <label for="email" class="block text-sm leading-5 font-medium text-gray-700 mb-2">{{ __('E-Mail Address') }}</label>
                    <input id="email" type="email" class="form-input" name="email" required autofocus>
                </div>

                <button type="submit" class="group relative py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-brand hover:bg-brand-hover focus:outline-none focus:border-brand-hover active:bg-brand-hover transition duration-150 ease-in-out">
                    {{ __('Send instructions by email') }}
                </button>
            </form>
        </div>
    </div>

@stop
