@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Update attribute :attribute', ['attribute' => $attribute->name]))

@section('content')

    <livewire:shopper-settings.attributes.edit :attribute="$attribute" />

@endsection
