@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Update category'). ' '. $category->name)

@section('content')

    <livewire:shopper-categories-edit :category="$category" />

@endsection
