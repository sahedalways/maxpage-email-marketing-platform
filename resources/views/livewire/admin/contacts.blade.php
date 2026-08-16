<div wire:poll.10000ms="pollFetchResult">
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
                        <div class="col-md-6">
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
                    </div>

                    <hr class="my-3 text-dark opacity-2">

                    <div class="d-flex flex-wrap align-items-start gap-2 pt-2">
                        <div class="me-auto">
                            @if ($syncInProgress)
                                <div class="sync-status-box d-flex align-items-center gap-3 px-3 py-2 rounded-3 bg-gradient-info text-white shadow-sm">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    <div class="text-sm">
                                        <span class="text-uppercase text-xs opacity-8 fw-600 d-block">{{ 'Syncing' }}</span>
                                        <span>{{ 'Fetching contacts from the APIs...' }}</span>
                                    </div>
                                </div>
                            @elseif ($lastSync)
                                <div class="sync-status-box d-flex align-items-center gap-3 px-3 py-2 rounded-3 border shadow-sm {{ $lastSync['success'] ? 'border-success' : 'border-danger' }}">
                                    <span class="avatar avatar-sm bg-gradient-{{ $lastSync['success'] ? 'success' : 'danger' }} text-white d-flex align-items-center justify-content-center shadow-sm">
                                        <i class="fas {{ $lastSync['success'] ? 'fa-check' : 'fa-exclamation' }} text-white"></i>
                                    </span>
                                    <div>
                                        <span class="text-xs text-uppercase fw-600 d-block {{ $lastSync['success'] ? 'text-success' : 'text-danger' }}">
                                            {{ $lastSync['success'] ? 'Last sync' : 'Sync failed' }}
                                        </span>
                                        <div class="text-sm d-flex flex-wrap align-items-center gap-2 mt-1">
                                            <span class="text-dark font-weight-bold">{{ now()->parse($lastSync['finished_at'])->format('M d, Y h:i A') }}</span>
                                            @if ($lastSync['success'])
                                                @foreach ($lastSync['summary'] as $source => $stats)
                                                    <span class="badge badge-sm rounded-3 px-2 py-1 {{ $stats['inserted'] > 0 ? 'bg-gradient-success text-white' : 'bg-light text-dark' }}">
                                                        {{ $source }}: +{{ $stats['inserted'] }} / {{ $stats['skipped'] }} skipped
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-danger text-xs">{{ $lastSync['error'] ?? 'Unknown error' }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                            <button type="button" class="btn btn-success" wire:click="fetchContacts"
                                wire:loading.attr="disabled" wire:target="fetchContacts">
                                <i class="fas fa-sync-alt me-1"></i>
                                <span wire:loading.remove wire:target="fetchContacts">{{ 'Fetch Contacts' }}</span>
                                <span wire:loading wire:target="fetchContacts">
                                    <span class="spinner-border spinner-border-sm me-1"></span>{{ 'Fetching...' }}
                                </span>
                            </button>
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
                                            @if ($contact->email)
                                                <a href="#" class="text-dark text-sm d-inline-block text-decoration-none copy-value"
                                                    title="{{ 'Click to copy email' }}"
                                                    onclick="return copyText('{{ $contact->email }}', 'email')">
                                                    {{ $contact->email }}
                                                </a>
                                            @else
                                                <p class="text-sm mb-0">N/A</p>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($contact->phone)
                                                <a href="#" class="text-dark text-sm d-inline-block text-decoration-none copy-value"
                                                    title="{{ 'Click to copy phone' }}"
                                                    onclick="return copyText('{{ $contact->phone }}', 'phone number')">
                                                    {{ $contact->phone }}
                                                </a>
                                            @else
                                                <p class="text-sm mb-0">N/A</p>
                                            @endif
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
                        <div class="d-flex justify-content-center px-3 pt-5 pb-3 pagination-responsive">
                            {{ $contacts->links('vendor.pagination.maxpage') }}
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

    <style>
        .copy-value {
            cursor: pointer;
            border-bottom: 1px dashed #c7d6e0;
            transition: color .15s ease;
        }

        .copy-value:hover {
            color: #5e72e4 !important;
        }

        .pagination {
            gap: 10px;
        }

        .pagination .page-link {
            min-width: 38px;
            text-align: center;
            font-size: 0.875rem;
            font-weight: 600;
            color: #67748e;
            background: #fff;
            border: 1px solid #e9edf4;
            border-radius: 0.625rem;
            box-shadow: 0 4px 8px rgba(15, 23, 42, 0.06);
            padding: 0.5rem 0.75rem;
            transition: all .2s ease;
        }

        .pagination .page-link:hover {
            color: #fff;
            background: #05ABD3;
            border-color: #05ABD3;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(5, 171, 211, 0.35);
        }

        .pagination .page-item.active .page-link {
            color: #fff;
            background: linear-gradient(310deg, #05ABD3 0%, #825ee4 100%);
            border-color: transparent;
            box-shadow: 0 6px 12px rgba(5, 171, 211, 0.35);
        }

        .pagination .page-item.disabled .page-link {
            color: #cbd5e1;
            background: #f8fafc;
            border-color: #e9edf4;
            box-shadow: none;
            transform: none;
        }
    </style>

    <script>
        function copyText(text, label) {
            const fallback = () => {
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                try {
                    document.execCommand('copy');
                    toastr.success(label.charAt(0).toUpperCase() + label.slice(1) + ' copied.');
                } catch (e) {
                    toastr.error('Could not copy ' + label + '.');
                }
                document.body.removeChild(ta);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    toastr.success(label.charAt(0).toUpperCase() + label.slice(1) + ' copied.');
                }).catch(() => fallback());
            } else {
                fallback();
            }

            return false;
        }

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
