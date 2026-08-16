@if ($templates->count() > 0)
    <div class="row mt-4">
        @foreach ($templates as $key => $template)
            <div class="col-md-2 col-sm-6 col-6 mb-4">
                <div class="card mb-4 shadow-sm template-card">
                    <span class="template-image-box2">
                        <img class="card-img-top" src="{{ $template->getThumbUrl() }}"
                            style="height: 100%; width: auto; display: block;">
                        <div class="preview_control">
                            <div style="width: calc(80% - 0px);">


                                @if (auth()->user()->role == 'guest')
                                    <div class="mb-2">
                                        <a class="btn btn-light d-block btn-icon btn-3 text-primary" href="#preview"
                                            onclick="popupwindow('{{ route('templates.preview', $template->uid) }}', `{{ $template->name }}`, 800)">
                                            Preview</a>
                                    </div>
                                @elseif (auth()->user()->role == 'company')
                                    <div class="mb-2">
                                        <a class="btn btn-light d-block btn-icon btn-3 text-primary" href="#preview"
                                            onclick="popupwindow('{{ route('templates.preview', $template->uid) }}', `{{ $template->name }}`, 800)">
                                            Preview</a>
                                    </div>
                                @else
                                    <div class="mb-2">
                                        <a class="btn btn-light d-block btn-icon btn-3 text-primary" href="#preview"
                                            onclick="popupwindow('{{ route('templates.preview', $template->uid) }}', `{{ $template->name }}`, 800)">
                                            Preview</a>
                                    </div>
                                @endif
                                <div>

                                    @if (auth()->user()->role == 'guest')
                                        <div>
                                            <div>
                                                <a href="{{ route('templates.builderEdit', $template->uid) }}"
                                                    role="button" class="btn btn-primary btn-icon d-block mb-2">
                                                    {{--                                               template-compose --}}
                                                    PRO
                                                </a>
                                            </div>

                                            <div>
                                                <a href="{{ route('templates.edit', $template->uid) }}"
                                                    role="button"
                                                    class="btn btn-info btn-icon template-compose-classic d-block">
                                                    Classic
                                                </a>
                                            </div>
                                        </div>
                                    @elseif (auth()->user()->role == 'company')
                                        <div>
                                            <div>
                                                <a href="{{ route('templates.builderEdit', $template->uid) }}"
                                                    role="button" class="btn btn-primary btn-icon d-block mb-2">
                                                    {{--                                               template-compose --}}
                                                    PRO
                                                </a>
                                            </div>

                                            <div>
                                                <a href="{{ route('templates.edit', $template->uid) }}"
                                                    role="button"
                                                    class="btn btn-info btn-icon template-compose-classic d-block">
                                                    Classic
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div>
                                            <div>
                                                <a href="{{ route('templates.builderEdit', $template->uid) }}"
                                                    role="button" class="btn btn-primary btn-icon d-block mb-2">
                                                    {{--                                               template-compose --}}
                                                    PRO
                                                </a>
                                            </div>

                                            <div>
                                                <a href="{{ route('templates.edit', $template->uid) }}" role="button"
                                                    class="btn btn-info btn-icon template-compose-classic d-block">
                                                    Classic
                                                </a>
                                            </div>
                                        </div>
                                    @endif


                                </div>
                            </div>
                        </div>
                    </span>
                    <div class="card-body p-3">
                        <h6 title="{{ $template->name }}" class="fw-600 mt-1 mb-1 text-ellipsis">{{ $template->name }}
                        </h6>
                        <p style="display: block;
                        overflow: hidden;" class="card-text">
                            @if ($template->categories()->count())
                                <span
                                    class="template-categories">{{ $template->categories->map(function ($cat) {
                                            return $cat->name;
                                        })->join(', ') }}</span>
                            @else
                                <span style="" class="xtooltip"
                                    title="Choose one or more category for your template to make it available to users">No
                                    category</span>
                            @endif
                        </p>
                        <div class="">
                            <div class="d-flex align-items-center justify-content-end">

                                @if (auth()->user()->role == 'guest')
                                    <div class="btn-group">
                                        <button role="button" class="btn btn-light dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            Actions </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item change-template-name"
                                                    href="{{ route('templates.changeName', [
                                                        'uid' => $template->uid,
                                                    ]) }}">
                                                    <span class="material-symbols-rounded">subtitles</span>
                                                    Change name
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="#preview"
                                                    onclick="popupwindow('{{ route('templates.preview', $template->uid) }}', `{{ $template->name }}`, 800)"><span
                                                        class="material-symbols-rounded">zoom_in</span> Preview</a></li>
                                            <li>
                                                <a class="dropdown-item upload-thumb-button"
                                                    href="{{ route('templates.updateThumb', $template->uid) }}">
                                                    <span class="material-symbols-rounded">insert_photo</span> Change
                                                    thumbnail
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item template-categories"
                                                    href="{{ route('templates.categories', [
                                                        'uid' => $template->uid,
                                                    ]) }}">
                                                    <span class="material-symbols-rounded">category</span> Categories
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('templates.export', $template->uid) }}"
                                                    role="button" class="dropdown-item" link-method="POST">
                                                    <span class="material-symbols-rounded me-2">download</span> Export
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('templates.copy', $template->uid) }}"
                                                    role="button" class="dropdown-item copy-template-button"
                                                    link-method="GET">
                                                    <span class="material-symbols-rounded me-2">copy_all</span> Copy
                                                </a>
                                            </li>

                                            <li><a class="dropdown-item list-action-single"
                                                    link-confirm="You're about to delete :number template(s)."
                                                    href="{{ route('templates.delete', ['uids' => $template->uid]) }}">
                                                    <span class="material-symbols-rounded me-2">delete_outline</span>
                                                    Delete</a></li>

                                        </ul>
                                    </div>
                                @elseif (auth()->user()->role == 'company')
                                    <div class="btn-group">
                                        <button role="button" class="btn btn-light dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            Actions </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item change-template-name"
                                                    href="{{ route('templates.changeName', [
                                                        'uid' => $template->uid,
                                                    ]) }}">
                                                    <span class="material-symbols-rounded">subtitles</span>
                                                    Change name
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="#preview"
                                                    onclick="popupwindow('{{ route('templates.preview', $template->uid) }}', `{{ $template->name }}`, 800)"><span
                                                        class="material-symbols-rounded">zoom_in</span> Preview</a></li>
                                            <li>
                                                <a class="dropdown-item upload-thumb-button"
                                                    href="{{ route('templates.updateThumb', $template->uid) }}">
                                                    <span class="material-symbols-rounded">insert_photo</span> Change
                                                    thumbnail
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item template-categories"
                                                    href="{{ route('templates.categories', [
                                                        'uid' => $template->uid,
                                                    ]) }}">
                                                    <span class="material-symbols-rounded">category</span> Categories
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('templates.export', $template->uid) }}"
                                                    role="button" class="dropdown-item" link-method="POST">
                                                    <span class="material-symbols-rounded me-2">download</span> Export
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('templates.copy', $template->uid) }}"
                                                    role="button" class="dropdown-item copy-template-button"
                                                    link-method="GET">
                                                    <span class="material-symbols-rounded me-2">copy_all</span> Copy
                                                </a>
                                            </li>

                                            <li><a class="dropdown-item list-action-single"
                                                    link-confirm="You're about to delete :number template(s)."
                                                    href="{{ route('templates.delete', ['uids' => $template->uid]) }}">
                                                    <span class="material-symbols-rounded me-2">delete_outline</span>
                                                    Delete</a></li>

                                        </ul>
                                    </div>
                                @else
                                    <div class="btn-group">
                                        <button role="button" class="btn btn-light dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            Actions </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item change-template-name"
                                                    href="{{ route('templates.changeName', [
                                                        'uid' => $template->uid,
                                                    ]) }}">
                                                    <span class="material-symbols-rounded">subtitles</span>
                                                    Change name
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="#preview"
                                                    onclick="popupwindow('{{ route('templates.preview', $template->uid) }}', `{{ $template->name }}`, 800)"><span
                                                        class="material-symbols-rounded">zoom_in</span> Preview</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item upload-thumb-button"
                                                    href="{{ route('templates.updateThumb', $template->uid) }}">
                                                    <span class="material-symbols-rounded">insert_photo</span> Change
                                                    thumbnail
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item template-categories"
                                                    href="{{ route('templates.categories', [
                                                        'uid' => $template->uid,
                                                    ]) }}">
                                                    <span class="material-symbols-rounded">category</span> Categories
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('templates.export', $template->uid) }}"
                                                    role="button" class="dropdown-item" link-method="POST">
                                                    <span class="material-symbols-rounded me-2">download</span> Export
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('templates.copy', $template->uid) }}"
                                                    role="button" class="dropdown-item copy-template-button"
                                                    link-method="GET">
                                                    <span class="material-symbols-rounded me-2">copy_all</span> Copy
                                                </a>
                                            </li>

                                            <li><a class="dropdown-item list-action-single"
                                                    link-confirm="You're about to delete :number template(s)."
                                                    href="{{ route('templates.delete', ['uids' => $template->uid]) }}">
                                                    <span class="material-symbols-rounded me-2">delete_outline</span>
                                                    Delete</a></li>

                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @include('elements/_per_page_select', ['items' => $templates, 'custom_per_pages' => [8, 12, 24]])
    <script>
        $(function() {
            // change name click
            $('.change-template-name').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                TemplatesList.getChangeNamePopup().load({
                    url: url
                });
            });

            $('.copy-template-button').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                TemplatesList.getCopyPopup().load({
                    url: url
                });
            });

            $('.template-compose').click(function(e) {
                e.preventDefault();

                var url = $(this).attr('href');

                openBuilder(url);
            });

            $('.template-compose-classic').click(function(e) {
                e.preventDefault();

                var url = $(this).attr('href');

                openBuilderClassic(url);
            });
        });


        var TemplatesList = {
            copyPopup: null,
            changeNamePopup: null,

            getCopyPopup: function() {
                if (this.copyPopup === null) {
                    this.copyPopup = new Popup();
                }

                return this.copyPopup;
            },

            getChangeNamePopup: function() {
                if (this.changeNamePopup === null) {
                    this.changeNamePopup = new Popup();
                }

                return this.changeNamePopup;
            }
        }
    </script>

    <script>
        var thumbPopup = new Popup();
        var categoriesPopup = new Popup();

        $('.upload-thumb-button').click(function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            thumbPopup.load(url);
        });

        $('.template-categories').click(function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            categoriesPopup.load(url);
        });
    </script>
@elseif (!empty(request()->keyword))
    <div class="empty-list"
        style="width: 100%; display: block; border: 1px dashed #c7d6e0; border-radius: 16px; padding: 70px 20px 60px; margin: 15px 0; background: linear-gradient(135deg, #f8fafd 0%, #eef4f8 100%); text-align: center;">
        <span class="material-symbols-rounded"
            style="display: inline-flex; width: 88px; height: 88px; align-items: center; justify-content: center; background: #fff; border-radius: 50%; box-shadow: 0 8px 24px rgba(2,120,150,.12); color: #0486A6; font-size: 44px; opacity: 1; transform: none;">auto_awesome_mosaic</span>
        <span class="line-1"
            style="display: block; margin: 15px 0 0; opacity: 1; font-size: 20px; font-weight: 700; color: #344767;">There
            is no search result</span>
        <span class="line-2"
            style="display: block; color: #67748e; font-size: 15px; margin-top: 4px;">Try a different keyword to find
            templates</span>
    </div>
@else
    <div class="empty-list"
        style="width: 100%; display: block; border: 1px dashed #c7d6e0; border-radius: 16px; padding: 70px 20px 60px; margin: 15px 0; background: linear-gradient(135deg, #f8fafd 0%, #eef4f8 100%); text-align: center;">
        <span class="material-symbols-rounded"
            style="display: inline-flex; width: 88px; height: 88px; align-items: center; justify-content: center; background: #fff; border-radius: 50%; box-shadow: 0 8px 24px rgba(2,120,150,.12); color: #0486A6; font-size: 44px; opacity: 1; transform: none;">auto_awesome_mosaic</span>
        <span class="line-1"
            style="display: block; margin: 15px 0 0; opacity: 1; font-size: 20px; font-weight: 700; color: #344767;">You
            have no template</span>
        <span class="line-2"
            style="display: block; color: #67748e; font-size: 15px; margin-top: 4px;">Create your first template to start
            sending beautiful emails</span>
        <a href="{{ route('templates.builderCreate') }}" class="btn btn-primary btn-sm mt-3"
            style="border-radius: 8px; font-weight: 600; margin-top: 18px;">
            <i class="fa fa-plus me-1"></i> Create Template
        </a>
    </div>
@endif
