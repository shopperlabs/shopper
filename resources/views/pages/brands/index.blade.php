@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Brands'))

@section('content')

    <livewire:shopper-brands-lists />

@endsection
