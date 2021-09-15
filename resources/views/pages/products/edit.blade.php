@extends('shopper::layouts.default')
@section('title', __('Update product :name', ['name' => $product->name]))

@section('content')

    <livewire:shopper-products.edit :product="$product" />

@endsection
