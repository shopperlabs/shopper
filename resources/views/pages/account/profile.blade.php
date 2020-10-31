@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Profile Account'))

@section('content')

    <div class="mt-4 pb-5 border-b border-gray-200 space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0">
        <h2 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">{{ __('My profile') }}</h2>
    </div>

    <livewire:shopper-account.profile />

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

    <livewire:shopper-account.password />

    @if (config('shopper.auth.2fa_enabled'))
        <div class="hidden sm:block">
            <div class="py-5">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>

        <livewire:shopper-account.two-factor />
    @endif

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

    <livewire:shopper-account.devices />

@endsection
