<div class="dropdown mb-4">
    @if ($criteria === 'email')
        <label for="recipientEmail" class="form-label">Recipient Email <span
                class="text-danger">*</span> <i
                class="fas fa-question-circle text-primary"
                data-bs-toggle="tooltip" data-bs-placement="top"
                title="Provide a valid email address."></i></label>
    @else
        <label for="recipientPhoneNo" class="form-label">Recipient Phone Number <span
                class="text-danger">*</span> <i
                class="fas fa-question-circle text-primary"
                data-bs-toggle="tooltip" data-bs-placement="top"
                title="Provide a valid phone number."></i></label>
    @endif

    <div class="recipient-add-row">
        <input type="text" class="form-control"
            placeholder="{{ $criteria === 'email' ? 'Enter recipient email' : 'Enter recipient phone number' }}"
            wire:model.live.debounce.300ms="contactSearch">

        <button class="btn btn-primary mt-2 ms-3"
            wire:click.prevent="onChangeSearchField">
            Add Recipient
        </button>
    </div>

    @if ($errorMessage)
        <span class="text-danger">{{ $errorMessage }}</span>
    @endif
</div>

@if ($filteredContacts && count($filteredContacts) > 0)
    @forelse ($filteredContacts as $contact)
        <div class="dropdown-menu show custom-dropdown-menu shadow-lg rounded w-auto"
            style="max-height: 200px; overflow-y: auto;">
            @if ($mode === 'single')
                <a class="dropdown-item custom-dropdown-item" href="#"
                    wire:click.prevent="addSingleContact('{{ $contact['email'] }}')">
            @else
                <a class="dropdown-item custom-dropdown-item" href="#"
                    wire:click.prevent="addGroupContact('{{ $contact['email'] }}')">
            @endif
                {{ $contact['name'] }}
                - ({{ $criteria === 'email' ? $contact['email'] : $contact['phone'] }})
                @if (!empty($contact['user_type']))
                    <span class="badge badge-sm text-uppercase text-white ms-2 {{ $contact['user_type'] == 'customer' ? 'bg-success' : 'bg-info' }}">{{ ucfirst($contact['user_type']) }}</span>
                @endif
            </a>
        </div>
    @empty
    @endforelse
@endif

@if ($selectedRecipients)
    <div class="mt-2">
        <h6>Selected Recipients:</h6>
        <ul class="list-group">
            @foreach ($selectedRecipients as $id => $item)
                <li class="list-group-item d-flex justify-content-between align-items-center mb-3">
                    @if ($criteria === 'email')
                        {{ $item['email'] }}
                        <button class="btn btn-danger btn-sm"
                            wire:click.prevent="removeContact('{{ $item['email'] }}')">Remove</button>
                    @else
                        {{ $item['phone'] }}
                        <button class="btn btn-danger btn-sm"
                            wire:click.prevent="removeContact('{{ $item['phone'] }}')">Remove</button>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif

@error('selectedRecipients')
    <span class="text-danger">{{ $message }}</span>
@enderror
