@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Update product'). ' '. $product->name)

@section('content')

    <livewire:shopper-products-edit :product="$product" />

@endsection
