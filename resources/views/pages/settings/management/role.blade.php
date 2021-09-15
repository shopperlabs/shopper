@extends('shopper::layouts.default')
@section('title', __('Roles'). ' ' . $role->display_name)

@section('content')

    <livewire:shopper-settings.management.role :role="$role" />

@endsection
