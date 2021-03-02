@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Orders'))

@section('content')

    <livewire:shopper-orders.browse />

@endsection
