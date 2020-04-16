@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Collections'))

@section('content')

    <livewire:collection-list />

@endsection
