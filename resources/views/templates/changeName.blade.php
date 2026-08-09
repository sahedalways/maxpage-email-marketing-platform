@extends('layouts.popup.small')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h5 class="mt-0 mb-4">Change name</h5>
            <p class="mb-1">Enter your new template name below</p>

            @if (auth()->user()->role == 'guest')
                <form id="changeNameForm"
                    action="{{ route('templates.changeName', [
                        'uid' => $template->uid,
                    ]) }}"
                    method="POST">
                    {{ csrf_field() }}

                    @include('helpers.form_control', [
                        'type' => 'text',
                        'label' => '',
                        'name' => 'name',
                        'value' => request()->has('name') ? request()->name : $template->name,
                    ])

                    <div class="mt-20">
                        <button class="btn btn-primary bg-grey-600 me-1">Save</button>
                    </div>

                </form>
            @elseif (auth()->user()->role == 'company')
                <form id="changeNameForm"
                    action="{{ route('templates.changeName', [
                        'uid' => $template->uid,
                    ]) }}"
                    method="POST">
                    {{ csrf_field() }}

                    @include('helpers.form_control', [
                        'type' => 'text',
                        'label' => '',
                        'name' => 'name',
                        'value' => request()->has('name') ? request()->name : $template->name,
                    ])

                    <div class="mt-20">
                        <button class="btn btn-primary bg-grey-600 me-1">Save</button>
                    </div>

                </form>
            @else
                <form id="changeNameForm"
                    action="{{ route('templates.changeName', [
                        'uid' => $template->uid,
                    ]) }}"
                    method="POST">
                    {{ csrf_field() }}

                    @include('helpers.form_control', [
                        'type' => 'text',
                        'label' => '',
                        'name' => 'name',
                        'value' => request()->has('name') ? request()->name : $template->name,
                    ])

                    <div class="mt-20">
                        <button class="btn btn-primary bg-grey-600 me-1">Save</button>
                    </div>

                </form>
            @endif

        </div>
    </div>

    <script>
        $(function() {
            $('#changeNameForm').submit(function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var data = $(this).serialize();

                addMaskLoading();

                //
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    globalError: false
                }).done(function(response) {
                    removeMaskLoading();

                    TemplatesList.getChangeNamePopup().hide();
                    TemplatesIndex.getList().load();
                }).fail(function(response) {
                    removeMaskLoading();
                    TemplatesList.getChangeNamePopup().loadHtml(response.responseText);
                });
            });
        });
    </script>
@endsection
