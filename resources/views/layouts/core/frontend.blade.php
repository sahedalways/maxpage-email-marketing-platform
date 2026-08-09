<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.core._head')

    @yield('head')
        <meta name="theme-color" content="#eff3f5">
    <script>
        var ECHARTS_THEME = 'dark';
    </script>

    <!-- Theme -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('core/css/theme/default.css') }}">
</head>
<body class="">

<main class="container page-container px-3">
    <!-- main inner content -->
    @yield('content')
</main>


</body>
</html>
