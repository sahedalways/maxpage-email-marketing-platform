<script type="text/javascript" src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>

<div class="saving" style="display:none; position: fixed;
    height: 100%;
    vertical-align: middle;
    text-align: center;
    padding: 100px 0;
    font-size: 20px;
    color: #fff;
    width: 100%;
    background: rgba(0,0,0,0.7);">Saving template screenshot...</div>

{!! $template->getPreviewContent() !!}
