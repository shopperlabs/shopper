@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Products'))

@section('content')

    <livewire:shopper-products-browse />

@endsection
