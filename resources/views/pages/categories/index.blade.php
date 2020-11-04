@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Categories'))

@section('content')

    <livewire:shopper-categories-lists />

@endsection
