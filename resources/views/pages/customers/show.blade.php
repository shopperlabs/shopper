@extends('shopper::layouts.default')
@section('title', __('Customer :name', ['name' => $customer->name]))

@section('content')

    <livewire:shopper-customers.show :customer="$customer" />

@endsection
