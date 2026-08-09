@extends('layouts.app')
@section('head')
    @include('layouts.core.script-var')
    @include('layouts.core.partial-script')
@endsection
@section('content')
    <div>
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col">
                <h5 class="fw-500 text-white">Upload</h5>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header p-4">
                        <div class="col-md-8">
                            <p>Select a template package from your PC to upload. A template package should be a .ZIP archive
                                containing an index.html file and other assets like CSS, images... You can download and try
                                with a sample template package <a target="_blank"
                                    href="{{ url('/download/Sample-Template.zip') }}">here</a></p>

                            <div class="alert alert-info">
                                Uploaded templates may not work well with the drag & drop builder. It is always recommended
                                that you create a new template based on one of the awesome templates available, for
                                optimized compatibility and efficiency
                            </div>

                            @if (auth()->user()->role == 'guest')
                                <form enctype="multipart/form-data" action="{{ route('templates.uploadTemplate') }}"
                                    method="POST" class="ajax_upload_form form-validate-jquery">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="type" value="{{ App\Models\Template::TYPE_EMAIL }}" />

                                    @include('helpers.form_control', [
                                        'required' => true,
                                        'type' => 'text',
                                        'label' => 'Template name',
                                        'name' => 'name',
                                        'value' => old('name'),
                                        'rules' => ['name' => 'required'],
                                    ])

                                    @include('helpers.form_control', [
                                        'required' => true,
                                        'type' => 'file',
                                        'label' => 'Choose a file to upload',
                                        'name' => 'file',
                                    ])

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-secondary me-2"><i class="icon-check"></i>
                                            Upload</button>
                                        <a href="{{ route('templates.index') }}" class="btn btn-link"><i
                                                class="icon-cross2"></i> Cancel</a>
                                    </div>

                                </form>
                            @elseif (auth()->user()->role == 'company')
                                <form enctype="multipart/form-data" action="{{ route('templates.uploadTemplate') }}"
                                    method="POST" class="ajax_upload_form form-validate-jquery">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="type" value="{{ App\Models\Template::TYPE_EMAIL }}" />

                                    @include('helpers.form_control', [
                                        'required' => true,
                                        'type' => 'text',
                                        'label' => 'Template name',
                                        'name' => 'name',
                                        'value' => old('name'),
                                        'rules' => ['name' => 'required'],
                                    ])

                                    @include('helpers.form_control', [
                                        'required' => true,
                                        'type' => 'file',
                                        'label' => 'Choose a file to upload',
                                        'name' => 'file',
                                    ])

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-secondary me-2"><i class="icon-check"></i>
                                            Upload</button>
                                        <a href="{{ route('templates.index') }}" class="btn btn-link"><i
                                                class="icon-cross2"></i> Cancel</a>
                                    </div>

                                </form>
                            @else
                                <form enctype="multipart/form-data" action="{{ route('templates.uploadTemplate') }}"
                                    method="POST" class="ajax_upload_form form-validate-jquery">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="type" value="{{ App\Models\Template::TYPE_EMAIL }}" />

                                    @include('helpers.form_control', [
                                        'required' => true,
                                        'type' => 'text',
                                        'label' => 'Template name',
                                        'name' => 'name',
                                        'value' => old('name'),
                                        'rules' => ['name' => 'required'],
                                    ])

                                    @include('helpers.form_control', [
                                        'required' => true,
                                        'type' => 'file',
                                        'label' => 'Choose a file to upload',
                                        'name' => 'file',
                                    ])

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-secondary me-2"><i class="icon-check"></i>
                                            Upload</button>
                                        <a href="{{ route('templates.index') }}" class="btn btn-link"><i
                                                class="icon-cross2"></i> Cancel</a>
                                    </div>

                                </form>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
