@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Discounts'))

@section('content')

    <livewire:shopper-discounts-browse />

@endsection
