@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Update brand :name', ['name' => $brand->name]))

@section('content')

    <livewire:shopper-brands.edit :brand="$brand" />

@endsection
