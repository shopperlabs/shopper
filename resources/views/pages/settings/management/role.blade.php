@extends('shopper::layouts.'. config('shopper.system.theme'))
@section('title', __('Roles'). ' ' . $role->display_name)

@section('content')

    <livewire:shopper-settings-management-role :role="$role" />

@endsection
