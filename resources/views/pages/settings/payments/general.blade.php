@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Payments Methods'))

@section('content')

    <livewire:shopper-settings-payments-general />

@endsection
