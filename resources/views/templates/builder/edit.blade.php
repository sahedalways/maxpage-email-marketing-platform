<!doctype html>
<html>

<head>
    <title>{{ getApplicationName() }} - {{ $template->name }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/png" href="{{ asset(getFavIcon()) }}">

    <!-- BuilderJS CORE -->
    <link href="{{ asset('builder/builder.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ asset('builder/builder.js') }}"></script>

    <!-- BuilderJS CUSTOM -->
    <link href="{{ asset('assets/template/css/builder-custom.css') }}" rel="stylesheet" type="text/css">
    @include('builder.js.widgets')

    <!-- Select2 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/template/select2/css/select2.min.css') }}">
    <script type="text/javascript" src="{{ asset('assets/template/select2/js/select2.min.js') }}"></script>

    <!-- Autofill -->
    <link href="{{ asset('assets/template/css/UrlAutoFill.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('assets/template/js/UrlAutoFill.js') }}"></script>

    <script>
        (function($) {
            $.fn.serializeObject = function() {

                var self = this,
                    json = {},
                    push_counters = {},
                    patterns = {
                        "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                        "key": /[a-zA-Z0-9_]+|(?=\[\])/g,
                        "push": /^$/,
                        "fixed": /^\d+$/,
                        "named": /^[a-zA-Z0-9_]+$/
                    };


                this.build = function(base, key, value) {
                    base[key] = value;
                    return base;
                };

                this.push_counter = function(key) {
                    if (push_counters[key] === undefined) {
                        push_counters[key] = 0;
                    }
                    return push_counters[key]++;
                };

                $.each($(this).serializeArray(), function() {

                    // Skip invalid keys
                    if (!patterns.validate.test(this.name)) {
                        return;
                    }

                    var k,
                        keys = this.name.match(patterns.key),
                        merge = this.value,
                        reverse_key = this.name;

                    while ((k = keys.pop()) !== undefined) {

                        // Adjust reverse_key
                        reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                        // Push
                        if (k.match(patterns.push)) {
                            merge = self.build([], self.push_counter(reverse_key), merge);
                        }

                        // Fixed
                        else if (k.match(patterns.fixed)) {
                            merge = self.build([], k, merge);
                        }

                        // Named
                        else if (k.match(patterns.named)) {
                            merge = self.build({}, k, merge);
                        }
                    }

                    json = $.extend(true, json, merge);
                });

                return json;
            };
        })(jQuery);
    </script>

    @if ($template->theme)
        @include('builder.themes.' . $template->theme)
    @endif


</head>

<body>
    <style>
        .lds-dual-ring {
            display: inline-block;
            width: 80px;
            height: 80px;
        }

        .lds-dual-ring:after {
            content: " ";
            display: block;
            width: 30px;
            height: 30px;
            margin: 4px;
            border-radius: 80%;
            border: 2px solid #aaa;
            border-color: #007bff transparent #007bff transparent;
            animation: lds-dual-ring 1.2s linear infinite;
        }

        @keyframes lds-dual-ring {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div
        style="text-align: center;
            height: 100vh;
            vertical-align: middle;
            padding: auto;
            display: flex;">
        <div style="margin:auto" class="lds-dual-ring"></div>
    </div>


    <input type="hidden" id="userRole" value="{{ auth()->user()->role }}">
    <input type="hidden" id="templateUid" value="{{ $template->uid }}">

    <script>
        const userRole = document.getElementById('userRole').value;
        const templateUid = document.getElementById('templateUid').value;


        const builderEditContentUrl = userRole == 'guest' ?
            '{{ route('templates.builderEditContent', $template->uid, false) }}' :
            (userRole == 'company' ?
                '{{ route('templates.builderEditContent', $template->uid) }}' :
                '{{ route('templates.builderEditContent', $template->uid) }}');

        const uploadAssetUrl = '{{ route('templates.uploadTemplateAssets', ['uid' => '__TEMPLATE_UID__']) }}'
            .replace('__TEMPLATE_UID__', templateUid)
            .replace('templates.uploadTemplateAssets', userRole === 'guest' ? 'guest.templates.uploadTemplateAssets' :
                (userRole === 'company' ? 'company.templates.uploadTemplateAssets' : 'templates.uploadTemplateAssets'));

        let saveUrl = '';

        if (userRole === 'guest') {
            saveUrl = '{{ route('templates.builderEdit', ['uid' => '__TEMPLATE_UID__']) }}'
                .replace('__TEMPLATE_UID__', templateUid);
        } else if (userRole === 'company') {
            saveUrl = '{{ route('templates.builderEdit', ['uid' => '__TEMPLATE_UID__']) }}'
                .replace('__TEMPLATE_UID__', templateUid);
        } else {
            saveUrl = '{{ route('templates.builderEdit', ['uid' => '__TEMPLATE_UID__']) }}'
                .replace('__TEMPLATE_UID__', templateUid);
        }


        const redirectUrl = userRole == 'guest' ?
            '{{ route('templates.index') }}' :
            (userRole == 'company' ?
                '{{ route('templates.index') }}' :
                '{{ route('templates.index') }}');


        var CSRF_TOKEN = "{{ csrf_token() }}";
        var editor;
        var templates = {!! json_encode($templates) !!};

        $(function() {
            editor = new Editor({
                strict: true,
                showHelp: false,
                showInlineToolbar: false,
                emailMode: true,
                lang: {!! json_encode(auth()->user()->getBuilderLang()) !!},

                url: builderEditContentUrl,
                backCallback: function() {
                    if (parent.$('.full-iframe-popup').length) {
                        parent.$('.full-iframe-popup').hide();
                        parent.$('body').removeClass('overflow-hidden');
                    }

                    if (parent.$('.listing-form').length) {
                        parent.TemplatesIndex.getList().load();
                    } else {
                        window.location = redirectUrl;
                    }
                },
                uploadAssetUrl: uploadAssetUrl,
                uploadAssetMethod: 'POST',
                saveUrl: saveUrl,
                saveMethod: 'POST',
                tags: {!! json_encode(App\Models\Template::builderTags(isset($list) ? $list : null)) !!},
                root: '{{ URL::asset('builder') }}/',
                templates: templates,
                filemanager: '{{ asset('filemanager2/dialog.php') }}',
                logo: '{{ asset('assets/img/logo.png') }}',
                backgrounds: [
                    '{{ url('/assets/template/images/backgrounds/images1.jpg') }}',
                    '{{ url('/assets/template/images/backgrounds/images2.jpg') }}',
                    '{{ url('/assets/template/images/backgrounds/images3.jpg') }}',
                    '{{ url('/assets/template/images/backgrounds/images4.png') }}',
                    '{{ url('/assets/template/images/backgrounds/images5.jpg') }}',
                    '{{ url('/assets/template/images/backgrounds/images6.jpg') }}',
                    '{{ url('/assets/template/images/backgrounds/images9.jpg') }}',
                    '{{ url('/assets/template/images/backgrounds/images11.jpg') }}',
                    '{{ url('/assets/template/images/backgrounds/images12.jpg') }}',
                    '{{ url('/assets/template/images/backgrounds/images13.jpg') }}',
                    '{{ url('/assets/template/images/backgrounds/images14.jpg') }}',
                    '{{ url('/assets/template/images/backgrounds/images15.jpg') }}',
                    '{{ url('/assets/template/images/backgrounds/images16.jpg') }}',
                    '{{ url('/assets/template/images/backgrounds/images17.png') }}'
                ],
                customInlineEdit: function(container) {
                    var thisEditor = this;

                    var tinyconfig = {
                        skin: 'oxide-dark',
                        inline: true,
                        menubar: false,
                        force_br_newlines: false,
                        force_p_newlines: false,
                        forced_root_block: '',
                        inline_boundaries: false,
                        relative_urls: false,
                        convert_urls: false,
                        typeahead_urls: false,
                        remove_script_host: false,
                        valid_elements: '*[*],meta[*]',
                        valid_children: '+p[ol],+p[ul],+h1[div],+h2[div],+h3[div],+h4[div],+h5[div],+h6[div],+a[div],*[*]',
                        plugins: 'image link lists autolink',
                        font_formats: "Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; MS Mincho=ms mincho; MS PMincho=ms pmincho; Oswald=oswald; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats",
                        //toolbar: 'undo redo | bold italic underline | fontselect fontsizeselect | forecolor backcolor | alignleft aligncenter alignright alignfull | numlist bullist outdent indent',
                        toolbar: [
                            // 'undo redo | bold italic underline | fontselect fontsizeselect | link | menuDateButton',
                            // 'forecolor backcolor | alignleft aligncenter alignright alignfull | numlist bullist outdent indent'
                        ],
                        external_filemanager_path: '{{ url('/') }}'.replace('/index.php',
                            '') + "/filemanager2/",
                        filemanager_title: "Responsive Filemanager",
                        external_plugins: {
                            "filemanager": '{{ url('/') }}'.replace('/index.php', '') +
                                "/filemanager2/plugin.min.js"
                        },
                        setup: function(editor) {

                            /* Menu button that has a simple "insert date" menu item, and a submenu containing other formats. */
                            /* Clicking the first menu item or one of the submenu items inserts the date in the selected format. */
                            editor.ui.registry.addMenuButton('menuDateButton', {
                                text: getI18n('editor.insert_tag'),
                                fetch: function(callback) {
                                    var items = [];

                                    thisEditor.tags.forEach(function(tag) {
                                        if (tag.type == 'label') {
                                            items.push({
                                                type: 'menuitem',
                                                text: tag.tag
                                                    .replace("{",
                                                        "").replace(
                                                        "}", ""),
                                                onAction: function(
                                                    _) {
                                                    if (tag
                                                        .text) {
                                                        editor
                                                            .insertContent(
                                                                tag
                                                                .text
                                                            );
                                                    } else {
                                                        editor
                                                            .insertContent(
                                                                tag
                                                                .tag
                                                            );
                                                    }
                                                }
                                            });
                                        }
                                    });

                                    callback(items);
                                }
                            });
                        }
                    };

                    var unsupported_types = 'td, table, img, body';
                    if (!container.is(unsupported_types) && (container.is('[builder-inline-edit]') || !
                            editor.strict)) {
                        container.addClass('builder-class-tinymce');
                        tinyconfig.selector = '.builder-class-tinymce';
                        editor.tinymce = $("#builder_iframe")[0].contentWindow.tinymce.init(tinyconfig);

                        container.removeClass('builder-class-tinymce');
                    }
                },
                loaded: function() {
                    var thisEditor = this;

                    // add custom css
                    this.addCustomCss('{{ url('/assets/template/css/builder-edit.css') }}');
                }
            });

            // product widgets
            // editor.addWidget(new ProductListWidget(), {
            //     index: 0,
            //     group: 'E-Commerce',
            // });
            // editor.addWidget(new ProductWidget(), {
            //     index: 0,
            //     group: 'E-Commerce',
            // });

            // Rss widget
            editor.addWidget(new RssWidget(), {
                index: 3
            });

            editor.init();

            //
            $(document).on('click', '.filemanager-ok', function(e) {
                alert('Please click on the thumbnail to select the corresponding image');
            })
            $(document).on('click', '.filemanager-cancel', function(e) {
                $('.PopUpCloseButton').click();
            })

            //
            var urlFill = new UrlAutoFill({!! json_encode($template->urlTagsDropdown()) !!});
        });
    </script>
</body>

</html>
