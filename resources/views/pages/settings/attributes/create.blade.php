@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Add attribute'))

@section('content')

    <livewire:shopper-settings-attributes-create />

@endsection
