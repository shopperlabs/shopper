@extends('shopper::layouts.default')
@section('title', __('Detail Order ~ :number', ['number' => $order->number]))

@section('content')

    <livewire:shopper-orders.show :order="$order" />

@endsection
