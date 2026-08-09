@extends('layouts.popup.small')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (auth()->user()->role == 'guest')
                <div class="text-center">
                    <ul class="nav nav-tabs mc-nav campaign-template-tabs nav-underline">
                        <li class="nav-item"><a class="nav-link thumb-url-tab"
                                href="{{ route('templates.updateThumb', $template->uid) }}">
                                Upload
                            </a></li>
                        <li class="nav-item active"><a class="nav-link" href="javascript:;">
                                Url
                            </a></li>
                    </ul>
                </div>
            @elseif (auth()->user()->role == 'company')
                <div class="text-center">
                    <ul class="nav nav-tabs mc-nav campaign-template-tabs nav-underline">
                        <li class="nav-item"><a class="nav-link thumb-url-tab"
                                href="{{ route('templates.updateThumb', $template->uid) }}">
                                Upload
                            </a></li>
                        <li class="nav-item active"><a class="nav-link" href="javascript:;">
                                Url
                            </a></li>
                    </ul>
                </div>
            @else
                <div class="text-center">
                    <ul class="nav nav-tabs mc-nav campaign-template-tabs nav-underline">
                        <li class="nav-item"><a class="nav-link thumb-url-tab"
                                href="{{ route('templates.updateThumb', $template->uid) }}">
                                Upload
                            </a></li>
                        <li class="nav-item active"><a class="nav-link" href="javascript:;">
                                Url
                            </a></li>
                    </ul>
                </div>
            @endif

            <h2 class="mt-0 mb-4">Select thumbnail url</h2>
            <p>Enter image url below which will be used as template thumbnail</p>

            @if (auth()->user()->role == 'guest')
                <form enctype="multipart/form-data" action="{{ route('templates.updateThumbUrl', $template->uid) }}"
                    method="POST" class="template_upload_form form-validate-jquery">
                    {{ csrf_field() }}

                    @include('helpers.form_control', [
                        'required' => true,
                        'type' => 'text',
                        'label' => 'Choose a file to upload',
                        'name' => 'url',
                    ])

                    <div class="mt-20">
                        <button class="btn btn-primary bg-grey-600 me-1">Save</button>
                    </div>

                </form>
            @elseif (auth()->user()->role == 'company')
                <form enctype="multipart/form-data" action="{{ route('templates.updateThumbUrl', $template->uid) }}"
                    method="POST" class="template_upload_form form-validate-jquery">
                    {{ csrf_field() }}

                    @include('helpers.form_control', [
                        'required' => true,
                        'type' => 'text',
                        'label' => 'Choose a file to upload',
                        'name' => 'url',
                    ])

                    <div class="mt-20">
                        <button class="btn btn-primary bg-grey-600 me-1">Save</button>
                    </div>

                </form>
            @else
                <form enctype="multipart/form-data" action="{{ route('templates.updateThumbUrl', $template->uid) }}"
                    method="POST" class="template_upload_form form-validate-jquery">
                    {{ csrf_field() }}

                    @include('helpers.form_control', [
                        'required' => true,
                        'type' => 'text',
                        'label' => 'Choose a file to upload',
                        'name' => 'url',
                    ])

                    <div class="mt-20">
                        <button class="btn btn-primary bg-grey-600 me-1">Save</button>
                    </div>

                </form>
            @endif

        </div>
    </div>

    <script>
        $('.thumb-url-tab').click(function(e) {
            e.preventDefault();

            var url = $(this).attr("href");

            thumbPopup.load(url);
        });

        $('.template_upload_form').submit(function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var formData = new FormData($(this)[0]);

            addMaskLoading();

            //
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                globalError: false,
                statusCode: {
                    // validate error
                    400: function(res) {
                        thumbPopup.loadHtml(res.responseText);

                        // remove masking
                        removeMaskLoading();
                    }
                },
                success: function(response) {
                    removeMaskLoading();

                    // notify
                    // notify(response.status, 'Success', response.message);

                    thumbPopup.hide();

                    TemplatesIndex.getList().load();
                }
            });
        });
    </script>
@endsection
