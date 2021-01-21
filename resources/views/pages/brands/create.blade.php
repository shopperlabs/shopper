@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Create brand'))

@section('content')

    <livewire:shopper-brands.create />

@endsection
