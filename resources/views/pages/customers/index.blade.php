@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Customers'))

@section('content')

    <livewire:customer-list />

@endsection
