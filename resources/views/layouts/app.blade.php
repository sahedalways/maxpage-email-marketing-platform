<!DOCTYPE html>
<html lang="en" @if (isRTL() == true) dir="rtl" @endif>

<head>
    @livewireStyles
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ asset(getFavIcon()) }}">
    <title>
        {{ getApplicationName() }}
    </title>
    <link href="{{ asset('assets/css/poppinsfont.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.min28b5.css?v=2.2.0') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/style.css?v=12') }}" rel="stylesheet" />
    {{--    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script> --}}
    <script type="text/javascript" src="{{ URL::asset('core/js/jquery-3.6.4.min.js') }}"></script>
    <link href="{{ asset('assets/js/plugins/toastr.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

    @yield('head')
    @livewireScripts
    @livewireStyles
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body class="g-sidenav-show @if (isRTL() == true) rtl @endif">

    <div class="min-height-300 bg-primary position-absolute w-100"></div>
    @if (auth()->user()->role === 'guest')
        <div style="position: sticky; top: 0;  display: flex; justify-content: center;">
            <div class="alert alert-info text-center" style="max-width: 600px; width: 100%;">
                You are in guest mode. Changes won't be saved permanently.
            </div>
        </div>
    @endif


    @livewire('components.side-bar')
    <main class="main-content position-relative border-radius-lg ">
        @livewire('components.header')
        <div class="container-fluid py-2">
            {{ isset($slot) ? $slot : '' }}
            @yield('content')
        </div>
    </main>

    <div class="modal fade" id="globalModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-body p-4 text-center">
                    <div id="globalModalIcon"
                        class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle text-white"
                        style="width: 64px; height: 64px;">
                        <i class="fas fa-check fa-2x"></i>
                    </div>
                    <h6 id="globalModalTitle" class="fw-600 mb-1 text-dark"></h6>
                    <p id="globalModalMessage" class="text-sm text-body mb-0"></p>
                    <div id="globalModalSummary" class="mt-3 d-flex flex-wrap justify-content-center gap-2"></div>
                </div>
                <div class="modal-footer border-0 justify-content-center pt-0">
                    <button type="button" class="btn btn-primary btn-sm px-4" data-bs-dismiss="modal">{{ 'OK' }}</button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dragula/dragula.min.js') }}"></script>
    <script src="{{ asset('assets/js/argon-dashboard.min.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        "use strict";
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script>
        "use strict"
        Livewire.on('closemodal', () => {
            $('.modal').modal('hide');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('body').removeAttr('style');
        })
    </script>
    <script>
        "use strict";
        Livewire.on('reloadpage', () => {
            window.location.reload();
        })
    </script>
    <script>
        "use strict";
        window.addEventListener('showGlobalModal', event => {
            const { type = 'success', title = '', message = '', summary = [] } = event.detail;
            const styles = {
                success: { bg: '#2dce89', icon: 'fa-check' },
                error: { bg: '#f5365c', icon: 'fa-exclamation' },
                warning: { bg: '#fb6340', icon: 'fa-exclamation-triangle' },
                info: { bg: '#05ABD3', icon: 'fa-info' },
            };
            const style = styles[type] || styles.success;
            const icon = document.getElementById('globalModalIcon');
            icon.style.background = style.bg;
            icon.innerHTML = '<i class="fas ' + style.icon + ' fa-2x text-white"></i>';
            document.getElementById('globalModalTitle').textContent = title;
            document.getElementById('globalModalMessage').textContent = message;
            const summaryBox = document.getElementById('globalModalSummary');
            summaryBox.innerHTML = '';
            (summary || []).forEach(item => {
                const badge = document.createElement('span');
                badge.className = 'badge badge-sm rounded-3 px-2 py-1 bg-light text-dark border';
                badge.textContent = item;
                summaryBox.appendChild(badge);
            });
            const modalEl = document.getElementById('globalModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        });
    </script>
    <script>
        "use strict";
        window.addEventListener('alert', event => {
            toastr[event.detail.type](event.detail.message, event.detail.title ?? '');
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
            }
        });
        @if (Session::has('message'))
            toastr.info("{{ Session::get('message') }}");
        @endif
    </script>
    <script>
        "use strict";
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script>
        document.addEventListener('livewire:load', function() {
            const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

            Livewire.emit('updateTimezone', userTimezone);
        });
    </script>



    @stack('js')
    @stack('scripts')

</body>

</html>
