@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Discounts'))

@section('content')

    <livewire:discounts-list />

@endsection
