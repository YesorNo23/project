<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title', 'SoundWave')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/type.css') }}">
    <link rel="stylesheet" href="{{ asset('css/playlist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/popup.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.AppConfig = {
            storageUrl: "{{ config('app.storage_url') }}"
        };
    </script>
    <link rel="icon" type="image/x-icon" href="{{ asset('image/favicon.ico') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body>
    
    @include('user/header')

        
        @yield('content')

        @include('user/pop_up/userprofile')
        @include('user/pop_up/changepassword')
        @include('user/pop_up/musicplayer')
        @include('user/pop_up/playlist_edit')
        @include('user/pop_up/addplaylist')
        @include('user/pop_up/createplaylist')
        @include('user/pop_up/assessment')

    @include('user/footter')
    
</body>
<script src="{{ asset('js/playermodal.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
@stack('scripts')
</html>