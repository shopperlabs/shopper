@extends('shopper::auth.layout')
@section('title', __('Login'))

@section('content')

    <div class="wrapper-form">
        <div class="card-wrapper">
            <div class="brand">
                @include('shopper::auth.partials.logo')
            </div>

            <div class="card fat">
                <div class="card-body">
                    <h4 class="card-title">{{ __('Login') }}</h4>
                    <form method="POST" class="my-login-validation" novalidate action="{{ route('shopper.login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="email" class="form-control" name="email" required autofocus>
                            <div class="invalid-feedback">{{ __('Email is invalid') }}</div>
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('Password') }}
                                <a href="{{ route('shopper.password.request') }}" class="float-right">{{ __('Forgot Password') }}?</a>
                            </label>
                            <input id="password" type="password" class="form-control" name="password" required data-eye>
                            <div class="invalid-feedback">{{ __('Password is required') }}</div>
                        </div>

                        <div class="form-group">
                            <div class="custom-checkbox custom-control">
                                <input type="checkbox" name="remember" id="remember" class="custom-control-input">
                                <label for="remember" class="custom-control-label">{{ __('Remeber Me') }}</label>
                            </div>
                        </div>

                        <div class="form-group m-0">
                            <button type="submit" class="btn btn-primary btn-block" id="btn-login">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @include('shopper::auth.partials.footer')
        </div>
    </div>

@stop
