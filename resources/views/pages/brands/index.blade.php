@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Brands'))

@section('content')

    <livewire:brand-list />

@endsection
