@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Add inventory location'))

@section('content')

    <livewire:shopper-settings.inventories.create />

@endsection
