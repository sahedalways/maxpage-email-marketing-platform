<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            @if ($selectedGateway === 'email')
                <h5 class="fw-500 text-white">{{ 'Email API Gateway List' }}
                </h5>
            @elseif($selectedGateway === 'sms')
                <h5 class="fw-500 text-white">{{ 'SMS API Gateway List' }}</h5>
            @elseif($selectedGateway === 'whatsapp')
                <h5 class="fw-500 text-white">
                    {{ 'Whatsapp API Gateway List' }}</h5>
            @endif


            <div class="col-12 d-flex justify-content-center my-4">
                <div class="msg-criteria-tabs d-flex" role="tablist">
                    <button wire:click="selectGateway('email')" type="button"
                        class="msg-tab {{ $selectedGateway === 'email' ? 'active' : '' }}">
                        <i class="fas fa-envelope me-2"></i> Email
                    </button>

                    <button wire:click="selectGateway('sms')" type="button"
                        class="msg-tab {{ $selectedGateway === 'sms' ? 'active' : '' }}">
                        <i class="fas fa-sms me-2"></i> SMS
                    </button>

                    <button wire:click="selectGateway('whatsapp')" type="button"
                        class="msg-tab {{ $selectedGateway === 'whatsapp' ? 'active' : '' }}">
                        <i class="fab fa-whatsapp me-2 text-success"></i> WhatsApp
                    </button>
                </div>
            </div>


        </div>
        <div class="col-auto gateway-add-col">

            @if ($selectedGateway === 'email')
                <a data-bs-toggle="modal" data-bs-target="#addEmailGateway" wire:click="resetInputFields"
                    class="btn btn-icon btn-3 btn-white text-primary mb-0">
                    <i class="fa fa-plus me-2"></i> {{ 'Add Gateway' }}
                </a>
            @elseif($selectedGateway === 'sms')
                <a data-bs-toggle="modal" data-bs-target="#addSmsGateway" wire:click="resetInputFields"
                    class="btn btn-icon btn-3 btn-white text-primary mb-0">
                    <i class="fa fa-plus me-2"></i> {{ 'Add Gateway' }}
                </a>
            @elseif($selectedGateway === 'whatsapp')
                <a data-bs-toggle="modal" data-bs-target="#addWhatsappGateway" wire:click="resetInputFields"
                    class="btn btn-icon btn-3 btn-white text-primary mb-0">
                    <i class="fa fa-plus me-2"></i>
                    {{ 'Add Gateway' }}
                </a>
            @endif
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">

                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead class="bg-light">
                                    @if ($selectedGateway === 'email')
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                            <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                                {{ 'Gateway Name' }}</th>
                                            <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                                {{ 'Gateway Email' }}</th>
                                            <th class="text-uppercase text-secondary text-xs  opacity-7">
                                                {{ 'Default' }}</th>
                                            <th class="text-secondary opacity-7">
                                                {{ 'Action' }}</th>
                                        </tr>
                                    @elseif($selectedGateway === 'sms')
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                            <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                                {{ 'Gateway' }}</th>
                                            <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                                {{ 'Brevo API Key' }}</th>
                                            <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                                {{ 'Twilio SID' }}</th>
                                            <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                                {{ 'Twilio Auth Token' }}</th>
                                            <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                                {{ 'Twilio Phone Number' }}</th>
                                            <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                                {{ 'SMS Type' }}</th>
                                            <th class="text-uppercase text-secondary text-xs  opacity-7">
                                                {{ 'Default' }}</th>
                                            <th class="text-secondary opacity-7">
                                                {{ 'Action' }}</th>
                                        </tr>
                                    @elseif($selectedGateway === 'whatsapp')
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                            <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                                {{ 'Business Account Name' }}
                                            </th>
                                            <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                                {{ 'Business Account ID' }}</th>

                                            <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                                {{ 'Twilio Auth Token' }}
                                            </th>
                                            <th class="text-uppercase text-secondary text-xs  opacity-7">
                                                {{ 'Default' }}</th>
                                            <th class="text-secondary opacity-7">
                                                {{ 'Action' }}</th>
                                        </tr>
                                    @endif

                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp

                                    @if ($selectedGateway === 'email')
                                        @foreach ($items as $row)
                                            <tr>
                                                <td>
                                                    <p class="text-sm px-3 mb-0">{{ $i++ }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ $row->mail_gateway_name }}</p>
                                                </td>

                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ $row->mail_gateway_email }}</p>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            wire:click="setDefault({{ $row->id }})"
                                                            {{ $row->status === 'default' ? 'checked' : '' }}
                                                            @if (auth()->user()->role === 'guest') disabled @endif>
                                                    </div>
                                                </td>


                                                <td>
                                                    <a data-bs-toggle="modal" data-bs-target="#quickEmailView"
                                                        wire:click="quickEmailView({{ $row->id }})" type="button"
                                                        class="badge badge-xs badge-primary fw-600 text-xs">
                                                        {{ 'Quick View' }}
                                                    </a>

                                                    <a data-bs-toggle="modal" data-bs-target="#editEmailInfo"
                                                        wire:click="editEmailInfo({{ $row->id }})" type="button"
                                                        class="badge badge-xs badge-warning fw-600 text-xs">
                                                        {{ 'Edit Info' }}
                                                    </a>
                                                    <a href="#" type="button"
                                                        class="ms-2 badge badge-xs badge-danger text-xs fw-600"
                                                        onclick="confirmDelete({{ $row->id }})">
                                                        {{ 'Delete' }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @elseif($selectedGateway === 'sms')
                                        @foreach ($items as $row)
                                            <tr>
                                                <td>
                                                    <p class="text-sm px-3 mb-0">{{ $i++ }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ $row->is_gateway_type }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ $row->brevo_sms_api_key ?? 'N/A' }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ $row->twilio_account_sid ?? 'N/A' }}</p>
                                                </td>

                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ $row->twilio_auth_token ?? 'N/A' }}</p>
                                                </td>

                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ $row->twilio_phone_number ?? 'N/A' }}</p>
                                                </td>

                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ $row->sms_type ?? 'N/A' }}</p>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            wire:click="setDefault({{ $row->id }})"
                                                            {{ $row->status === 'default' ? 'checked' : '' }}
                                                            @if (auth()->user()->role === 'guest') disabled @endif>
                                                    </div>
                                                </td>


                                                <td>
                                                    <a data-bs-toggle="modal" data-bs-target="#editSmsInfo"
                                                        wire:click="editSmsInfo({{ $row->id }})" type="button"
                                                        class="badge badge-xs badge-warning fw-600 text-xs">
                                                        {{ 'Edit Info' }}
                                                    </a>
                                                    <a href="#" type="button"
                                                        class="ms-2 badge badge-xs badge-danger text-xs fw-600"
                                                        onclick="confirmDelete({{ $row->id }})">
                                                        {{ 'Delete' }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @elseif($selectedGateway === 'whatsapp')
                                        @foreach ($items as $row)
                                            <tr>
                                                <td>
                                                    <p class="text-sm px-3 mb-0">{{ $i++ }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ $row->whatsapp_business_name }}</p>
                                                </td>

                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ $row->whatsapp_account_id }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ $row->twilio_auth_token }}</p>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            wire:click="setDefault({{ $row->id }})"
                                                            {{ $row->status === 'default' ? 'checked' : '' }}
                                                            @if (auth()->user()->role === 'guest') disabled @endif>
                                                    </div>
                                                </td>


                                                <td>
                                                    <a data-bs-toggle="modal" data-bs-target="#quickWhatsappView"
                                                        wire:click="quickWhatsappView({{ $row->id }})"
                                                        type="button"
                                                        class="badge badge-xs badge-warning fw-600 text-xs">
                                                        {{ 'Quick View' }}
                                                    </a>

                                                    <a data-bs-toggle="modal" data-bs-target="#editWhatsappInfo"
                                                        wire:click="editWhatsappInfo({{ $row->id }})"
                                                        type="button"
                                                        class="badge badge-xs badge-warning fw-600 text-xs">
                                                        {{ 'Edit Info' }}
                                                    </a>

                                                    <a href="#" type="button"
                                                        class="ms-2 badge badge-xs badge-danger text-xs fw-600"
                                                        onclick="confirmDelete({{ $row->id }})">
                                                        {{ 'Delete' }}
                                                    </a>
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            @if ($hasMorePages)
                                <div x-data="{
                                    init() {
                                        let observer = new IntersectionObserver((entries) => {
                                            entries.forEach(entry => {
                                                if (entry.isIntersecting) {
                                                    @this.call('loadItems')
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




    <div wire:ignore.self class="modal fade " id="addEmailGateway" tabindex="-1" role="dialog"
        aria-labelledby="addEmailGateway" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="addEmailGateway">
                        {{ 'Add Email Gateway' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>


                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-12 mb-1">
                                <label class="form-label">Service Provider <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="serviceProvider">
                                    <option value="">-- Select Provider --</option>
                                    <option value="brevo" selected>Brevo</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('serviceProvider')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            @if ($serviceProvider == 'brevo')
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ 'API Key' }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control"
                                        placeholder="{{ 'Enter API Key' }}"
                                        wire:model="mail_api_key"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_api_key')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label
                                        class="form-label">{{ 'Gateway Email' }}
                                        <span class="text-danger">*</span> </label>
                                    <input type="email" class="form-control" required
                                        placeholder="{{ 'Enter Gateway Email' }}"
                                        wire:model="mail_gateway_email"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_gateway_email')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif


                            @if ($serviceProvider == 'other')
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ 'Gateway Name' }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control"
                                        placeholder="{{ 'Enter Gateway Name' }}"
                                        wire:model="mail_gateway_name"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_gateway_name')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label
                                        class="form-label">{{ 'Gateway Email' }}
                                        <span class="text-danger">*</span> </label>
                                    <input type="email" class="form-control" required
                                        placeholder="{{ 'Enter Gateway Email' }}"
                                        wire:model="mail_gateway_email"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_gateway_email')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ 'Gateway Type' }}
                                        <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model="mail_gateway_type">
                                        <option value="smtp" selected>SMTP</option>
                                    </select>
                                    @error('mail_gateway_type')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ 'Mail Host' }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required
                                        placeholder="{{ 'Enter Mail Host' }}"
                                        wire:model="mail_host" oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_host')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ 'Mail Driver' }}
                                    </label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ 'Enter Mail Driver' }}"
                                        wire:model="mail_driver" oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_driver')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ 'Mail Port' }} <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" required
                                        placeholder="{{ 'Enter Mail Port' }}"
                                        wire:model="mail_port" oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_port')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label
                                        class="form-label">{{ 'Mail Encryption' }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required
                                        placeholder="{{ 'Enter Mail Encryption (e.g., tls, ssl)' }}"
                                        wire:model="mail_encryption"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_encryption')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ 'Mail Username' }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required
                                        placeholder="{{ 'Enter Mail Username' }}"
                                        wire:model="mail_username"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_username')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ 'Mail Password' }}
                                        <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" required
                                        placeholder="{{ 'Enter Mail Password' }}"
                                        wire:model="mail_password"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_password')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif



                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary"
                            wire:click.prevent="storeEmailGateway()">{{ 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div wire:ignore.self class="modal fade " id="editEmailInfo" tabindex="-1" role="dialog"
        aria-labelledby="editEmailInfo" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="editEmailInfo">
                        {{ 'Edit Email Gateway' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-12 mb-1">
                                <label class="form-label">Service Provider <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="serviceProvider">
                                    <option value="">-- Select Provider --</option>
                                    <option value="brevo" selected>Brevo</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('serviceProvider')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            @if ($serviceProvider == 'brevo')
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ 'API Key' }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control"
                                        placeholder="{{ 'Enter API Key' }}"
                                        wire:model="mail_api_key"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_api_key')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label
                                        class="form-label">{{ 'Gateway Email' }}
                                        <span class="text-danger">*</span> </label>
                                    <input type="email" class="form-control" required
                                        placeholder="{{ 'Enter Gateway Email' }}"
                                        wire:model="mail_gateway_email"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_gateway_email')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif


                            @if ($serviceProvider == 'other')
                                <div class="col-md-12 mb-1">
                                    <label
                                        class="form-label">{{ 'Gateway Name' }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control"
                                        placeholder="{{ 'Enter Gateway Name' }}"
                                        wire:model="mail_gateway_name"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_gateway_name')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label
                                        class="form-label">{{ 'Gateway Email' }}
                                        <span class="text-danger">*</span> </label>
                                    <input type="email" class="form-control" required
                                        placeholder="{{ 'Enter Gateway Email' }}"
                                        wire:model="mail_gateway_email"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_gateway_email')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label
                                        class="form-label">{{ 'Gateway Type' }}
                                        <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model="mail_gateway_type">
                                        <option value="smtp" selected>SMTP</option>
                                    </select>
                                    @error('mail_gateway_type')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ 'Mail Host' }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required
                                        placeholder="{{ 'Enter Mail Host' }}"
                                        wire:model="mail_host" oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_host')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ 'Mail Driver' }}
                                    </label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ 'Enter Mail Driver' }}"
                                        wire:model="mail_driver" oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_driver')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ 'Mail Port' }} <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" required
                                        placeholder="{{ 'Enter Mail Port' }}"
                                        wire:model="mail_port" oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_port')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label
                                        class="form-label">{{ 'Mail Encryption' }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required
                                        placeholder="{{ 'Enter Mail Encryption (e.g., tls, ssl)' }}"
                                        wire:model="mail_encryption"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_encryption')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ 'Mail Username' }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required
                                        placeholder="{{ 'Enter Mail Username' }}"
                                        wire:model="mail_username"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_username')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ 'Mail Password' }}
                                        <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" required
                                        placeholder="{{ 'Enter Mail Password' }}"
                                        wire:model="mail_password"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('mail_password')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif



                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary"
                            wire:click.prevent="updateEmailGatewayInfo()">{{ 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div wire:ignore.self class="modal fade " id="quickEmailView" tabindex="-1" role="dialog"
        aria-labelledby="quickEmailView" aria-hidden="true">
        <div class="modal-dialog full-width" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="quickEmailView">
                        {{ 'Quick Email Gateway View' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            @if ($selectedEmailGatewayView)
                                @if ($serviceProvider === 'brevo')
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mailApiKey"
                                                    class="fw-bold text-muted">{{ 'Mail API Key' }}</label>
                                                <div class="p-2 border rounded-3 bg-light">
                                                    <p class="mb-0">{{ $selectedEmailGatewayView->mail_api_key }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="gatewayEmail"
                                                    class="fw-bold text-muted">{{ 'Gateway Email' }}</label>
                                                <div class="p-2 border rounded-3 bg-light">
                                                    <p class="mb-0">
                                                        {{ $selectedEmailGatewayView->mail_gateway_email }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="gatewayName"
                                                        class="fw-bold text-muted">{{ 'Gateway Name' }}</label>
                                                    <div class="p-2 border rounded-3 bg-light">
                                                        <p class="mb-0">
                                                            {{ $selectedEmailGatewayView->mail_gateway_name }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="gatewayEmail"
                                                        class="fw-bold text-muted">{{ 'Gateway Email' }}</label>
                                                    <div class="p-2 border rounded-3 bg-light">
                                                        <p class="mb-0">
                                                            {{ $selectedEmailGatewayView->mail_gateway_email }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mailHost"
                                                        class="fw-bold text-muted">{{ 'Mail Host' }}</label>
                                                    <div class="p-2 border rounded-3 bg-light">
                                                        <p class="mb-0">{{ $selectedEmailGatewayView->mail_host }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mailPort"
                                                        class="fw-bold text-muted">{{ 'Mail Port' }}</label>
                                                    <div class="p-2 border rounded-3 bg-light">
                                                        <p class="mb-0">{{ $selectedEmailGatewayView->mail_port }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mailUsername"
                                                        class="fw-bold text-muted">{{ 'Mail Username' }}</label>
                                                    <div class="p-2 border rounded-3 bg-light">
                                                        <p class="mb-0">
                                                            {{ $selectedEmailGatewayView->mail_username }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mailPassword"
                                                        class="fw-bold text-muted">{{ 'Mail Password' }}</label>
                                                    <div class="p-2 border rounded-3 bg-light">
                                                        <p class="mb-0">
                                                            {{ $selectedEmailGatewayView->mail_password }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mailEncryption"
                                                        class="fw-bold text-muted">{{ 'Mail Encryption' }}</label>
                                                    <div class="p-2 border rounded-3 bg-light">
                                                        <p class="mb-0">
                                                            {{ $selectedEmailGatewayView->mail_encryption }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                @endif

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



    <div wire:ignore.self class="modal fade " id="addSmsGateway" tabindex="-1" role="dialog"
        aria-labelledby="addSmsGateway" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="addSmsGateway">
                        {{ 'Add SMS Gateway' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-12 mb-1">
                                <label class="form-label">Service Provider <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="serviceProvider">
                                    <option value="">-- Select Provider --</option>
                                    <option value="brevo" selected>Brevo</option>
                                    <option value="twilio">Twilio</option>
                                </select>
                                @error('serviceProvider')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            @if ($serviceProvider == 'twilio')
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Twilio SID <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control"
                                        placeholder="Enter Twilio SID" wire:model="twilio_account_sid"
                                        autocomplete="off" maxlength="34"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('twilio_account_sid')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Twilio Auth Token <span
                                            class="text-danger">*</span></label>
                                    <input type="text" required class="form-control"
                                        placeholder="Enter Twilio Auth Token" wire:model="twilio_auth_token"
                                        autocomplete="off" maxlength="32"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('twilio_auth_token')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Twilio Phone Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" required class="form-control"
                                        placeholder="Enter Twilio Phone Number" wire:model="twilio_phone_number"
                                        maxlength="15" oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('twilio_phone_number')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="col-md-12 mb-1">
                                    <label class="form-label">SMS Type <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control" placeholder="Enter SMS Type"
                                        wire:model="sms_type" oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('sms_type')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @elseif($serviceProvider == 'brevo')
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">API Key <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control" placeholder="Enter API Key"
                                        wire:model="brevo_api_key" autocomplete="off"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('brevo_api_key')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary"
                            wire:click.prevent="storeSmsGateway()">{{ 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div wire:ignore.self class="modal fade " id="editSmsInfo" tabindex="-1" role="dialog"
        aria-labelledby="editSmsInfo" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="editSmsInfo">
                        {{ 'Edit SMS Gateway Info' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">

                            <div class="col-md-12 mb-1">
                                <label class="form-label">Service Provider <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="serviceProvider">
                                    <option value="">-- Select Provider --</option>
                                    <option value="brevo" selected>Brevo</option>
                                    <option value="twilio">Twilio</option>
                                </select>
                                @error('serviceProvider')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            @if ($serviceProvider == 'twilio')
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Twilio SID <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control"
                                        placeholder="Enter Twilio SID" wire:model="twilio_account_sid"
                                        autocomplete="off" maxlength="34"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('twilio_account_sid')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Twilio Auth Token <span
                                            class="text-danger">*</span></label>
                                    <input type="text" required class="form-control"
                                        placeholder="Enter Twilio Auth Token" wire:model="twilio_auth_token"
                                        autocomplete="off" maxlength="32"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('twilio_auth_token')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Twilio Phone Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" required class="form-control"
                                        placeholder="Enter Twilio Phone Number" wire:model="twilio_phone_number"
                                        maxlength="15" oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('twilio_phone_number')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="col-md-12 mb-1">
                                    <label class="form-label">SMS Type <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control" placeholder="Enter SMS Type"
                                        wire:model="sms_type" oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('sms_type')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @elseif($serviceProvider == 'brevo')
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">API Key <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control" placeholder="Enter API Key"
                                        wire:model="brevo_api_key" autocomplete="off"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('brevo_api_key')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary"
                            wire:click.prevent="updateSmsGatewayInfo()">{{ 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div wire:ignore.self class="modal fade " id="addWhatsappGateway" tabindex="-1" role="dialog"
        aria-labelledby="addWhatsappGateway" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="addWhatsappGateway">
                        {{ 'Add WhatsApp Gateway' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">

                            <div class="col-md-12 mb-1">
                                <label class="form-label">Service Provider <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="serviceProvider">
                                    <option value="">-- Select Provider --</option>
                                    <option value="whatsapp_business" selected>WhatsApp Business</option>
                                    <option value="twilio">Twilio</option>
                                </select>
                                @error('serviceProvider')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                            @if ($serviceProvider === 'whatsapp_business')
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">WhatsApp Business Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required
                                        placeholder="Enter WhatsApp Business Name" wire:model="whatsapp_business_name"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('whatsapp_business_name')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">WhatsApp Access Token <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required
                                        placeholder="Enter WhatsApp Access Token" wire:model="whatsapp_access_token"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('whatsapp_access_token')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">WhatsApp Number ID <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required
                                        placeholder="Enter WhatsApp Number ID" wire:model="whatsapp_no_id"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('whatsapp_no_id')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">WhatsApp Account ID <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required
                                        placeholder="Enter WhatsApp Account ID" wire:model="whatsapp_account_id"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('whatsapp_account_id')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif


                            @if ($serviceProvider === 'twilio')
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">Twilio Account SID <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Twilio Account SID"
                                        wire:model="twilio_account_sid"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('twilio_account_sid')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">Twilio Auth Token <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Twilio Auth Token"
                                        wire:model="twilio_auth_token"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('twilio_auth_token')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">Twilio Phone Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control"
                                        placeholder="Enter Twilio Phone Number" wire:model="twilio_phone_number"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('twilio_phone_number')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary"
                            wire:click.prevent="storeWhatsappGateway()">{{ 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div wire:ignore.self class="modal fade " id="editWhatsappInfo" tabindex="-1" role="dialog"
        aria-labelledby="editWhatsappInfo" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="editWhatsappInfo">
                        {{ 'Edit WhatsApp Gateway' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-12 mb-1">
                                <label class="form-label">Service Provider <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="serviceProvider">
                                    <option value="">-- Select Provider --</option>
                                    <option value="whatsapp_business" selected>WhatsApp Business</option>
                                    <option value="twilio">Twilio</option>
                                </select>
                                @error('serviceProvider')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                            @if ($serviceProvider === 'whatsapp_business')
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">WhatsApp Business Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required
                                        placeholder="Enter WhatsApp Business Name" wire:model="whatsapp_business_name"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('whatsapp_business_name')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">WhatsApp Access Token <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required
                                        placeholder="Enter WhatsApp Access Token" wire:model="whatsapp_access_token"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('whatsapp_access_token')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">WhatsApp Number ID <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required
                                        placeholder="Enter WhatsApp Number ID" wire:model="whatsapp_no_id"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('whatsapp_no_id')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">WhatsApp Account ID <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required
                                        placeholder="Enter WhatsApp Account ID" wire:model="whatsapp_account_id"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('whatsapp_account_id')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif


                            @if ($serviceProvider === 'twilio')
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">Twilio Account SID <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Twilio Account SID"
                                        wire:model="twilio_account_sid"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('twilio_account_sid')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">Twilio Auth Token <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Twilio Auth Token"
                                        wire:model="twilio_auth_token"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('twilio_auth_token')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-1">
                                    <label class="form-label">Twilio Phone Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control"
                                        placeholder="Enter Twilio Phone Number" wire:model="twilio_phone_number"
                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                    @error('twilio_phone_number')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary"
                            wire:click.prevent="updateWhatsappGateway()">{{ 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div wire:ignore.self class="modal fade " id="quickWhatsappView" tabindex="-1" role="dialog"
        aria-labelledby="quickWhatsappView" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content full-width">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="quickWhatsappView">
                        {{ 'Quick WhatsApp Gateway View' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">

                            @if ($selectedWhatsappGatewayView)
                                @if ($serviceProvider === 'whatsapp_business')
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="gatewayName"
                                                    class="fw-bold text-muted">{{ 'WhatsApp Business Name' }}</label>
                                                <div class="p-2 border rounded-3 bg-light">
                                                    <p class="mb-0">
                                                        {{ $selectedWhatsappGatewayView->whatsapp_business_name }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="accessToken"
                                                    class="fw-bold text-muted">{{ 'WhatsApp Access Token' }}</label>
                                                <div class="p-2 border rounded-3 bg-light">
                                                    <p class="mb-0">
                                                        {{ Str::limit($selectedWhatsappGatewayView->whatsapp_access_token, 25, '...') }}
                                                    </p>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="numberId"
                                                    class="fw-bold text-muted">{{ 'WhatsApp Number ID' }}</label>
                                                <div class="p-2 border rounded-3 bg-light">
                                                    <p class="mb-0">
                                                        {{ $selectedWhatsappGatewayView->whatsapp_no_id }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="accountId"
                                                    class="fw-bold text-muted">{{ 'WhatsApp Account ID' }}</label>
                                                <div class="p-2 border rounded-3 bg-light">
                                                    <p class="mb-0">
                                                        {{ $selectedWhatsappGatewayView->whatsapp_account_id }}</p>
                                                </div>
                                            </div>
                                        </div>
                                @endif

                                @if ($serviceProvider === 'twilio')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="twilio_sid"
                                                class="fw-bold text-muted">{{ 'Twilio SID' }}</label>
                                            <div class="p-2 border rounded-3 bg-light">
                                                <p class="mb-0">
                                                    {{ $selectedWhatsappGatewayView->twilio_account_sid }}</p>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="twilio_token"
                                                class="fw-bold text-muted">{{ 'Twilio Auth Token' }}</label>
                                            <div class="p-2 border rounded-3 bg-light">
                                                <p class="mb-0">
                                                    {{ $selectedWhatsappGatewayView->twilio_auth_token }}</p>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="twilio_phone_no"
                                                class="fw-bold text-muted">{{ 'Twilio Phone Number' }}</label>
                                            <div class="p-2 border rounded-3 bg-light">
                                                <p class="mb-0">
                                                    {{ $selectedWhatsappGatewayView->twilio_phone_number }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

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

        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
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
                    Livewire.emit('deleteGateway', id);
                }
            })
        }
    </script>
