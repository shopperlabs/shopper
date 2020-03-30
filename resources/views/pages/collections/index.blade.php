@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Collections'))

@section('content')

    <x:breadcrumb>
        <span class="text-primary-text">{{ __('Collections') }}</span>
    </x:breadcrumb>

    <livewire:collection-list />

@endsection
