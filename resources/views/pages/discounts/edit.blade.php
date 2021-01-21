@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Update discount code :code', ['code' => $discount->code]))

@section('content')

    <livewire:shopper-discounts.edit :discount="$discount" />

@endsection
