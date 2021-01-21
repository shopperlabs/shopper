@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Update collection :name', ['name' => $collection->name]))

@section('content')

    <livewire:shopper-collections.edit :collection="$collection" />

@endsection
