@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Add administrator'))

@section('content')

    <livewire:shopper-settings.management.create-admin-user />

@endsection
