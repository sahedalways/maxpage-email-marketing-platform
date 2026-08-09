@php
    $dash = route('admin.dashboard');
    $prefix = 'admin';
@endphp
<aside
    class="@if (isRTL() == true) sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-end me-4 rotate-caret @else  sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 ps ps--active-y @endif"
    id="sidenav-main" data-color="primary">

    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ $dash }}">
            <img src="{{ asset(getSiteLogo()) }}" class="navbar-brand-img" alt="main_logo">
        </a>
    </div>

    <hr class="horizontal mt-0">
    <div class="collapse navbar-collapse  w-auto h-auto h-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">

            <li class="nav-item">
                <a class="nav-link {{ $dashActive ? 'active' : '' }}" href="{{ $dash }}">
                    <div
                        class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-shop text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ 'Dashboard' }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ $activeContacts ? 'active' : '' }}" href="{{ url($prefix . '/contacts') }}">
                    <div
                        class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ 'Contacts' }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#messageHub"
                    class="nav-link {{ $messageHubActive ? 'active menu-parent-active' : '' }}"
                    aria-controls="messageHub" role="button" aria-expanded="false">
                    <div
                        class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-chat-round text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ 'Message Hub' }}</span>
                </a>

                <div class="collapse {{ $messageHubActive ? 'show' : '' }}"
                    id="messageHub">
                    <ul class="nav ms-4">

                        <li class="nav-item {{ $activeSendMessage ? 'active' : '' }}">
                            <a class="nav-link {{ $activeSendMessage ? 'active' : '' }}"
                                href="{{ url($prefix . '/messages/send') }}">
                                <span class="sidenav-mini-icon side-bar-inner"><i class="fas fa-envelope"></i></span>
                                <span
                                    class="sidenav-normal side-bar-inner">{{ 'Send Message' }}</span>
                            </a>
                        </li>

                        <li class="nav-item {{ $activeTemplates ? 'active' : '' }}">
                            <a class="nav-link {{ $activeTemplates ? 'active' : '' }}"
                                href="{{ url($prefix . '/templates') }}">
                                <span class="sidenav-mini-icon side-bar-inner"><i class="fas fa-copy"></i></span>
                                <span class="sidenav-normal side-bar-inner">{{ 'Mail Templates' }}</span>
                            </a>
                        </li>

                        <li class="nav-item {{ $activeHistory ? 'active' : '' }}">
                            <a class="nav-link {{ $activeHistory ? 'active' : '' }}"
                                href="{{ url($prefix . '/messages/history') }}">
                                <span class="sidenav-mini-icon side-bar-inner"><i class="fas fa-history"></i></span>
                                <span
                                    class="sidenav-normal side-bar-inner">{{ 'Message History' }}</span>
                            </a>
                        </li>

                        <li class="nav-item {{ $activeGateway ? 'active' : '' }}">
                            <a class="nav-link {{ $activeGateway ? 'active' : '' }}"
                                href="{{ url($prefix . '/messages/gateway') }}">
                                <span class="sidenav-mini-icon side-bar-inner"><i class="fas fa-cogs"></i></span>
                                <span
                                    class="sidenav-normal side-bar-inner">{{ 'Gateway' }}</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" wire:click.prevent="logout" href="#">
                    <div
                        class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-button-power text-secondary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ 'Logout' }}</span>
                </a>
            </li>
        </ul>
    </div>
    <hr class="horizontal dark mt-2">
</aside>
