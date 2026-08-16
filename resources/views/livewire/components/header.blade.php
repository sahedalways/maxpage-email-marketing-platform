<div>
    <nav class="navbar navbar-main navbar-expand-lg  px-0 mx-4 shadow-none border-radius-xl z-index-sticky "
        id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <div class="d-flex w-100 align-items-center justify-content-between">
                <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none">
                    <a href="javascript:;" class="nav-link p-0">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                        </div>
                    </a>
                </div>

                <ul class="navbar-nav flex-row align-items-center justify-content-end ms-auto mb-0">
                    <li class="nav-item d-flex align-items-center me-3 me-sm-4">
                        <a class="nav-link text-white font-weight-bold px-0" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="{{ Auth::user()->role }}" data-container="body"
                            data-animation="true">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            @if (!empty(Auth::user()->role))
                                <span class="d-none d-sm-inline">({{ Auth::user()->role }})</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item d-xl-none d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line bg-white"></i>
                                <i class="sidenav-toggler-line bg-white"></i>
                                <i class="sidenav-toggler-line bg-white"></i>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
