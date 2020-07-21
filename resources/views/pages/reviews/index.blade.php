@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Reviews'))

@section('content')

    <livewire:reviews-list />

@endsection
