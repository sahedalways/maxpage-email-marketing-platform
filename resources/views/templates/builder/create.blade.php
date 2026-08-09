@extends('layouts.app')
@section('head')
    @include('layouts.core.script-var')
    @include('layouts.core.partial-script')
    <script type="text/javascript" src="{{ asset('assets/template/js/functions.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/template/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/template/js/editor.js') }}"></script>
@endsection

@section('content')
    <div>
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col">
                <h5 class="fw-500 text-white">New Template</h5>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header p-4">
                        <div class="row">
                            <div class="col-md-6">

                                 <form action="{{ route('templates.builderCreate') }}" method="POST"
                                        class="template-form form-validate-jquery">
                                        {{ csrf_field() }}
                                        <input type="hidden" value="" name="template" />
                                        <div class="sub_section">
                                            @include('helpers.form_control', [
                                                'type' => 'text',
                                                'class' => '',
                                                'name' => 'Name',
                                                'value' => $template->name,
                                                'label' => "Enter your template's name here",
                                                'rules' => ['name' => 'required'],
                                            ])
                                        </div>
                                    </form>

                                @if (session('message'))
                                    <div class="alert alert-warning">
                                        {{ session('message') }}
                                    </div>
                                @endif

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12" style="position: relative;">
                                <div class="d-flex align-items-center mt-4 template-create-sticky">
                                    <p class="text-semibold mr-auto mb-0 mt-0">Select one from the base templates below</p>
                                    <div class="text-end d-flex align-items-center ms-auto">
                                        <div class="view-toggle d-flex ml-auto">
                                            <a href="javascript:void(0);"
                                                class="btn btn-icon btn-3 btn-white text-primary start-design mb-0">
                                                Start Design
                                            </a>
                                            {{--                                        <button class="btn btn-secondary me-2 start-design"><i class="icon-check"></i> Start Design</button> --}}
                                        </div>
                                    </div>
                                </div>

                                @foreach (App\Models\TemplateCategory::all() as $category)
                                    @if (auth()->user()->role == 'guest')
                                        @if ($category->templates()->count())
                                            <div class="subsection pb-4">
                                                <h2 class="font-weight-semibold mb-0">{{ $category->name }}</h2>
                                                <hr>

                                                <div id="gallery-{{ $category->id }}" class="pb-4">
                                                    <div class="listing-form"
                                                        data-url="{{ route('templates.builderTemplates', [
                                                            'category_uid' => $category->uid,
                                                        ]) }}"
                                                        per-page="25">
                                                        <div
                                                            class="d-flex top-list-controls top-sticky-contentx align-items-center gap-2">
                                                            <div class="filter-box d-flex align-items-center gap-2">
                                                                <span class="filter-group d-flex align-items-center gap-2">
                                                                    <span class="title text-semibold text-muted">Sort
                                                                        by</span>
                                                                    <select class="form-select" name="sort_order">
                                                                        <option value="id">Default</option>
                                                                        <option value="name">Name</option>
                                                                    </select>
                                                                    <input type="hidden" name="sort_direction"
                                                                        value="asc" />
                                                                    <button class="btn btn-xs sort-direction" rel="asc"
                                                                        data-popup="tooltip" title="Change sort direction"
                                                                        role="button">
                                                                        <span
                                                                            class="material-symbols-rounded desc">sort</span>
                                                                    </button>
                                                                </span>
                                                                <span
                                                                    class="text-nowrap d-flex align-items-center position-relative">
                                                                    <input type="text" name="keyword"
                                                                        class="form-control search"
                                                                        value="{{ request()->keyword }}"
                                                                        placeholder="Type to search" />
                                                                    <span class="material-symbols-rounded position-absolute"
                                                                        style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">search</span>
                                                                </span>
                                                            </div>
                                                        </div>



                                                        <div id="gallery-{{ $category->id }}-content"
                                                            class="pml-table-container">
                                                        </div>
                                                    </div>
                                                </div>
                                                <br style="clear:both" /><br style="clear:both" />
                                            </div>

                                            <script>
                                                $(function() {
                                                    var list = makeList({
                                                        url: '{{ route('templates.builderTemplates', [
                                                            'category_uid' => $category->uid,
                                                        ]) }}',
                                                        container: $('#gallery-{{ $category->id }}'),
                                                        content: $('#gallery-{{ $category->id }}-content')
                                                    });

                                                    list.load();
                                                });
                                            </script>
                                        @endif
                                    @elseif (auth()->user()->role == 'company')
                                        @if ($category->templates()->count())
                                            <div class="subsection pb-4">
                                                <h2 class="font-weight-semibold mb-0">{{ $category->name }}</h2>
                                                <hr>

                                                <div id="gallery-{{ $category->id }}" class="pb-4">
                                                    <div class="listing-form"
                                                        data-url="{{ route('templates.builderTemplates', [
                                                            'category_uid' => $category->uid,
                                                        ]) }}"
                                                        per-page="25">
                                                        <div
                                                            class="d-flex top-list-controls top-sticky-contentx align-items-center gap-2">
                                                            <div class="filter-box d-flex align-items-center gap-2">
                                                                <span class="filter-group d-flex align-items-center gap-2">
                                                                    <span class="title text-semibold text-muted">Sort
                                                                        by</span>
                                                                    <select class="form-select" name="sort_order">
                                                                        <option value="id">Default</option>
                                                                        <option value="name">Name</option>
                                                                    </select>
                                                                    <input type="hidden" name="sort_direction"
                                                                        value="asc" />
                                                                    <button class="btn btn-xs sort-direction" rel="asc"
                                                                        data-popup="tooltip" title="Change sort direction"
                                                                        role="button">
                                                                        <span
                                                                            class="material-symbols-rounded desc">sort</span>
                                                                    </button>
                                                                </span>
                                                                <span
                                                                    class="text-nowrap d-flex align-items-center position-relative">
                                                                    <input type="text" name="keyword"
                                                                        class="form-control search"
                                                                        value="{{ request()->keyword }}"
                                                                        placeholder="Type to search" />
                                                                    <span
                                                                        class="material-symbols-rounded position-absolute"
                                                                        style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">search</span>
                                                                </span>
                                                            </div>
                                                        </div>



                                                        <div id="gallery-{{ $category->id }}-content"
                                                            class="pml-table-container">
                                                        </div>
                                                    </div>
                                                </div>
                                                <br style="clear:both" /><br style="clear:both" />
                                            </div>

                                            <script>
                                                $(function() {
                                                    var list = makeList({
                                                        url: '{{ route('templates.builderTemplates', [
                                                            'category_uid' => $category->uid,
                                                        ]) }}',
                                                        container: $('#gallery-{{ $category->id }}'),
                                                        content: $('#gallery-{{ $category->id }}-content')
                                                    });

                                                    list.load();
                                                });
                                            </script>
                                        @endif
                                    @else
                                        @if ($category->templates()->count())
                                            <div class="subsection pb-4">
                                                <h2 class="font-weight-semibold mb-0">{{ $category->name }}</h2>
                                                <hr>

                                                <div id="gallery-{{ $category->id }}" class="pb-4">
                                                    <div class="listing-form"
                                                        data-url="{{ route('templates.builderTemplates', [
                                                            'category_uid' => $category->uid,
                                                        ]) }}"
                                                        per-page="25">
                                                        <div
                                                            class="d-flex top-list-controls top-sticky-contentx align-items-center gap-2">
                                                            <div class="filter-box d-flex align-items-center gap-2">
                                                                <span class="filter-group d-flex align-items-center gap-2">
                                                                    <span class="title text-semibold text-muted">Sort
                                                                        by</span>
                                                                    <select class="form-select" name="sort_order">
                                                                        <option value="id">Default</option>
                                                                        <option value="name">Name</option>
                                                                    </select>
                                                                    <input type="hidden" name="sort_direction"
                                                                        value="asc" />
                                                                    <button class="btn btn-xs sort-direction"
                                                                        rel="asc" data-popup="tooltip"
                                                                        title="Change sort direction" role="button">
                                                                        <span
                                                                            class="material-symbols-rounded desc">sort</span>
                                                                    </button>
                                                                </span>
                                                                <span
                                                                    class="text-nowrap d-flex align-items-center position-relative">
                                                                    <input type="text" name="keyword"
                                                                        class="form-control search"
                                                                        value="{{ request()->keyword }}"
                                                                        placeholder="Type to search" />
                                                                    <span
                                                                        class="material-symbols-rounded position-absolute"
                                                                        style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">search</span>
                                                                </span>
                                                            </div>
                                                        </div>



                                                        <div id="gallery-{{ $category->id }}-content"
                                                            class="pml-table-container">
                                                        </div>
                                                    </div>
                                                </div>
                                                <br style="clear:both" /><br style="clear:both" />
                                            </div>

                                            <script>
                                                $(function() {
                                                    var list = makeList({
                                                        url: '{{ route('templates.builderTemplates', [
                                                            'category_uid' => $category->uid,
                                                        ]) }}',
                                                        container: $('#gallery-{{ $category->id }}'),
                                                        content: $('#gallery-{{ $category->id }}-content')
                                                    });

                                                    list.load();
                                                });
                                            </script>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $(document).on('click', '.select-template-layout', function() {
                var template = $(this).attr('data-template');

                // unselect all layouts
                $('.select-template-layout').removeClass('selected');

                // select this
                $(this).addClass('selected');

                // unselect all
                $('[name=template]').val('');

                // update template value
                if (typeof(template) !== 'undefined') {
                    $('[name=template]').val(template);
                }
            });

            $('.select-template-layout').eq(0).click();

            $(document).on('click', '.start-design', function() {
                var form = $('.template-form');

                if ($('.select-template-layout.selected').length == 0) {
                    // Success alert
                    new Dialog('alert', {
                        title: "Error",
                        message: "Continue from an existing template base",
                    });
                    return;
                }

                if (form.valid()) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
