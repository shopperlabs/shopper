@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Manage Inventory'))

@section('content')

    <livewire:inventory-history />

@endsection
