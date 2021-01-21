@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Properties List'))

@section('content')

    <livewire:shopper-settings.attributes.browse />

@endsection
