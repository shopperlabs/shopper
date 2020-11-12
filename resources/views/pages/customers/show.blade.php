@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Customer'). ' '. $customer->name)

@section('content')

    <livewire:shopper-customers-show :customer="$customer" />

@endsection
