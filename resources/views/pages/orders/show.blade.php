@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Detail Order ~ :number', ['number' => $order->number]))

@section('content')

    <livewire:shopper-orders.show :order="$order" />

@endsection
