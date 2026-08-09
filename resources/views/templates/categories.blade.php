@extends('layouts.popup.small')

@section('content')


    @if (auth()->user()->role == 'guest')
        <form class="categories-form"
            action="{{ route('templates.categories', [
                'uid' => $template->uid,
            ]) }}"
            method="POST">

            <h2 class="mt-0 mb-2">Set template category</h2>
            <p class="mt-0">Choose one or more categories for your template. Notice: if your template does not fall upon
                any category, it will not be visible to users.</p>

            {{ csrf_field() }}

            <div class="categories-checkboxes">
                @foreach (App\Models\TemplateCategory::all() as $category)
                    @include('helpers.form_control', [
                        'type' => 'checkbox2',
                        'name' => 'categories[' . $category->uid . ']',
                        'value' => $template->hasCategory($category) ? 'true' : 'false',
                        'label' => $category->name,
                        'options' => ['false', 'true'],
                        'rules' => [],
                    ])
                @endforeach
            </div>

            <hr>

            <div class="mt-4">
                <button type="submit" class="btn btn-secondary">Save</button>
            </div>
        </form>
    @elseif (auth()->user()->role == 'company')
        <form class="categories-form"
            action="{{ route('templates.categories', [
                'uid' => $template->uid,
            ]) }}"
            method="POST">

            <h2 class="mt-0 mb-2">Set template category</h2>
            <p class="mt-0">Choose one or more categories for your template. Notice: if your template does not fall upon
                any category, it will not be visible to users.</p>

            {{ csrf_field() }}

            <div class="categories-checkboxes">
                @foreach (App\Models\TemplateCategory::all() as $category)
                    @include('helpers.form_control', [
                        'type' => 'checkbox2',
                        'name' => 'categories[' . $category->uid . ']',
                        'value' => $template->hasCategory($category) ? 'true' : 'false',
                        'label' => $category->name,
                        'options' => ['false', 'true'],
                        'rules' => [],
                    ])
                @endforeach
            </div>

            <hr>

            <div class="mt-4">
                <button type="submit" class="btn btn-secondary">Save</button>
            </div>
        </form>
    @else
        <form class="categories-form"
            action="{{ route('templates.categories', [
                'uid' => $template->uid,
            ]) }}"
            method="POST">

            <h2 class="mt-0 mb-2">Set template category</h2>
            <p class="mt-0">Choose one or more categories for your template. Notice: if your template does not fall upon
                any category, it will not be visible to users.</p>

            {{ csrf_field() }}

            <div class="categories-checkboxes">
                @foreach (App\Models\TemplateCategory::all() as $category)
                    @include('helpers.form_control', [
                        'type' => 'checkbox2',
                        'name' => 'categories[' . $category->uid . ']',
                        'value' => $template->hasCategory($category) ? 'true' : 'false',
                        'label' => $category->name,
                        'options' => ['false', 'true'],
                        'rules' => [],
                    ])
                @endforeach
            </div>

            <hr>

            <div class="mt-4">
                <button type="submit" class="btn btn-secondary">Save</button>
            </div>
        </form>
    @endif


    <script>
        $('.categories-form').on('submit', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();

            addMaskLoading();

            //
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                success: function(response) {
                    removeMaskLoading();

                    // notify

                    categoriesPopup.hide();

                    TemplatesIndex.getList().load();
                }
            });
        })
    </script>
@endsection
