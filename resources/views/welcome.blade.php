<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Yelou') }}</title>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Styles -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
        <title>{{ config('app.name', 'Yelou') }}</title>
    </head>
    <body>
        <div id="app">
            <app></app>
        </div>
        @routes
        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
