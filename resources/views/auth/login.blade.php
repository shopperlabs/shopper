@extends('shopper::auth.layout')
@section('title', __('Login'))

@section('content')

    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full px-6">
            <div class="space-y-4">
                <x-shopper-validation-errors />

                <div>
                    <x-shopper-application-logo class="mx-auto h-20 w-auto" />
                    <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900 dark:text-white">
                        {{ __('Welcome Back !') }}
                    </h2>
                    <p class="mt-2 text-center text-sm max-w">
                        {{ __('Or') }}
                        <a href="{{ url('/') }}" class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                            {{ __('Return to the landing page') }}
                        </a>
                    </p>
                </div>
            </div>

            <form class="mt-8" action="{{ route('shopper.login') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm">
                    <div>
                        <input aria-label="{{ __('Email address') }}" name="email" type="email" value="{{ old('email') }}" autocomplete="off" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 placeholder-gray-500 text-gray-900 dark:text-gray-300 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-offset-gray-900 focus:z-10 sm:text-sm" placeholder="{{ __('Email address') }}" />
                    </div>
                    <div class="-mt-px">
                        <input aria-label="{{ __('Password') }}" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 placeholder-gray-500 text-gray-900 dark:text-gray-300 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-offset-gray-900 focus:z-10 sm:text-sm" placeholder="{{ __('Password') }}">
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 dark:bg-gray-800 dark:border-gray-700 focus:ring-blue-500 border-gray-300 dark:focus:ring-offset-gray-900 rounded">
                        <label for="remember_me" class="ml-2 block text-sm leading-5 cursor-pointer">
                            {{ __('Remember me') }}
                        </label>
                    </div>

                    <div class="text-sm leading-5">
                        <a href="{{ route('shopper.password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                </div>

                <div class="mt-6">
                    <x-shopper-button type="submit" class="group relative w-full justify-center">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        {{ __('Log in') }}
                    </x-shopper-button>
                </div>
            </form>
            <p class="mt-8 font-medium text-sm text-center">Copyright &copy; {{ date('Y') }} &mdash; Shopper Labs.</p>
        </div>
    </div>

@endsection
