@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Reviews for :product', ['product' => $review->reviewrateable->name]))

@section('content')

    <livewire:shopper-reviews.show :review="$review" />

@endsection
