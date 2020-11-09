@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Update customer'). ' '. $customer->name)

@section('content')

    <livewire:shopper-customers-edit :customer="$customer" />

@endsection
