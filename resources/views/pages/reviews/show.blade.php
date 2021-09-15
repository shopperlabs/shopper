@extends('shopper::layouts.default')
@section('title', __('Reviews for :product', ['product' => $review->reviewrateable->name]))

@section('content')

    <livewire:shopper-reviews.show :review="$review" />

@endsection
