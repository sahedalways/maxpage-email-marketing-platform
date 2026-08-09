<script>
    var ECHARTS_THEME = 'dark';
</script>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('core/css/theme/default.css') }}">
<!-- Select2 -->
<link rel="stylesheet" type="text/css" href="{{ URL::asset('core/select2/css/select2.min.css') }}">
<script type="text/javascript" src="{{ URL::asset('core/select2/js/select2.min.js') }}"></script>

<!-- Validate -->
<script type="text/javascript" src="{{ URL::asset('core/validate/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/validate.js') }}"></script>

<!-- Numeric -->
<script type="text/javascript" src="{{ URL::asset('core/numeric/jquery.numeric.min.js') }}"></script>

<!-- Google icon -->
<link href="{{ URL::asset('core/css/google-font-icon.css') }}?v=2" rel="stylesheet">

<!-- Autofill -->
<link rel="stylesheet" type="text/css" href="{{ URL::asset('core/css/autofill.css') }}">
<script type="text/javascript" src="{{ URL::asset('core/js/autofill.js') }}"></script>

<!-- Custom css -->
<link rel="stylesheet" type="text/css" href="{{ URL::asset('custom.css') }}">

<script type="text/javascript" src="{{ URL::asset('core/js/functions.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('core/js/link.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/box.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/popup.js') }}"></script>
{{--<script type="text/javascript" src="{{ URL::asset('core/js/sidebar.js') }}"></script>--}}
<script type="text/javascript" src="{{ URL::asset('core/js/list.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/anotify.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/dialog.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/iframe_modal.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/search.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/image_popup.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('core/js/app.js') }}"></script>

<script>
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    });

</script>
<style>
    .template-image-box2 {
        position: relative;
    }
    .template-image-box2 img {
        width: 100%!important;
    }
    .text-ellipsis {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .fw-600 {
        font-weight: 600;
    }
    .preview_control {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        justify-content: center;
        align-items: center;
        background: rgba(0,0,0,0.3);
        -webkit-backdrop-filter: blur(5px);
        backdrop-filter: blur(5px);
        display: flex;
        opacity: 0;
        transition: all 0.2s ease-in-out;
    }
    .template-image-box:hover .preview_control, .template-image-box2:hover .preview_control {
        display: flex;
        opacity: 1;
    }
    .select-template-layout .panel-template-style {
        border-width: 1px;
        padding: 2px;
        transition: all 0.2s ease-in-out;
        color: #333;
        border-radius: 4px;
        padding-bottom: 5px;
    }
    .select-template-layout .panel-template-style:hover {
        color: #000;
    }
    .select-template-layout {
        position: relative;
        width: 100%;
    }
    .select-template-layout.selected .panel-template-style {
        border-color: #166dba;
        position: relative;
        background: #d2e3f2;
    }
    .select-template-layout.selected {
        border: solid 3px #166dba;
    }
    .select-template-layout.selected:before {
        content: 'check';
        font-family: 'Material icons outlined';
        right: 0;
        top: 0px;
        position: absolute;
        color: #fff;
        background: #166dba;
        border-radius: 0 0 0 100px;
        padding: 0px 0px 6px 11px;
        z-index: 1;
        font-size: 24px;
        width: 30px;
        height: 30px;
        line-height: 20px;
        text-indent: -3px;
    }
    .select-template-layout:hover .panel-template-placeholder, .select-template-layout.selected .panel-template-placeholder {
        box-shadow: 0 .125rem .35rem rgba(0,0,0,.1)!important;
    }
    .select-template-layout {
        display: inline-block;
        text-align: center;
        border-radius: 5px;
        padding: 4px 4px 7px 4px;
        color: #333;
    }
    .select-template-layout label, .template-list-item label {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
    }
    .select-template-layout:hover {
        background-color: #f2f2f2;
    }
    .full-iframe-popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        z-index: 90000000;
    }
    .full-iframe-popup iframe.builder {
        width: 100%;
        height: 100vh;
        border: none;
        background: #333;
    }
    body > .ui-pnotify {
        z-index: 10000000000 !important;
    }
    .overflow-hidden {
        overflow: hidden !important;
    }
    .full-iframe-popup .frame-classic-loader {
        position: absolute;
        height: 100vh;
        width: 100%;
        background: #fff;
        display: flex;
        padding: 0 50%;
        align-items: center;
    }
    .full-iframe-popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        z-index: 90000000;
    }
    .full-iframe-popup iframe.builder {
        width: 100%;
        height: 100vh;
        border: none;
        background: #333;
    }
    .mask-loading-effect {
        position: fixed;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        top: 0;
        left: 0;
        text-align: center;
        z-index: 1000000;
    }
    .mask-loading-effect .content {
        display: block;
        margin: auto;
        position: fixed;
        width: 100%;
        top: 40%;
        text-align: center;
        z-index: 100000000000;
        color: #fff;
        font-size: 20px;
        font-style: italic;
    }
</style>
