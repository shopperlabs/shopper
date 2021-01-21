@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Create collection'))

@section('content')

    <livewire:shopper-collections.create />

@endsection
