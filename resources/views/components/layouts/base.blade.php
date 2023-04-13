@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-DNS-Prefetch-Control" content="on">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta name="base-url" content="{{ config('app.url') }}">
    <meta name="dashboard-url" content="{{ config('app.url') . '/' . shopper_prefix() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('shopper::includes._favicons')

    <title>{{ $title ?? config('app.name') }} // {{ __('shopper::layout.meta_title') }}</title>

    <link rel="dns-prefetch" href="{{ config('app.url') }}">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />

    @stack('styles')

    <livewire:styles />
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/shopper.css', 'shopper') }}">

    <wireui:scripts />
    <livewire:scripts />
    <script src="{{ mix('/js/shopper.js','shopper') }}" defer></script>

    @include('shopper::includes._additional-styles')
    @stack('scripts')
</head>
<body class="bg-secondary-100 font-sans antialiased overflow-hidden dark:bg-secondary-900">

    {{ $slot }}

    <x-shopper::alert />

    @livewire('livewire-ui-modal')
    @livewire('notifications')

    @include('shopper::includes._additional-scripts')

</body>
</html>
