@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Variants ~ :name', ['name' => $variant->name]))

@section('content')

    <livewire:shopper-products.edit-variant :product="$product" :variant="$variant" />

@endsection
