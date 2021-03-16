@extends('shopper::layouts.default')
@section('title', __('Update category :name', ['name' => $category->name]))

@section('content')

    <livewire:shopper-categories.edit :category="$category" />

@endsection
