<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('page-title')</title>
    <link href="{{ asset('css/pdf/structure_invoice.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pdf/style.css') }}" rel="stylesheet">
    @stack('styles')

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        table{
            font-size: x-small;
        }
        tfoot tr td{
            font-weight: bold;
            font-size: x-small;
        }
        .gray {
            background-color: lightgray
        }
    </style>

</head>
<body>
@hasSection('header')
    <header>
        @yield('header')
    </header>
@endif

<main>
    @yield('content')
</main>

@hasSection('footer')
    <footer>
        @yield('footer')
    </footer>
@endif

</body>
</html>