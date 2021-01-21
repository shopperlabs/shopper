@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Create product'))

@section('content')

    <livewire:shopper-products.create />

@endsection
