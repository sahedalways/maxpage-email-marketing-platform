@extends('layouts.popup.small')

@section('content')
    <div class="row">
        <div class="col-md-12">

            @if (auth()->user()->role == 'guest')
                <div class="text-center">
                    <ul class="nav nav-tabs mc-nav campaign-template-tabs nav-underline">
                        <li class="nav-item active"><a class="nav-link" href="javascript:;">
                                Upload
                            </a></li>
                        <li class="nav-item"><a class="nav-link thumb-url-tab"
                                href="{{ route('templates.updateThumbUrl', $template->uid) }}">
                                Url
                            </a></li>
                    </ul>
                </div>
            @elseif (auth()->user()->role == 'company')
                <div class="text-center">
                    <ul class="nav nav-tabs mc-nav campaign-template-tabs nav-underline">
                        <li class="nav-item active"><a class="nav-link" href="javascript:;">
                                Upload
                            </a></li>
                        <li class="nav-item"><a class="nav-link thumb-url-tab"
                                href="{{ route('templates.updateThumbUrl', $template->uid) }}">
                                Url
                            </a></li>
                    </ul>
                </div>
            @else
                <div class="text-center">
                    <ul class="nav nav-tabs mc-nav campaign-template-tabs nav-underline">
                        <li class="nav-item active"><a class="nav-link" href="javascript:;">
                                Upload
                            </a></li>
                        <li class="nav-item"><a class="nav-link thumb-url-tab"
                                href="{{ route('templates.updateThumbUrl', $template->uid) }}">
                                Url
                            </a></li>
                    </ul>
                </div>
            @endif


            <h2 class="mt-0 mb-4">Upload thumbnail</h2>
            <p>Upload image for template thumbnail when it shows in list or represent of using the template</p>
            @if (auth()->user()->role == 'guest')
                <form enctype="multipart/form-data" action="{{ route('templates.updateThumb', $template->uid) }}"
                    method="POST" class="template_upload_form form-validate-jquery">
                    {{ csrf_field() }}

                    @include('helpers.form_control', [
                        'required' => true,
                        'type' => 'file',
                        'label' => 'Choose a file to upload',
                        'name' => 'file',
                        'attributes' => [
                            'accept' => 'image/png, image/gif, image/jpeg',
                        ],
                    ])

                    <div class="mt-20">
                        <button class="btn btn-primary bg-grey-600 me-1">Upload</button>
                    </div>

                </form>
            @elseif (auth()->user()->role == 'company')
                <form enctype="multipart/form-data" action="{{ route('templates.updateThumb', $template->uid) }}"
                    method="POST" class="template_upload_form form-validate-jquery">
                    {{ csrf_field() }}

                    @include('helpers.form_control', [
                        'required' => true,
                        'type' => 'file',
                        'label' => 'Choose a file to upload',
                        'name' => 'file',
                        'attributes' => [
                            'accept' => 'image/png, image/gif, image/jpeg',
                        ],
                    ])

                    <div class="mt-20">
                        <button class="btn btn-primary bg-grey-600 me-1">Upload</button>
                    </div>

                </form>
            @else
                <form enctype="multipart/form-data" action="{{ route('templates.updateThumb', $template->uid) }}"
                    method="POST" class="template_upload_form form-validate-jquery">
                    {{ csrf_field() }}

                    @include('helpers.form_control', [
                        'required' => true,
                        'type' => 'file',
                        'label' => 'Choose a file to upload',
                        'name' => 'file',
                        'attributes' => [
                            'accept' => 'image/png, image/gif, image/jpeg',
                        ],
                    ])

                    <div class="mt-20">
                        <button class="btn btn-primary bg-grey-600 me-1">Upload</button>
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
