@push('styles')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <style type="text/css">

        .CodeMirror {
            height: 400px;
        }
        .editor-preview-active,
        .editor-preview-active-side {
            /*display:block;*/
        }
        .editor-preview-side>p,
        .editor-preview>p {
            margin:inherit;
        }
        .editor-preview pre,
        .editor-preview-side pre {
            background:inherit;
            margin:inherit;
        }
        .editor-preview table td,
        .editor-preview table th,
        .editor-preview-side table td,
        .editor-preview-side table th {
            border:inherit;
            padding:inherit;
        }
        .view_data_param {
            cursor: pointer;
        }
    </style>
@endpush

<x-shopper::layouts.setting :title="__('Mail ~ Create Template')">

    <x-shopper::breadcrumb back="shopper.settings.mails.select-template">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.mails.select-template')" title="Templates" />
    </x-shopper::breadcrumb>

    <div class="mt-3 pb-5 border-b border-secondary-200 dark:border-secondary-700">
        <h3 class="text-2xl font-bold leading-6 text-secondary-900 sm:text-3xl sm:leading-9 sm:truncate dark:text-white">
            {{ ucfirst($skeleton['type']) }} / {{ ucfirst($skeleton['name']) }} / {{ ucfirst($skeleton['skeleton']) }}
        </h3>
    </div>

    <div class="mt-6 pb-10 overflow-hidden">
        <form action="{{ route('shopper.settings.mails.store-template') }}" method="POST">
            @csrf
            <input type="hidden" name="template_type" value="{{ $type }}">
            <input type="hidden" name="template_view_name" value="{{ $name }}">
            <input type="hidden" name="template_skeleton" value="{{ $skeleton['skeleton'] }}">

            <div class="grid gap-4 sm:grid-cols-4 sm:gap-6">
                <div class="sm:col-span-3">
                    <label for="content" class="sr-only">{{ __('Content') }}</label>
                    <textarea id="template_editor" id="content" name="content" cols="30" rows="10">{{ $skeleton['template'] }}</textarea>
                </div>
                <div class="sm:col-span-1 space-y-5">
                    <x-shopper::forms.group for="name" label="Template name" :error="$errors->first('template_name')" isRequired>
                        <x-shopper::forms.input name="template_name" type="text" id="name" placeholder="Shopper Newsletter" />
                    </x-shopper::forms.group>
                    <x-shopper::forms.group for="description" label="Template description" :error="$errors->first('template_description')" isRequired>
                        <x-shopper::forms.textarea name="template_description" id="description" />
                    </x-shopper::forms.group>
                    <div class="pt-5 border-t border-secondary-200 dark:border-secondary-700">
                        <div class="flex justify-end">
                            <x-shopper::buttons.primary type="submit">
                                {{ __('Create') }}
                            </x-shopper::buttons.primary>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</x-shopper::layouts.setting>

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.0/tinymce.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/xml/xml.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/css/css.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/javascript/javascript.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/htmlmixed/htmlmixed.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.43.0/addon/display/placeholder.js"></script>

    <script type="text/javascript">
        @if ($skeleton['type'] === 'markdown')
        var simplemde = new SimpleMDE(
            {
                element: $("#template_editor")[0],
                toolbar: [
                    {
                        name: "bold",
                        action: SimpleMDE.toggleBold,
                        className: "fa fa-bold",
                        title: "Bold",
                    },
                    {
                        name: "italic",
                        action: SimpleMDE.toggleItalic,
                        className: "fa fa-italic",
                        title: "Italic",
                    },
                    {
                        name: "strikethrough",
                        action: SimpleMDE.toggleStrikethrough,
                        className: "fa fa-strikethrough",
                        title: "Strikethrough",
                    },
                    {
                        name: "heading",
                        action: SimpleMDE.toggleHeadingSmaller,
                        className: "fa fa-header",
                        title: "Heading",
                    },
                    {
                        name: "code",
                        action: SimpleMDE.toggleCodeBlock,
                        className: "fa fa-code",
                        title: "Code",
                    },
                    "|",
                    {
                        name: "unordered-list",
                        action: SimpleMDE.toggleBlockquote,
                        className: "fa fa-list-ul",
                        title: "Generic List",
                    },
                    {
                        name: "uordered-list",
                        action: SimpleMDE.toggleOrderedList,
                        className: "fa fa-list-ol",
                        title: "Numbered List",
                    },
                    {
                        name: "clean-block",
                        action: SimpleMDE.cleanBlock,
                        className: "fa fa-eraser fa-clean-block",
                        title: "Clean block",
                    },
                    "|",
                    {
                        name: "link",
                        action: SimpleMDE.drawLink,
                        className: "fa fa-link",
                        title: "Create Link",
                    },
                    {
                        name: "image",
                        action: SimpleMDE.drawImage,
                        className: "fa fa-picture-o",
                        title: "Insert Image",
                    },
                    {
                        name: "horizontal-rule",
                        action: SimpleMDE.drawHorizontalRule,
                        className: "fa fa-minus",
                        title: "Insert Horizontal Line",
                    },
                    "|",
                    {
                        name: "button-component",
                        action: setButtonComponent,
                        className: "fa fa-hand-pointer-o",
                        title: "Button Component",
                    },
                    {
                        name: "table-component",
                        action: setTableComponent,
                        className: "fa fa-table",
                        title: "Table Component",
                    },
                    {
                        name: "promotion-component",
                        action: setPromotionComponent,
                        className: "fa fa-bullhorn",
                        title: "Promotion Component",
                    },
                    {
                        name: "panel-component",
                        action: setPanelComponent,
                        className: "fa fa-thumb-tack",
                        title: "Panel Component",
                    },
                    "|",
                    {
                        name: "side-by-side",
                        action: SimpleMDE.toggleSideBySide,
                        className: "fa fa-columns no-disable no-mobile",
                        title: "Toggle Side by Side",
                    },
                    {
                        name: "fullscreen",
                        action: SimpleMDE.toggleFullScreen,
                        className: "fa fa-arrows-alt no-disable no-mobile",
                        title: "Toggle Fullscreen",
                    },
                    {
                        name: "preview",
                        action: SimpleMDE.togglePreview,
                        className: "fa fa-eye no-disable",
                        title: "Toggle Preview",
                    },
                ],
                renderingConfig: { singleLineBreaks: true, codeSyntaxHighlighting: true,},
                hideIcons: ["guide"],
                spellChecker: false,
                promptURLs: true,
                placeholder: "{{ __('Write your Beautiful Email') }}",
            });
        function setButtonComponent(editor) {
            link = prompt('Button Link');
            var cm = editor.codemirror;
            var output = '';
            var selectedText = cm.getSelection();
            var text = selectedText || 'Button Text';
            output = `
                    [component]: # ('mail::button',  ['url' => '`+ link +`'])
                    ` + text + `
                    [endcomponent]: #
                `;
            cm.replaceSelection(output);
        }
        function setPromotionComponent(editor) {
            var cm = editor.codemirror;
            var output = '';
            var selectedText = cm.getSelection();
            var text = selectedText || 'Promotion Text';
            output = `
                    [component]: # ('mail::promotion')
                    ` + text + `
                    [endcomponent]: #
                `;
            cm.replaceSelection(output);
        }
        function setPanelComponent(editor) {
            var cm = editor.codemirror;
            var output = '';
            var selectedText = cm.getSelection();
            var text = selectedText || 'Panel Text';
            output = `
                    [component]: # ('mail::panel')
                    ` + text + `
                    [endcomponent]: #
                `;
            cm.replaceSelection(output);
        }
        function setTableComponent(editor) {
            var cm = editor.codemirror;
            var output = '';
            var selectedText = cm.getSelection();
            output = `
                    [component]: # ('mail::table')
                    | Laravel       | Table         | Example  |
                    | ------------- |:-------------:| --------:|
                    | Col 2 is      | Centered      | $10      |
                    | Col 3 is      | Right-Aligned | $20      |
                    [endcomponent]: #
                `;
            cm.replaceSelection(output);
        }
        @else
        tinymce.init({
            selector: "textarea#template_editor",
            menubar : false,
            visual: false,
            height:600,
            inline_styles : true,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table directionality emoticons template paste fullpage code legacyoutput"
            ],
            content_css: "css/content.css",
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image fullpage | forecolor backcolor emoticons | preview | code",
            fullpage_default_encoding: "UTF-8",
            fullpage_default_doctype: "<!DOCTYPE html>",
            init_instance_callback: function (editor)
            {
                setTimeout(function(){
                    editor.execCommand("mceRepaint");
                }, 5000);
            }
        });
        @endif
    </script>
@endpush
