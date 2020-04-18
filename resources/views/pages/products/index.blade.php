@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Products'))

@section('content')

    <livewire:product-list />

@endsection
