@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Collections'))

@section('content')

    <livewire:shopper-collections-lists />

@endsection
