@extends('shopper::layouts.default')
@section('title', __('Mail ~ Create Template'))

@section('content')

    <livewire:shopper-settings.mails.create-template :skeleton="$skeleton" />

@endsection
