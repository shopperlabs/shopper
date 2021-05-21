@extends('shopper::layouts.default')
@section('title', __('Variants ~ :name', ['name' => $variant->name]))

@section('content')

    <livewire:shopper-products.variant :product="$product" :variant="$variant" :currency="shopper_currency()" />

@endsection
