@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Inventories') . ' ' . $inventory->name)

@section('content')

    <livewire:shopper-settings-inventories-edit :inventory="$inventory" />

@endsection
