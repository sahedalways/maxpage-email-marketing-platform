<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{ 'Message Templates' }}</h5>
        </div>
        <div class="col-auto">
            <a data-bs-toggle="modal" data-bs-target="#addTemp" wire:click="resetInputFields"
                class="btn btn-icon btn-3 btn-white text-primary mb-0">
                <i class="fa fa-plus me-2"></i> {{ 'Add Template' }}
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" class="form-control"
                                placeholder="{{ 'Search by Name' }}"
                                wire:model="search">
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        {{ 'Name' }}</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        {{ 'Content' }}</th>
                                    <th class="text-secondary opacity-7"> {{ 'Action' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp

                                @foreach ($templates as $row)
                                    <tr>
                                        <td>
                                            <p class="text-sm px-3 mb-0">{{ $i++ }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->name }}</p>
                                        </td>

                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{!! Str::limit(strip_tags($row->content), 55) !!}</p>
                                        </td>

                                        <td>
                                            {{-- <a data-bs-toggle="modal" data-bs-target="#editTemp"
                                                wire:click="editTemp({{ $row->id }})" type="button"
                                                class="badge badge-xs badge-warning fw-600 text-xs">
                                                {{ 'Edit Info' }}
                                            </a> --}}

                                            <a href="#" type="button"
                                                class="ms-2 badge badge-xs badge-success text-xs fw-600"
                                                data-bs-toggle="modal" data-bs-target="#quickView"
                                                wire:click="quickView({{ $row->id }})" type="button">
                                                {{ 'Quick View' }}
                                            </a>


                                            <a href="#" type="button"
                                                class="ms-2 badge badge-xs badge-danger text-xs fw-600"
                                                onclick="confirmDelete({{ $row->id }})">
                                                {{ 'Delete' }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($hasMorePages)
                            <div x-data="{
                                init() {
                                    let observer = new IntersectionObserver((entries) => {
                                        entries.forEach(entry => {
                                            if (entry.isIntersecting) {
                                                @this.call('loadTemplates')
                                                console.log('loading...')
                                            }
                                        })
                                    }, {
                                        root: null
                                    });
                                    observer.observe(this.$el);
                                }
                            }"
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-4">
                                <div class="text-center pb-2 d-flex justify-content-center align-items-center">
                                    Loading...
                                    <div class="spinner-grow d-inline-flex mx-2 text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade " id="addTemp" tabindex="-1" role="dialog" aria-labelledby="addTemp"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="addTemp">{{ 'Add Template' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-12 mb-1">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required placeholder="Enter template Name"
                                    wire:model="name">
                                @error('name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3" wire:ignore>
                                <label for="content" class="form-label">Content</label>
                                <textarea id="editor" wire:model="content" class="form-control"></textarea>
                                @error('content')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <strong>You can use variables in your content. For example, to mention the first name of
                                    a contact, use the variable
                                    <code>&#123;&#123;name&#125;&#125;</code>.</strong><br>
                            </div>



                            <div class="variables-list" id="suggestionsList">
                                <h4>Available Variables</h4>
                                <ul class="list-group">
                                    @foreach ($variables as $variable => $description)
                                        <li class="list-group-item">
                                            {{ $variable }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary"
                            wire:click.prevent="store()">{{ 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div wire:ignore.self class="modal fade " id="quickView" tabindex="-1" role="dialog" aria-labelledby="quickView"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="quickView">
                        {{ 'Quick Template View' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            @if ($selectedItem)
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name"
                                                class="fw-bold text-muted">{{ 'Name' }}</label>
                                            <div class="p-2 border rounded-3 bg-light">
                                                <p class="mb-0">{{ $selectedItem->name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="content"
                                                class="fw-bold text-muted">{{ 'Content' }}</label>
                                            <div class="p-2 border rounded-3 bg-light">
                                                <p class="mb-0">{{ strip_tags($selectedItem->content) }}</p>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ 'Cancel' }}</button>
                    </div>
                </form>
            </div>
        </div>


    </div>
</div>



<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('deleteTemp', id);
            }
        })
    }


    let editorInstance;
    document.addEventListener('livewire:load', function() {
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                editorInstance = editor;
                editor.model.document.on('change:data', () => {
                    const content = editor.getData();
                    @this.set('content', content, true);
                });
            })
            .catch(error => {
                console.error(error);
            });
    });


    document.addEventListener('pageReload', () => {
        window.location.reload();
    });
</script>
