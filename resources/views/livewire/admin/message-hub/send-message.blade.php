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
                            <div class="audience-btn-group" role="group" aria-label="Audience Selection">
                                <button type="button" wire:click="selectAudience('all')"
                                    class="btn btn-outline-primary {{ $selectedAudience === 'all' ? 'active text-white' : '' }}">
                                    <i class="fas fa-bullhorn me-2"></i> All Contacts
                                </button>

                                <button type="button" wire:click="selectAudience('type')"
                                    class="btn btn-outline-primary {{ $selectedAudience === 'type' ? 'active text-white' : '' }}">
                                    <i class="fas fa-users me-2"></i> Specific User Type
                                </button>

                                <button type="button" wire:click="selectAudience('single')"
                                    class="btn btn-outline-primary {{ $selectedAudience === 'single' ? 'active text-white' : '' }}">
                                    <i class="fas fa-user me-2"></i> Single Audience
                                </button>

                                <button type="button" wire:click="selectAudience('group')"
                                    class="btn btn-outline-primary {{ $selectedAudience === 'group' ? 'active text-white' : '' }}">
                                    <i class="fas fa-users me-2"></i> Group Audience
                                </button>

                            </div>
                        </div>
                    </div>

                    @if ($selectedAudience === 'type')
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="sendUserType" class="form-label">
                                    {{ 'User Type' }}
                                </label>
                                <select id="sendUserType" class="form-select" wire:model="userTypeFilter">
                                    <option value="">{{ 'Select User Type' }}</option>
                                    @foreach ($userTypes as $type)
                                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                </div>

                <div class="card-body p-0">
                    <div class="send-message-form-body">

                            @if ($selectedAudience === 'all' || $selectedAudience === 'type')
                                <div class="alert alert-primary text-white mt-3" role="alert">
                                    <i class="fas fa-info-circle me-2"></i>
                                    @if ($selectedAudience === 'all')
                                        This {{ $selectedCriteria === 'email' ? 'email' : ($selectedCriteria === 'sms' ? 'SMS' : 'WhatsApp message') }} will be sent to <strong>all contacts</strong>.
                                    @else
                                        This {{ $selectedCriteria === 'email' ? 'email' : ($selectedCriteria === 'sms' ? 'SMS' : 'WhatsApp message') }} will be sent to all <strong>{{ $userTypeFilter ? ucfirst($userTypeFilter) : 'selected user type' }}</strong> contacts.
                                    @endif
                                </div>
                            @endif

                            @if ($selectedAudience === 'single' || $selectedAudience === 'group')
                                @include('livewire.admin.message-hub._recipients', ['criteria' => $selectedCriteria, 'mode' => $selectedAudience])
                            @endif

                            @include('livewire.admin.message-hub._message-form', [
                                'criteria' => $selectedCriteria,
                                'sendMethod' => $selectedCriteria === 'email' ? 'sendEmailMessage' : ($selectedCriteria === 'sms' ? 'sendSmsMessage' : 'sendWhatsappMessage'),
                            ])

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
