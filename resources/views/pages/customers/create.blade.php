@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Create customer'))

@section('content')

    <livewire:shopper-customers.create />

@endsection
