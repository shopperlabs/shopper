@extends('shopper::auth.layout')
@section('title', __('Login'))

@section('content')

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
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
                <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
                    {{ __("Welcome Back !") }}
                </h2>
                <p class="mt-2 text-center text-sm leading-5 text-gray-600 max-w">
                    Or
                    <a href="{{ url('/') }}" class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                        {{ __("Return to the landing page") }}
                    </a>
                </p>
            </div>
            <form class="mt-8" action="{{ route('shopper.login') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm">
                    <div>
                        <input aria-label="Email address" name="email" type="email" autocomplete="off" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-600 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="{{ __("Email address") }}">
                    </div>
                    <div class="-mt-px">
                        <input aria-label="Password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-600 rounded-b-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="{{ __("Password") }}">
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="form-checkbox h-4 w-4 text-brand-600 transition duration-150 ease-in-out">
                        <label for="remember_me" class="ml-2 block text-sm leading-5 text-gray-500 cursor-pointer">
                            {{ __("Remember me") }}
                        </label>
                    </div>

                    <div class="text-sm leading-5">
                        <a href="{{ route('shopper.password.request') }}" class="font-medium text-gray-500 hover:text-gray-600 focus:outline-none focus:underline transition ease-in-out duration-150">
                            {{ __("Forgot your password?") }}
                        </a>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="relative block w-full py-2 px-3 border border-transparent rounded-md text-white font-semibold bg-blue-600 hover:bg-blue-700 focus:bg-blue-800 focus:outline-none focus:shadow-outline-blue sm:text-sm sm:leading-5 transition duration-150 ease-in-out">
                        <span class="absolute left-0 inset-y pl-3">
                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        {{ __("Log in") }}
                    </button>
                </div>
            </form>
            <p class="mt-8 text-gray-500 font-medium text-sm text-center">Copyright &copy; {{ date('Y') }} &mdash; Shopper Labs.</p>
        </div>
    </div>

{{--    <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 lg:mt-24">--}}
{{--        <div class="max-w-md w-full">--}}
{{--            <div>--}}
{{--                @include('shopper::auth.partials.logo')--}}
{{--                <h2 class="mt-6 text-center text-3xl leading-9 font-semibold text-gray-500">--}}
{{--                    {{ __('Login to your account') }}--}}
{{--                </h2>--}}
{{--            </div>--}}
{{--            <div id="login-form" data-url="{{ route('shopper.login') }}"></div>--}}
{{--        </div>--}}
{{--    </div>--}}

@endsection
