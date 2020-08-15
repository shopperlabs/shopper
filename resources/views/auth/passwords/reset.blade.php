@extends('shopper::auth.layout')
@section('title', __('Reset Password'))

@section('content')

    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full px-6">
            @if($errors->count() > 0)
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm leading-5 font-medium text-red-800">
                                {{ __("Your submission contains errors. Please try again") }}
                            </h3>
                            <div class="mt-2 text-sm leading-5 text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="mt-4">
                <img class="mx-auto h-12 w-auto" src="{{ asset('shopper/images/shopper.svg') }}" alt="Laravel Shopper">
                <h2 class="mt-10 text-3xl font-semibold font-heading text-center leading-9 text-gray-800">{{ __('Reset your password') }}</h2>
                <p class="mt-5 text-sm leading-5 text-center text-gray-600">
                    {{ __("Enter your email and the new password you'd like to use to access your account.") }}
                </p>
            </div>
            <form class="mt-5" action="{{ route('shopper.password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="rounded-md shadow-sm">
                    <div>
                        <input aria-label="Email address" value="{{ $email }}" name="email" type="email" autocomplete="off" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-600 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="{{ __("Email address") }}">
                    </div>
                    <div class="-mt-px">
                        <input aria-label="Password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-600 rounded-b-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="{{ __("New password") }}">
                    </div>
                </div>

                <div class="mt-5">
                    <button type="submit" class="relative block w-full py-2 px-3 border border-transparent rounded-md text-white font-semibold bg-blue-600 hover:bg-blue-700 focus:bg-blue-800 focus:outline-none focus:shadow-outline-blue sm:text-sm sm:leading-5">
                        {{ __('Update password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@stop
