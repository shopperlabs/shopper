@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Categories'))

@section('content')

    <x:breadcrumb>
        <span class="text-primary-text">{{ __('Categories') }}</span>
    </x:breadcrumb>

    <livewire:category-list />

@endsection
