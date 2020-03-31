@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Brands'))

@section('content')

    <x:breadcrumb>
        <span class="text-primary-text">{{ __('Brands') }}</span>
    </x:breadcrumb>

    <livewire:brand-list />

@endsection
