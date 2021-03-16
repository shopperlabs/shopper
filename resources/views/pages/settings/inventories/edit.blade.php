@extends('shopper::layouts.default')
@section('title', __('Inventories') . ' ' . $inventory->name)

@section('content')

    <livewire:shopper-settings.inventories.edit :inventory="$inventory" />

@endsection
