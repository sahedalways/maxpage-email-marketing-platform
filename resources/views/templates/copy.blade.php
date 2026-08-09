@extends('layouts.popup.small')

@section('title')
    {{ $template->name }}
@endsection

@section('content')
    <input type="text" id="userRole" value="{{ auth()->user()->role }}" hidden>
    <input type="hidden" id="copyRouteDefault" value="{{ route('templates.copy', $template->uid) }}">
    <input type="hidden" id="copyRouteGuest" value="{{ route('templates.copy', $template->uid) }}">
    <form id="copyTemplateForm" action="" method="POST" class="form-validate-jquery">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="">
        <input type="hidden" name="uids" value="">

        @foreach (request()->all() as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        @include('helpers.form_control', [
            'type' => 'text',
            'name' => 'name',
            'value' => 'Copy of' . $template->name,
            'label' => 'What would you like to name your template?',
            'rules' => ['name' => 'required'],
        ])


        <div class="text-end">
            <button type="submit" role="button" id="doCopyButton" class="btn btn-secondary me-1">Copy</button>
            <a role="button" class="btn btn-default" onclick="TemplatesList.getCopyPopup().hide()">
                close
            </a>
        </div>
    </form>

    <script>
        const userRole = document.getElementById('userRole').value;

        const copyRoute = userRole == 'guest' ?
            document.getElementById('copyRouteGuest').value :
            document.getElementById('copyRouteDefault').value;


        var TemplatesCopy = {
            action: copyRoute,
            copy: function(url, data) {
                TemplatesList.getCopyPopup().mask();
                addButtonMask($('#doCopyButton'));

                // copy
                $.ajax({
                    url: this.action,
                    type: 'POST',
                    data: data,
                    globalError: false
                }).done(function(response) {
                    TemplatesList.getCopyPopup().hide();
                    TemplatesIndex.getList().load();

                }).fail(function(jqXHR, textStatus, errorThrown) {
                    // for debugging
                    TemplatesList.getCopyPopup().loadHtml(jqXHR.responseText);
                }).always(function() {
                    TemplatesList.getCopyPopup().unmask();
                    removeButtonMask($('#doCopyButton'));
                });
            }
        }

        $(function() {
            $('#copyTemplateForm').on('submit', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var data = $(this).serialize();

                TemplatesCopy.copy(url, data);
            });
        });
    </script>
@endsection
