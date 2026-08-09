<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">
                @if ($selectedCriteria === 'email')
                    {{ 'Send Email ' }}
                @elseif($selectedCriteria === 'sms')
                    {{ 'Send SMS' }}
                @elseif($selectedCriteria === 'whatsapp')
                    {{ 'Send Whatsapp' }}
                @endif
            </h5>

            <div class="col-12 d-flex justify-content-center my-4">
                <div class="msg-criteria-tabs d-flex" role="tablist">
                    <button wire:click="selectCriteria('email')" type="button"
                        class="msg-tab {{ $selectedCriteria === 'email' ? 'active' : '' }}">
                        <i class="fas fa-envelope me-2"></i> Email
                    </button>

                    <button wire:click="selectCriteria('sms')" type="button"
                        class="msg-tab {{ $selectedCriteria === 'sms' ? 'active' : '' }}">
                        <i class="fas fa-sms me-2"></i> SMS
                    </button>

                    <button wire:click="selectCriteria('whatsapp')" type="button"
                        class="msg-tab {{ $selectedCriteria === 'whatsapp' ? 'active' : '' }}">
                        <i class="fab fa-whatsapp me-2 text-success"></i> WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="btn-group" role="group" aria-label="Audience Selection">
                                <button type="button" wire:click="selectAudience('single')"
                                    class="btn btn-outline-primary {{ $selectedAudience === 'single' ? 'active text-white' : '' }}">
                                    <i class="fas fa-user me-2"></i> Single Audience
                                </button>

                                <button type="button" wire:click="selectAudience('group')"
                                    class="btn btn-outline-primary ms-3 {{ $selectedAudience === 'group' ? 'active text-white' : '' }}">
                                    <i class="fas fa-users me-2 "></i> Group Audience
                                </button>

                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label for="sendUserType" class="form-label">
                                {{ 'User Type' }}
                            </label>
                            <select id="sendUserType" class="form-select" wire:model="userTypeFilter">
                                <option value="">{{ 'All User Types' }}</option>
                                @foreach ($userTypes as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div class="card-body p-0">

                        @if ($selectedCriteria === 'email')
                            <div class="table-responsive" style="max-width: 900px;">

                                @if ($selectedAudience === 'single')
                                    <form class="ms-4">
                                        <div class="dropdown mb-4">



                                            <div class="dropdown mb-4">
                                                <label for="recipientEmail" class="form-label">Recipient Email <span
                                                        class="text-danger">*</span> <i
                                                        class="fas fa-question-circle text-primary"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Provide a valid email address."></i></label>

                                                <div class="d-flex">
                                                    <input type="text" class="form-control" flex-grow-1
                                                        placeholder="Enter recipient email"
                                                        wire:model.live.debounce.300ms="contactSearch">

                                                    <button class="btn btn-primary mt-2 ms-3 w-20" flex-shrink-0
                                                        wire:click.prevent="onChangeSearchField">
                                                        Add Recipient
                                                    </button>
                                                </div>


                                                @if ($errorMessage)
                                                    <span class="text-danger">{{ $errorMessage }}</span>
                                                @endif
                                            </div>

                                            @if ($filteredContacts)
                                                @if (count($filteredContacts) > 0)
                                                    @forelse ($filteredContacts as $contact)
                                                        <div class="dropdown-menu show custom-dropdown-menu shadow-lg rounded w-auto"
                                                            style="max-height: 200px; overflow-y: auto;">
                                                            <a class="dropdown-item custom-dropdown-item" href="#"
                                                                wire:click.prevent="addSingleContact('{{ $contact['email'] }}')">
                                                                {{ $contact['name'] }} - ({{ $contact['email'] }})
                                                                @if (!empty($contact['user_type']))
                                                                    <span class="badge badge-sm text-uppercase text-white ms-2 {{ $contact['user_type'] == 'customer' ? 'bg-success' : 'bg-info' }}">{{ ucfirst($contact['user_type']) }}</span>
                                                                @endif
                                                            </a>
                                                        </div>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            @endif


                                            <!-- Selected contact -->
                                            @if ($selectedRecipients)
                                                <div class="mt-2">
                                                    <h6>Selected Recipients:</h6>
                                                    <ul class="list-group">

                                                        @foreach ($selectedRecipients as $id => $item)
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center mb-3">

                                                                {{ $item['email'] }}
                                                                <button class="btn btn-danger btn-sm"
                                                                    wire:click.prevent="removeContact('{{ $item['email'] }}')">Remove</button>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            @error('selectedRecipients')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror


                                            {{-- <div class="mb-4">
                                                <div class="mb-4 position-relative">
                                                    <label for="scheduleAt" class="form-label">
                                                        Schedule At (Date & Time)
                                                        <i class="fas fa-question-circle text-primary"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="If left empty, the message will be sent instantly."></i>
                                                    </label>

                                                    <input type="text" id="scheduleAt" class="form-control"
                                                        placeholder="Select Date & Time" wire:model.defer="schedule_at">

                                                    @error('schedule_at')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                            </div> --}}


                                            <div class="mb-4">
                                                <label for="subject" class="form-label">Subject <span
                                                        class="text-danger">*</span> <i
                                                        class="fas fa-question-circle text-primary"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Provide a subject for the mail, so it does not go into spam."></i></label>
                                                <input type="text" id="subject" class="form-control"
                                                    placeholder="Enter subject" wire:model.defer="subject">
                                                @error('subject')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-3">
                                                <div class="mb-4">
                                                    <label for="template" class="form-label">Select Template</label>
                                                    <select id="template" class="form-select"
                                                        wire:model="selectedTemplate"
                                                        wire:change="loadTemplateContent">
                                                        <option value="" hidden>-- Select Template --</option>
                                                        @foreach ($templates as $template)
                                                            <option value="{{ $template->id }}">{{ $template->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-0">
                                                @if (!$isSelectTemp)
                                                    <div class="col-md-9">
                                                        <div class="mb-4 custom-textarea" wire:ignore>
                                                            <label for="content" class="form-label">Message <span
                                                                    class="text-danger">*</span></label>
                                                            <textarea id="editor" rows="10" class="form-control">{{ $content }}</textarea>
                                                        </div>
                                                        <input type="hidden" wire:model.defer="content"
                                                            id="livewire-content">
                                                    </div>
                                                @endif
                                            </div>


                                            <button type="submit" wire:click.prevent="sendEmailMessage"
                                                class="btn btn-primary" wire:loading.attr="disabled"
                                                wire:target="sendEmailMessage">
                                                <span wire:loading.remove wire:target="sendEmailMessage">Send</span>
                                                <span wire:loading wire:target="sendEmailMessage">Loading...</span>
                                            </button>
                                    </form>
                                @elseif($selectedAudience === 'group')
                                    <form wire:submit.prevent="sendEmail" class="ms-4">


                                        <div class="dropdown mb-4">
                                            <label for="recipientEmail" class="form-label">Recipient Email <span
                                                    class="text-danger">*</span> <i
                                                    class="fas fa-question-circle text-primary"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Provide a valid email address."></i></label>

                                            <div class="d-flex">
                                                <input type="text" class="form-control" flex-grow-1
                                                    placeholder="Enter recipient email"
                                                    wire:model.live.debounce.300ms="contactSearch">

                                                <button class="btn btn-primary mt-2 ms-3 w-20" flex-shrink-0
                                                    wire:click.prevent="onChangeSearchField">
                                                    Add Recipient
                                                </button>
                                            </div>
                                            @if ($errorMessage)
                                                <span class="text-danger">{{ $errorMessage }}</span>
                                            @endif


                                        </div>
                                        @if ($filteredContacts)
                                            @if (count($filteredContacts) > 0)
                                                @forelse ($filteredContacts as $contact)
                                                    <div class="dropdown-menu show custom-dropdown-menu shadow-lg rounded w-auto"
                                                        style="max-height: 200px; overflow-y: auto;"
                                                        x-show="$wire.filteredContacts.length > 0">
                                                        <a class="dropdown-item" href="#"
                                                            wire:click.prevent="addGroupContact('{{ $contact['email'] }}')">
                                                            {{ $contact['name'] }}
                                                            -
                                                            ({{ $contact['email'] }})
                                                            @if (!empty($contact['user_type']))
                                                                <span class="badge badge-sm text-uppercase text-white ms-2 {{ $contact['user_type'] == 'customer' ? 'bg-success' : 'bg-info' }}">{{ ucfirst($contact['user_type']) }}</span>
                                                            @endif
                                                        </a>
                                                    </div>
                                                @empty
                                                @endforelse
                                            @endif

                                        @endif

                                        <!-- Selected contact -->
                                        @if ($selectedRecipients)
                                            <div class="mt-2">
                                                <h6>Selected Recipients:</h6>
                                                <ul class="list-group">

                                                    @foreach ($selectedRecipients as $id => $item)
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-center mb-3">
                                                            {{ $item['email'] }}
                                                            <button class="btn btn-danger btn-sm"
                                                                wire:click.prevent="removeContact('{{ $item['email'] }}')">Remove</button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @error('selectedRecipients')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror



                                        {{-- <div class="mb-4">
                                            <label for="scheduleAt" class="form-label">Schedule At (Date & Time) <i
                                                    class="fas fa-question-circle text-primary"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="If left empty, the message will be sent instantly."></i></label>
                                            <input type="text" id="scheduleAt" class="form-control"
                                                placeholder="Select Date & Time" wire:model.defer="schedule_at">
                                            @error('schedule_at')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </div> --}}


                                        <div class="mb-4">
                                            <label for="subject" class="form-label">Subject <span
                                                    class="text-danger">*</span> <i
                                                    class="fas fa-question-circle text-primary"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Provide a subject for the mail, so it does not go into spam."></i></label>
                                            <input type="text" id="subject" class="form-control"
                                                placeholder="Enter subject" wire:model.defer="subject">
                                            @error('subject')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>



                                        <div class="col-md-3">
                                            <div class="mb-4">
                                                <label for="template" class="form-label">Select Template </label>
                                                <select id="template" class="form-select"
                                                    wire:model="selectedTemplate" wire:change="loadTemplateContent">
                                                    <option value="" hidden>-- Select Template --</option>
                                                    @foreach ($templates as $template)
                                                        <option value="{{ $template->id }}">{{ $template->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="row g-0">
                                            @if (!$isSelectTemp)
                                                <div class="col-md-9" wire:ignore>
                                                    <div class="mb-4 custom-textarea">
                                                        <label for="content" class="form-label">Message <span
                                                                class="text-danger">*</span></label>
                                                        <textarea id="editor" wire:model.defer="content" class="form-control" hidden></textarea>

                                                    </div>
                                                </div>
                                            @endif



                                        </div>


                                        <button type="submit" wire:click.prevent="sendEmailMessage"
                                            class="btn btn-primary" wire:loading.attr="disabled"
                                            wire:target="sendEmailMessage">
                                            <span wire:loading.remove wire:target="sendEmailMessage">Send</span>
                                            <span wire:loading wire:target="sendEmailMessage">Loading...</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @elseif($selectedCriteria === 'sms')
                            <div class="table-responsive" style="max-width: 900px;">

                                @if ($selectedAudience === 'single')
                                    <form wire:submit.prevent="sendEmail" class="ms-4">

                                        <div class="dropdown mb-4">
                                            <label for="recipientPhoneNo" class="form-label">Recipient Phone Number <span
                                                        class="text-danger">*</span> <i
                                                    class="fas fa-question-circle text-primary"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Provide a valid phone number."></i></label>


                                            <div class="d-flex">
                                                <input type="text" class="form-control" flex-grow-1
                                                    placeholder="Enter recipient phone number"
                                                    wire:model.live.debounce.300ms="contactSearch">

                                                <button class="btn btn-primary mt-2 ms-3 w-20" flex-shrink-0
                                                    wire:click.prevent="onChangeSearchField">
                                                    Add Recipient
                                                </button>
                                            </div>
                                            @if ($errorMessage)
                                                <span class="text-danger">{{ $errorMessage }}</span>
                                            @endif




                                        </div>


                                        @if ($filteredContacts)
                                            @if (count($filteredContacts) > 0)
                                                @forelse ($filteredContacts as $contact)
                                                    <div class="dropdown-menu show custom-dropdown-menu shadow-lg rounded w-auto"
                                                        style="max-height: 200px; overflow-y: auto;"
                                                        x-show="$wire.filteredContacts.length > 0">
                                                        <a class="dropdown-item" href="#"
                                                            wire:click.prevent="addSingleContact('{{ $contact['email'] }}')">
                                                            {{ $contact['name'] }}
                                                            -
                                                            ({{ $contact['phone'] }})
                                                            @if (!empty($contact['user_type']))
                                                                <span class="badge badge-sm text-uppercase text-white ms-2 {{ $contact['user_type'] == 'customer' ? 'bg-success' : 'bg-info' }}">{{ ucfirst($contact['user_type']) }}</span>
                                                            @endif
                                                        </a>
                                                    </div>
                                                @empty
                                                @endforelse
                                            @endif

                                        @endif

                                        <!-- Selected contact -->
                                        @if ($selectedRecipients)
                                            <div class="mt-2">
                                                <h6>Selected Recipients:</h6>
                                                <ul class="list-group">

                                                    @foreach ($selectedRecipients as $id => $item)
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-center mb-3">

                                                            {{ $item['phone'] }}
                                                            <button class="btn btn-danger btn-sm"
                                                                wire:click.prevent="removeContact('{{ $item['phone'] }}')">Remove</button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @error('selectedRecipients')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror


                                        {{-- <div class="mb-4">
                                            <div class="mb-4 position-relative">
                                                <label for="scheduleAt" class="form-label">
                                                    Schedule At (Date & Time)
                                                    <i class="fas fa-question-circle text-primary"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="If left empty, the message will be sent instantly."></i>
                                                </label>

                                                <input type="text" id="scheduleAt" class="form-control"
                                                    placeholder="Select Date & Time" wire:model.defer="schedule_at">

                                                @error('schedule_at')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div> --}}


                                        <div class="row g-0">
                                            @if ($isSelectTemp === false)
                                                <div class="col-md-9">
                                                    <div class="mb-4 custom-textarea">
                                                        <label for="content" class="form-label">Message <span class="text-danger">*</span></label>
                                                        <textarea id="content" wire:model="content" class="form-control" placeholder="Type your message here..."></textarea>

                                                    </div>
                                                </div>
                                            @endif


                                            {{--                                            <div class="col-md-3"> --}}
                                            {{--                                                <div class="mb-4"> --}}
                                            {{--                                                    <label for="template" class="form-label">Select Template</label> --}}
                                            {{--                                                    <select id="template" class="form-select" --}}
                                            {{--                                                        wire:model="selectedTemplate" --}}
                                            {{--                                                        wire:change="loadTemplateContent"> --}}
                                            {{--                                                        <option value="">-- Select Template --</option> --}}
                                            {{--                                                        @foreach ($templates as $template) --}}
                                            {{--                                                            <option value="{{ $template->id }}">{{ $template->name }} --}}
                                            {{--                                                            </option> --}}
                                            {{--                                                        @endforeach --}}
                                            {{--                                                    </select> --}}
                                            {{--                                                </div> --}}
                                            {{--                                            </div> --}}
                                        </div>


                                        <button type="submit" wire:click.prevent="sendSmsMessage"
                                            class="btn btn-primary" wire:loading.attr="disabled"
                                            wire:target="sendSmsMessage">
                                            <span wire:loading.remove wire:target="sendSmsMessage">Send</span>
                                            <span wire:loading wire:target="sendSmsMessage">Loading...</span>
                                        </button>

                                    </form>
                                @elseif($selectedAudience === 'group')
                                    <form wire:submit.prevent="sendEmail" class="ms-4">


                                        <div class="dropdown mb-4">
                                            <label for="recipientPhoneNo" class="form-label">Recipient Phone Number <span
                                                        class="text-danger">*</span> <i
                                                    class="fas fa-question-circle text-primary"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Provide a valid email address."></i></label>

                                            <div class="d-flex">
                                                <input type="text" class="form-control" flex-grow-1
                                                    placeholder="Enter recipient phone number"
                                                    wire:model.live.debounce.300ms="contactSearch">

                                                <button class="btn btn-primary mt-2 ms-3 w-20" flex-shrink-0
                                                    wire:click.prevent="onChangeSearchField">
                                                    Add Recipient
                                                </button>
                                            </div>
                                            @if ($errorMessage)
                                                <span class="text-danger">{{ $errorMessage }}</span>
                                            @endif



                                        </div>


                                        @if ($filteredContacts)
                                            @if (count($filteredContacts) > 0)
                                                @forelse ($filteredContacts as $contact)
                                                    <div class="dropdown-menu show custom-dropdown-menu shadow-lg rounded w-auto"
                                                        style="max-height: 200px; overflow-y: auto;"
                                                        x-show="$wire.filteredContacts.length > 0">
                                                        <a class="dropdown-item" href="#"
                                                            wire:click.prevent="addGroupContact('{{ $contact['email'] }}')">
                                                            {{ $contact['name'] }}
                                                            -
                                                            ({{ $contact['phone'] }})
                                                            @if (!empty($contact['user_type']))
                                                                <span class="badge badge-sm text-uppercase text-white ms-2 {{ $contact['user_type'] == 'customer' ? 'bg-success' : 'bg-info' }}">{{ ucfirst($contact['user_type']) }}</span>
                                                            @endif
                                                        </a>
                                                    </div>
                                                @empty
                                                @endforelse
                                            @endif

                                        @endif

                                        <!-- Selected contact -->
                                        @if ($selectedRecipients)
                                            <div class="mt-2">
                                                <h6>Selected Recipients:</h6>
                                                <ul class="list-group">

                                                    @foreach ($selectedRecipients as $id => $item)
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-center mb-3">

                                                            {{ $item['phone'] }}
                                                            <button class="btn btn-danger btn-sm"
                                                                wire:click.prevent="removeContact('{{ $item['phone'] }}')">Remove</button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @error('selectedRecipients')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror



                                        {{-- <div class="mb-4">
                                            <label for="scheduleAt" class="form-label">Schedule At (Date & Time) <i
                                                    class="fas fa-question-circle text-primary"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="If left empty, the message will be sent instantly."></i></label>
                                            <input type="text" id="scheduleAt" class="form-control"
                                                placeholder="Select Date & Time" wire:model.defer="schedule_at">
                                            @error('schedule_at')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </div> --}}




                                        <div class="row g-0">
                                            @if ($isSelectTemp === false)
                                                <div class="col-md-9">
                                                    <div class="mb-4">
                                                        <label for="content" class="form-label">Message <span
                                                                class="text-danger">*</span></label>
                                                        <textarea id="content" wire:model="content" class="form-control" placeholder="Type your message here..."></textarea>

                                                    </div>
                                                </div>
                                            @endif


                                            {{--                                            <div class="col-md-3"> --}}
                                            {{--                                                <div class="mb-4"> --}}
                                            {{--                                                    <label for="template" class="form-label">Select Template</label> --}}
                                            {{--                                                    <select id="template" class="form-select" --}}
                                            {{--                                                        wire:model="selectedTemplate" --}}
                                            {{--                                                        wire:change="loadTemplateContent"> --}}
                                            {{--                                                        <option value="">-- Select Template --</option> --}}
                                            {{--                                                        @foreach ($templates as $template) --}}
                                            {{--                                                            <option value="{{ $template->id }}">{{ $template->name }} --}}
                                            {{--                                                            </option> --}}
                                            {{--                                                        @endforeach --}}
                                            {{--                                                    </select> --}}
                                            {{--                                                </div> --}}
                                            {{--                                            </div> --}}
                                        </div>


                                        <button type="submit" wire:click.prevent="sendSmsMessage"
                                            class="btn btn-primary" wire:loading.attr="disabled"
                                            wire:target="sendSmsMessage">
                                            <span wire:loading.remove wire:target="sendSmsMessage">Send</span>
                                            <span wire:loading wire:target="sendSmsMessage">Loading...</span>
                                        </button>

                                    </form>
                                @endif
                            </div>
                        @elseif($selectedCriteria === 'whatsapp')
                            <div class="table-responsive" style="max-width: 900px;">

                                @if ($selectedAudience === 'single')
                                    <form wire:submit.prevent="sendEmail" class="ms-4">

                                        <div class="dropdown mb-4">
                                            <label for="recipientPhoneNo" class="form-label">Recipient Phone Number <span
                                                        class="text-danger">*</span> <i
                                                    class="fas fa-question-circle text-primary"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Provide a valid phone number."></i></label>



                                            <div class="d-flex">
                                                <input type="text" class="form-control" flex-grow-1
                                                    placeholder="Enter recipient phone number"
                                                    wire:model.live.debounce.300ms="contactSearch">

                                                <button class="btn btn-primary mt-2 ms-3 w-20" flex-shrink-0
                                                    wire:click.prevent="onChangeSearchField">
                                                    Add Recipient
                                                </button>
                                            </div>
                                            @if ($errorMessage)
                                                <span class="text-danger">{{ $errorMessage }}</span>
                                            @endif




                                        </div>


                                        @if ($filteredContacts)
                                            @if (count($filteredContacts) > 0)
                                                @forelse ($filteredContacts as $contact)
                                                    <div class="dropdown-menu show custom-dropdown-menu shadow-lg rounded w-auto"
                                                        style="max-height: 200px; overflow-y: auto;"
                                                        x-show="$wire.filteredContacts.length > 0">
                                                        <a class="dropdown-item" href="#"
                                                            wire:click.prevent="addSingleContact('{{ $contact['email'] }}')">
                                                            {{ $contact['name'] }}
                                                            -
                                                            ({{ $contact['phone'] }})
                                                            @if (!empty($contact['user_type']))
                                                                <span class="badge badge-sm text-uppercase text-white ms-2 {{ $contact['user_type'] == 'customer' ? 'bg-success' : 'bg-info' }}">{{ ucfirst($contact['user_type']) }}</span>
                                                            @endif
                                                        </a>
                                                    </div>
                                                @empty
                                                @endforelse
                                            @endif

                                        @endif

                                        <!-- Selected contact -->
                                        @if ($selectedRecipients)
                                            <div class="mt-2">
                                                <h6>Selected Recipients:</h6>
                                                <ul class="list-group">

                                                    @foreach ($selectedRecipients as $id => $item)
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-center mb-3">

                                                            {{ $item['phone'] }}
                                                            <button class="btn btn-danger btn-sm"
                                                                wire:click.prevent="removeContact('{{ $item['phone'] }}')">Remove</button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @error('selectedRecipients')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror


                                        {{-- <div class="mb-4">
                                            <div class="mb-4 position-relative">
                                                <label for="scheduleAt" class="form-label">
                                                    Schedule At (Date & Time)
                                                    <i class="fas fa-question-circle text-primary"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="If left empty, the message will be sent instantly."></i>
                                                </label>

                                                <input type="text" id="scheduleAt" class="form-control"
                                                    placeholder="Select Date & Time" wire:model.defer="schedule_at">

                                                @error('schedule_at')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div> --}}


                                        <div class="row g-0">
                                            @if ($isSelectTemp === false)
                                                <div class="col-md-9">
                                                    <div class="mb-4 custom-textarea">
                                                        <label for="content" class="form-label">Message <span class="text-danger">*</span></label>
                                                        <textarea id="content" wire:model="content" class="form-control" placeholder="Type your message here..."></textarea>

                                                    </div>
                                                </div>
                                            @endif


                                            {{--                                            <div class="col-md-3"> --}}
                                            {{--                                                <div class="mb-4"> --}}
                                            {{--                                                    <label for="template" class="form-label">Select Template</label> --}}
                                            {{--                                                    <select id="template" class="form-select" --}}
                                            {{--                                                        wire:model="selectedTemplate" --}}
                                            {{--                                                        wire:change="loadTemplateContent"> --}}
                                            {{--                                                        <option value="">-- Select Template --</option> --}}
                                            {{--                                                        @foreach ($templates as $template) --}}
                                            {{--                                                            <option value="{{ $template->id }}">{{ $template->name }} --}}
                                            {{--                                                            </option> --}}
                                            {{--                                                        @endforeach --}}
                                            {{--                                                    </select> --}}
                                            {{--                                                </div> --}}
                                            {{--                                            </div> --}}
                                        </div>

                                        <button type="submit" wire:click.prevent="sendWhatsappMessage"
                                            class="btn btn-primary" wire:loading.attr="disabled"
                                            wire:target="sendWhatsappMessage">
                                            <span wire:loading.remove wire:target="sendWhatsappMessage">Send</span>
                                            <span wire:loading wire:target="sendWhatsappMessage">Loading...</span>
                                        </button>
                                    </form>
                                @elseif($selectedAudience === 'group')
                                    <form wire:submit.prevent="sendEmail" class="ms-4">


                                        <div class="dropdown mb-4">
                                            <label for="recipientPhoneNo" class="form-label">Recipient Phone Number <span
                                                        class="text-danger">*</span> <i
                                                    class="fas fa-question-circle text-primary"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Provide a valid email address."></i></label>

                                            <div class="d-flex">
                                                <input type="text" class="form-control" flex-grow-1
                                                    placeholder="Enter recipient phone number"
                                                    wire:model.live.debounce.300ms="contactSearch">

                                                <button class="btn btn-primary mt-2 ms-3 w-20" flex-shrink-0
                                                    wire:click.prevent="onChangeSearchField">
                                                    Add Recipient
                                                </button>
                                            </div>


                                            @if ($errorMessage)
                                                <span class="text-danger">{{ $errorMessage }}</span>
                                            @endif
                                        </div>

                                        @if ($filteredContacts)
                                            @if (count($filteredContacts) > 0)
                                                @forelse ($filteredContacts as $contact)
                                                    <div class="dropdown-menu show custom-dropdown-menu shadow-lg rounded w-auto w-auto"
                                                        style="max-height: 200px; overflow-y: auto;"
                                                        x-show="$wire.filteredContacts.length > 0">
                                                        <a class="dropdown-item" href="#"
                                                            wire:click.prevent="addGroupContact('{{ $contact['email'] }}')">
                                                            {{ $contact['name'] }}
                                                            -
                                                            ({{ $contact['phone'] }})
                                                            @if (!empty($contact['user_type']))
                                                                <span class="badge badge-sm text-uppercase text-white ms-2 {{ $contact['user_type'] == 'customer' ? 'bg-success' : 'bg-info' }}">{{ ucfirst($contact['user_type']) }}</span>
                                                            @endif
                                                        </a>
                                                    </div>
                                                @empty
                                                @endforelse
                                            @endif

                                        @endif

                                        <!-- Selected contact -->
                                        @if ($selectedRecipients)
                                            <div class="mt-2">
                                                <h6>Selected Recipients:</h6>
                                                <ul class="list-group">

                                                    @foreach ($selectedRecipients as $id => $item)
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-center mb-3">

                                                            {{ $item['phone'] }}
                                                            <button class="btn btn-danger btn-sm"
                                                                wire:click.prevent="removeContact('{{ $item['phone'] }}')">Remove</button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @error('selectedRecipients')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror



                                        {{-- <div class="mb-4">
                                            <label for="scheduleAt" class="form-label">Schedule At (Date & Time) <i
                                                    class="fas fa-question-circle text-primary"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="If left empty, the message will be sent instantly."></i></label>
                                            <input type="text" id="scheduleAt" class="form-control"
                                                placeholder="Select Date & Time" wire:model.defer="schedule_at">
                                            @error('schedule_at')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </div> --}}




                                        <div class="row g-0">
                                            @if ($isSelectTemp === false)
                                                <div class="col-md-9">
                                                    <div class="mb-4" wire:ignore>
                                                        <label for="content" class="form-label">Message <span
                                                                class="text-danger">*</span></label>
                                                        <textarea id="content" class="form-control"
                                                            placeholder="Type your message here...">{!! $content !!}</textarea>

                                                    </div>
                                                </div>
                                            @endif



                                        </div>


                                        <button type="submit" wire:click.prevent="sendWhatsappMessage"
                                            class="btn btn-primary" wire:loading.attr="disabled"
                                            wire:target="sendWhatsappMessage">
                                            <span wire:loading.remove wire:target="sendWhatsappMessage">Send</span>
                                            <span wire:loading wire:target="sendWhatsappMessage">Loading...</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        let editorInstance;

        function initializeEditor() {
            if (editorInstance) {
                editorInstance.destroy()
                    .then(() => {
                        createEditor();
                    });
            } else {
                createEditor();
            }
        }

        function createEditor() {
            ClassicEditor
                .create(document.querySelector('#editor'), {
                    placeholder: 'Write your message here...'
                })
                .then(editor => {
                    editorInstance = editor;

                    // Update Livewire property on change
                    editor.model.document.on('change:data', () => {
                        const hiddenInput = document.getElementById('livewire-content');
                        hiddenInput.value = editor.getData();
                        hiddenInput.dispatchEvent(new Event('input'));
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        }

        document.addEventListener('livewire:load', () => {
            initializeEditor();

            Livewire.hook('message.processed', () => {
                initializeEditor();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {

            function initFlatpickr() {
                const scheduleAtEl = document.querySelector('#scheduleAt');
                if (!scheduleAtEl) {
                    return;
                }
                flatpickr("#scheduleAt", {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i:S",
                    time_24hr: false,
                    minuteIncrement: 1,
                    defaultDate: null,
                    disable: [
                        function(date) {
                            return date < new Date();
                        },
                        function(date) {
                            return date.getFullYear() < new Date().getFullYear();
                        }
                    ],
                    onClose: function(selectedDates, dateStr, instance) {
                        @this.set('schedule_at', dateStr);
                    }
                });
            }


            initFlatpickr();



            window.livewire.on('flatpickr-reinitialize', () => {
                initFlatpickr();
            });



            window.livewire.on('initCKEditor', () => {
                ClassicEditor
                    .create(document.querySelector('#editor'), {
                        placeholder: 'Write your message here...'
                    })
                    .then(editor => {
                        editor.model.document.on('change:data', () => {
                            @this.set('content', editor.getData());
                        })
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });


            function initTooltips() {
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipTriggerList.forEach((tooltipTriggerEl) => {
                    new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            window.livewire.on('initTooltip', () => {
                initTooltips();
            });

        });

        document.addEventListener("livewire:load", () => {
            Livewire.hook('element.updated', (el, component) => {
                if (el.id === 'scheduleAt') {
                    flatpickr("#scheduleAt", {
                        enableTime: true,
                        dateFormat: "Y-m-d H:i:S",
                        time_24hr: false,
                        minuteIncrement: 1,
                        defaultDate: el.value ? el.value : null,
                        onClose: function(selectedDates, dateStr, instance) {
                            @this.set('schedule_at', dateStr);
                        }
                    });
                }
            });
        });

        document.addEventListener('isEmailSent', () => {
            setTimeout(function() {
                location.reload();
            }, 1000);

        });
    </script>
