@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Update discount code'). ' '. $discount->code)

@section('content')

    <livewire:edit-discount :discountId="$discount->id" />

@endsection
