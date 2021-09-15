@extends('shopper::layouts.default')
@section('title', __('Update attribute :attribute', ['attribute' => $attribute->name]))

@section('content')

    <livewire:shopper-settings.attributes.edit :attribute="$attribute" />

@endsection
