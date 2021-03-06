@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Mail ~ Add Template'))

@section('content')

    <livewire:shopper-settings.mails.add-template />

@endsection
