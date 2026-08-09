<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white mb-0">{{ 'Contacts' }}</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label for="contactSearch" class="form-label">
                                <i class="fas fa-search"></i>
                                {{ 'Search' }}
                            </label>
                            <input type="text" id="contactSearch" class="form-control"
                                placeholder="{{ 'Search by name, email or phone' }}" wire:model.debounce.300ms="search">
                        </div>

                        <div class="col-md-3">
                            <label for="contactSource" class="form-label">
                                <i class="fas fa-filter"></i>
                                {{ 'Source' }}
                            </label>
                            <select id="contactSource" class="form-select" wire:model="source">
                                <option value="">{{ 'All Sources' }}</option>
                                @foreach ($sources as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="contactUserType" class="form-label">
                                <i class="fas fa-user-tag"></i>
                                {{ 'User Type' }}
                            </label>
                            <select id="contactUserType" class="form-select" wire:model="userType">
                                <option value="">{{ 'All User Types' }}</option>
                                @foreach ($userTypes as $item)
                                    <option value="{{ $item }}">{{ ucfirst($item) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#contactModal" wire:click="openAdd">
                                <i class="fas fa-plus me-1"></i> {{ 'Add Contact' }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">{{ 'Name' }}</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">{{ 'Email' }}</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">{{ 'Phone' }}</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">{{ 'Source' }}</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">{{ 'User Type' }}</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">{{ 'Added' }}</th>
                                    <th class="text-secondary opacity-7">{{ 'Actions' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($contacts as $contact)
                                    <tr>
                                        <td>
                                            <p class="text-sm px-3 mb-0">{{ $contacts->firstItem() + $loop->index }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $contact->name ?? 'N/A' }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $contact->email ?? 'N/A' }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $contact->phone ?? 'N/A' }}</p>
                                        </td>
                                        <td>
                                            @if ($contact->source)
                                                <span class="badge badge-sm badge-primary bg-gradient-primary text-uppercase text-white">
                                                    {{ $contact->source }}
                                                </span>
                                            @else
                                                <span class="text-muted text-sm">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($contact->user_type)
                                                <span class="badge badge-sm text-uppercase text-white {{ $contact->user_type == 'customer' ? 'bg-success' : 'bg-info' }}">
                                                    {{ ucfirst($contact->user_type) }}
                                                </span>
                                            @else
                                                <span class="text-muted text-sm">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $contact->created_at->format('M d, Y h:i A') }}</p>
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="#" class="badge badge-xs badge-info text-xs fw-600"
                                                data-bs-toggle="modal" data-bs-target="#contactModal"
                                                wire:click="openEdit({{ $contact->id }})">
                                                <i class="fas fa-edit me-1"></i>{{ 'Edit' }}
                                            </a>
                                            <a href="#" class="ms-1 badge badge-xs badge-danger text-xs fw-600"
                                                onclick="confirmDelete({{ $contact->id }})">
                                                <i class="fas fa-trash-alt me-1"></i>{{ 'Delete' }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="empty-list"
                                                style="border: 1px dashed #c7d6e0; border-radius: 16px; padding: 50px 20px; margin: 15px; background: linear-gradient(135deg, #f8fafd 0%, #eef4f8 100%);">
                                                <i class="fas fa-address-book text-primary"
                                                    style="font-size: 44px; opacity: .6;"></i>
                                                <div style="margin-top: 15px; font-size: 20px; font-weight: 700; color: #344767;">
                                                    {{ 'No contacts found' }}
                                                </div>
                                                <div style="color: #67748e; font-size: 15px; margin-top: 4px;">
                                                    {{ 'Add a contact or adjust your search filters' }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($contacts->hasPages())
                        <div class="d-flex justify-content-center p-3">
                            {{ $contacts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="contactModal" tabindex="-1" role="dialog"
        aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="contactModalLabel">
                        {{ $editingId ? 'Edit Contact' : 'Add New Contact' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="contactName" class="form-label">{{ 'Name' }}</label>
                            <input type="text" id="contactName" class="form-control"
                                placeholder="{{ 'Enter contact name' }}" wire:model.defer="name">
                        </div>

                        <div class="mb-3">
                            <label for="contactEmail" class="form-label">{{ 'Email' }}</label>
                            <input type="email" id="contactEmail" class="form-control"
                                placeholder="{{ 'Enter email address' }}" wire:model.defer="email">
                            @error('email')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contactPhone" class="form-label">{{ 'Phone Number' }}</label>
                            <input type="text" id="contactPhone" class="form-control"
                                placeholder="{{ 'Enter phone number' }}" wire:model.defer="phone">
                            @error('phone')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contactSourceField" class="form-label">{{ 'Source' }}</label>
                            <input type="text" id="contactSourceField" class="form-control"
                                placeholder="{{ 'e.g. import, newsletter, manual' }}" wire:model.defer="contactSource"
                                list="sourceOptions">
                            <datalist id="sourceOptions">
                                @foreach ($sources as $item)
                                    <option value="{{ $item }}"></option>
                                @endforeach
                                <option value="manual"></option>
                                <option value="import"></option>
                                <option value="newsletter"></option>
                                <option value="website"></option>
                            </datalist>
                            <small class="text-muted">{{ 'You can pick an existing source or type a new one.' }}</small>
                        </div>

                        <div class="mb-3">
                            <label for="contactUserTypeField" class="form-label">{{ 'User Type' }}</label>
                            <input type="text" id="contactUserTypeField" class="form-control"
                                placeholder="{{ 'e.g. customer, affiliate' }}" wire:model.defer="contactUserType"
                                list="userTypeOptions">
                            <datalist id="userTypeOptions">
                                @foreach ($userTypes as $item)
                                    <option value="{{ $item }}"></option>
                                @endforeach
                                <option value="customer"></option>
                                <option value="affiliate"></option>
                            </datalist>
                            <small class="text-muted">{{ 'You can pick an existing user type or type a new one.' }}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                            wire:target="save">
                            <span wire:loading.remove wire:target="save">
                                {{ $editingId ? 'Update Contact' : 'Add Contact' }}
                            </span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </form>
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
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('delete', id)
                }
            })
        }

        document.addEventListener('livewire:load', function() {
            Livewire.on('contactModalClose', () => {
                const el = document.getElementById('contactModal');
                const modal = el ? bootstrap.Modal.getInstance(el) : null;
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
</div>
