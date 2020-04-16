@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Categories'))

@section('content')

    <livewire:category-list />

@endsection
