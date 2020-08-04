@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Update discount code'). ' '. $discount->code)

@section('content')

    <x:breadcrumb back="shopper.discounts.index">
        <a href="{{ route('shopper.discounts.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Discount codes') }}</a>
        <svg class="flex-shrink-0 mx-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <span class="text-gray-500">{{ __('Update discount') }}</span>
    </x:breadcrumb>

    <div class="mt-6 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-600 sm:text-3xl sm:leading-9 sm:truncate pb-4">{{ $discount->code }}</h2>
        </div>
    </div>

    <livewire:edit-discount :discountId="$discount->id" />

@endsection
