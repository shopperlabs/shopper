@extends('shopper::layouts.default')
@section('title', __('Update brand :name', ['name' => $brand->name]))

@section('content')

    <livewire:shopper-brands.edit :brand="$brand" />

@endsection
