@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('My Profil'))

@section('content')

    @include('shopper::pages.users.partials.menu')

    @include('shopper::pages.users.partials.'. $section)

@endsection
