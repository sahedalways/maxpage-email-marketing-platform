<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.core._head')

    @yield('head')
</head>

<body class="layout-dark topbar">
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark fixed-top navbar-main py-0">
        <div class="container-fluid ms-0">
            @if (auth()->user()->role == 'guest')
                <a class="navbar-brand d-flex align-items-center me-2" href="{{ route('guest.templates.index') }}">
                    <img class="logo" src="{{ asset('assets/img/logo.png') }}" alt="">
                </a>
            @elseif(auth()->user()->role == 'company')
                <a class="navbar-brand d-flex align-items-center me-2" href="{{ route('company.templates.index') }}">
                    <img class="logo" src="{{ asset('assets/img/logo.png') }}" alt="">
                </a>
            @else
                <a class="navbar-brand d-flex align-items-center me-2" href="{{ route('templates.index') }}">
                    <img class="logo" src="{{ asset('assets/img/logo.png') }}" alt="">
                </a>
            @endif

            <button class="navbar-toggler" role="button" data-bs-toggle="collapse" data-bs-target="#mainAppNav"
                aria-controls="mainAppNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainAppNav">
                <ul class="navbar-nav me-auto mb-md-0">
                    <li class="nav-item">
                        @yield('menu_title')
                    </li>
                </ul>
                <div class="navbar-right">
                    <ul class="navbar-nav me-auto mb-md-0">
                        @yield('menu_right')
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div middle-bar-control="container" class="middle-bar pt-1">
        <div class="middle-bar-head px-1">
            <button middle-bar-control="close" class="btn btn-link fs-4 middle-bar-close-button"
                style="box-shadow: -1rem 0rem 1rem rgba(0,0,0,.025)!important;"><span
                    class="material-symbols-rounded">west</span></button>
        </div>
        <div class="content">
        </div>
    </div>

    <script>
        $(function() {
            // middle bar close
            $('[middle-bar-control="close"]').on('click', function() {
                hideMiddleBar();
            });
            $(document).on('mouseup', function(e) {
                var container = $('[middle-bar-control="container"], [middle-bar-control="element"]');

                // if the target of the click isn't the container nor a descendant of the container
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    hideMiddleBar();
                }
            });
        })
    </script>

    @yield('page_header')

    <!-- main inner content -->
    @yield('content')
</body>

</html>
