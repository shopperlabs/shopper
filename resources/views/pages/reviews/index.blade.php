@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Products Reviews'))

@section('content')

    <livewire:shopper-reviews-browse />

@endsection
