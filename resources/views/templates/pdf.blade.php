<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>@yield('page-title')</title>
        <link href="{{ asset('css/pdf/structure.css') }}" rel="stylesheet">
        <link href="{{ asset('css/pdf/style.css') }}" rel="stylesheet">
        @stack('styles')
    </head>

    <body>
        @hasSection('header')
            <header>
                @yield('header')
            </header>
        @endif

        @hasSection('footer')
            <footer>
                @yield('footer')
            </footer>
        @endif

        <main>
            @yield('content')
        </main>
    </body>
</html>
