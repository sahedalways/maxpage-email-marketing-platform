@extends('layouts.app')
@section('head')
    @include('layouts.core.script-var')
    @include('layouts.core.partial-script')
@endsection
@section('content')
    <div>
        <div class="row align-items-center justify-content-between mb-4 templates-header-row">
            <div class="col">
                <h5 class="fw-500 text-white mb-0">Templates</h5>
            </div>

            @if (auth()->user()->role == 'guest')
                <div class="col-auto">
                    <a href="{{ route('templates.index', [
                        'from' => 'mine',
                        'view' => 'list',
                    ]) }}"
                        class="btn btn-icon btn-3 btn-white text-primary mb-0 {{ request()->from != 'gallery' ? 'focus' : '' }}">
                        <i class="fa fa-portrait me-2"></i>My templates
                    </a>
                    <a href="{{ route('templates.index', [
                        'from' => 'gallery',
                        'view' => 'grid',
                    ]) }}"
                        class="btn btn-icon btn-3 btn-white text-primary mb-0 {{ request()->from == 'gallery' ? 'focus' : '' }}">
                        <i class="fa fa-image me-2"></i>Base Template Gallery
                    </a>
                </div>
            @elseif (auth()->user()->role == 'company')
                <div class="col-auto">
                    <a href="{{ route('templates.index', [
                        'from' => 'mine',
                        'view' => 'list',
                    ]) }}"
                        class="btn btn-icon btn-3 btn-white text-primary mb-0 {{ request()->from != 'gallery' ? 'focus' : '' }}">
                        <i class="fa fa-portrait me-2"></i>My templates
                    </a>
                    <a href="{{ route('templates.index', [
                        'from' => 'gallery',
                        'view' => 'grid',
                    ]) }}"
                        class="btn btn-icon btn-3 btn-white text-primary mb-0 {{ request()->from == 'gallery' ? 'focus' : '' }}">
                        <i class="fa fa-image me-2"></i>Base Template Gallery
                    </a>
                </div>
            @else
                <div class="col-auto">
                    <a href="{{ route('templates.index', [
                        'from' => 'mine',
                        'view' => 'list',
                    ]) }}"
                        class="btn btn-icon btn-3 btn-white text-primary mb-0 {{ request()->from != 'gallery' ? 'focus' : '' }}">
                        <i class="fa fa-portrait me-2"></i>My templates
                    </a>
                    <a href="{{ route('templates.index', [
                        'from' => 'gallery',
                        'view' => 'grid',
                    ]) }}"
                        class="btn btn-icon btn-3 btn-white text-primary mb-0 {{ request()->from == 'gallery' ? 'focus' : '' }}">
                        <i class="fa fa-image me-2"></i>Base Template Gallery
                    </a>
                </div>
            @endif




        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header p-4">
                        <div id="TemplatesIndexContainer"
                            class="listing-form  view-{{ request()->view ? request()->view : 'list' }}"
                            data-url="{{ route('templates.listing') }}"
                            per-page="{{ App\Models\Template::$itemsPerPage }}">
                            <div class="top-list-controls top-sticky-content"
                                style="display: flex; flex-wrap: wrap; align-items: center; gap: 10px; background: #f7f9fc; border: 1px solid #eef2f7; border-radius: 12px; padding: 12px 14px;">
                                <div class="filter-box d-flex align-items-center flex-wrap gap-2"
                                    style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                                    <input type="hidden" name="view" value="{{ request()->view }}" />

                                    <span class="filter-group"
                                        style="display: inline-flex; align-items: stretch; background: #fff; border: 1px solid #e3e9f0; border-radius: 8px; overflow: hidden; height: 38px;">
                                        <select class="form-select" name="sort_order"
                                            style="appearance: none; -webkit-appearance: none; -moz-appearance: none; border: none; box-shadow: none; background-color: #fff; background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 16 16%22%3E%3Cpath fill=%22none%22 stroke=%22%2367748e%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M2 5l6 6 6-6%22/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 8px center; background-size: 12px; width: auto; color: #344767; font-weight: 600; font-size: 13px; padding: 0 28px 0 12px; height: 38px; cursor: pointer;">
                                            <option value="created_at">Created At</option>
                                            <option value="name">Name</option>
                                        </select>
                                        <input type="hidden" name="sort_direction" value="desc" />
                                        <button type="button" class="sort-direction" data-popup="tooltip"
                                            title="Change sort direction" role="button"
                                            style="border: none; background: #f8fafc; border-left: 1px solid #e3e9f0; width: 36px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                            <span class="material-symbols-rounded desc"
                                                style="font-size: 18px; color: #8392ab; transform: none;">sort</span>
                                        </button>
                                    </span>

                                    @if (request()->from != 'mine')
                                        <span class="filter-group"
                                            style="display: inline-flex; align-items: center; background: #fff; border: 1px solid #e3e9f0; border-radius: 8px; overflow: hidden; height: 38px;">
                                            <select class="form-select" name="category_uid"
                                                style="appearance: none; -webkit-appearance: none; -moz-appearance: none; border: none; box-shadow: none; background-color: #fff; background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 16 16%22%3E%3Cpath fill=%22none%22 stroke=%22%2367748e%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M2 5l6 6 6-6%22/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 8px center; background-size: 12px; width: auto; color: #344767; font-weight: 600; font-size: 13px; padding: 0 28px 0 12px; height: 38px; cursor: pointer;">
                                                <option value="">All categories</option>
                                                @foreach (\App\Models\TemplateCategory::all() as $category)
                                                    <option value="{{ $category->uid }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </span>
                                    @endif
                                    <input type="text" id="userRole" value="{{ auth()->user()->role }}" hidden>

                                    <span class="filter-group">
                                        <input type="hidden" name="from"
                                            value="{{ request()->from ? request()->from : 'mine' }}" />
                                    </span>

                                    <span class="text-nowrap"
                                        style="position: relative; display: inline-flex; align-items: center;">
                                        <input type="text" name="keyword" class="form-control search"
                                            value="{{ request()->keyword }}" placeholder="Type to search"
                                            style="min-width: 220px; height: 38px; border-radius: 8px; border-color: #e3e9f0; padding-left: 36px; font-size: 13px;" />
                                        <span class="material-symbols-rounded"
                                            style="position: absolute; left: 10px; font-size: 18px; color: #8392ab; transform: translateY(-50%) scale(1.2); pointer-events: none; top: 50%;">search</span>
                                    </span>
                                </div>

                                <div class="d-flex align-items-center"
                                    style="margin-left: auto; display: flex; align-items: center; gap: 8px;">
                                    <div class="view-toggle" style="display: flex; align-items: center; position: relative; top: 8px;">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('templates.index', [
                                                'from' => request()->from ? request()->from : 'mine',
                                                'view' => 'grid',
                                            ]) }}"
                                                class="btn btn-white text-primary {{ request()->view == 'grid' ? 'active' : '' }}"
                                                style="{{ request()->view == 'grid' ? 'background: #0486A6; color: #fff; border: 1px solid #0486A6;' : 'background: #fff; color: #344767; border: 1px solid #e3e9f0;' }} border-radius: 6px 0 0 6px; height: 38px; width: 42px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fa fa-grip"></i>
                                            </a>
                                            <a href="{{ route('templates.index', [
                                                'from' => request()->from ? request()->from : 'mine',
                                                'view' => 'list',
                                            ]) }}"
                                                class="btn btn-white text-primary {{ !request()->view || request()->view == 'list' ? 'active' : '' }}"
                                                style="{{ !request()->view || request()->view == 'list' ? 'background: #0486A6; color: #fff; border: 1px solid #0486A6;' : 'background: #fff; color: #344767; border: 1px solid #e3e9f0;' }} border-left: 0; border-radius: 0 6px 6px 0; height: 38px; width: 42px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fa fa-list"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <a href="{{ route('templates.uploadTemplate') }}"
                                        class="btn btn-sm btn-primary mb-0"
                                        style="border-radius: 8px; font-weight: 600; height: 38px; line-height: 38px; padding-top: 0; padding-bottom: 0;">
                                        <i class="fa fa-upload me-1"></i>Upload
                                    </a>
                                    <a href="{{ route('templates.builderCreate') }}"
                                        class="btn btn-sm btn-primary mb-0"
                                        style="border-radius: 8px; font-weight: 600; height: 38px; line-height: 38px; padding-top: 0; padding-bottom: 0;">
                                        <i class="fa fa-plus me-1"></i>Create
                                    </a>
                                </div>
                            </div>

                            <div id="TemplatesIndexContent" class="pml-table-container">



                            </div>
                        </div>

                        <script>
                            var TemplatesIndex = {
                                list: null,
                                getList: function() {
                                    if (this.list == null) {
                                        const userRole = document.getElementById('userRole').value;
                                        const currentUrl = userRole === 'guest' ?
                                            '{{ route('templates.listing') }}' :
                                            userRole === 'company' ?
                                            '{{ route('templates.listing') }}' :
                                            '{{ route('templates.listing') }}';


                                        this.list = makeList({
                                            url: currentUrl,
                                            container: $('#TemplatesIndexContainer'),
                                            content: $('#TemplatesIndexContent')
                                        });
                                    }
                                    return this.list;
                                }
                            };

                            $(document).ready(function() {
                                TemplatesIndex.getList().load();
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
