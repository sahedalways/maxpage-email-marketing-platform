<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{ 'Communication Logs' }}</h5>
        </div>
        <div class="col-auto">

        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row g-2 align-items-end">

                        <div class="col-md-3">
                            <label for="byemailphone" class="form-label">
                                <i class="fas fa-search"></i>
                                {{ 'By Receiver Email or Phone' }}
                            </label>
                            <input type="text" class="form-control" id="byemailphone"
                                placeholder="{{ 'Search by Email or Phone' }}"
                                wire:model="search">
                        </div>


                        <div class="col-md-3">
                            <label for="startDate" class="form-label">
                                <i class="fas fa-calendar-alt"></i> {{ 'Start Date' }}
                            </label>
                            <input type="date" id="startDate" class="form-control" wire:model="startDate">
                        </div>


                        <div class="col-md-3">
                            <label for="endDate" class="form-label">
                                <i class="fas fa-calendar-check"></i> {{ 'End Date' }}
                            </label>
                            <input type="date" id="endDate" class="form-control" wire:model="endDate">
                        </div>


                        <div class="col-md-3">
                            <label for="status" class="form-label">
                                <i class="fas fa-info-circle"></i>
                                {{ 'By Message Status' }}
                            </label>
                            <select id="status" class="form-control" wire:model="status">
                                <option value="">{{ 'All Statuses' }}</option>
                                <option value="pending">{{ 'Schedule' }}</option>
                                <option value="sent">{{ 'Delivered' }}</option>
                                <option value="failed">{{ 'Failed' }}</option>
                            </select>
                        </div>


                        <div class="col-md-12 d-flex justify-content-start mt-2">
                            <button type="button" class="btn btn-primary me-2" wire:click="filterResults">
                                <i class="fas fa-filter"></i> {{ 'Filter' }}
                            </button>
                            <button type="button" class="btn btn-secondary" wire:click="resetFilters">
                                <i class="fas fa-undo"></i> {{ 'Reset' }}
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
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        {{ 'Sender' }}</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        {{ 'To' }}</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        {{ 'Date & Time' }}</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        {{ 'Status' }}</th>
                                    <th class="text-secondary opacity-7"> {{ 'Action' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp

                                @foreach ($messages as $row)
                                    <tr>
                                        <td>
                                            <p class="text-sm px-3 mb-0">{{ $i++ }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">
                                                {{ getApplicationName() ?? 'Maxpage' }}</p>
                                        </td>

                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->receiver_email }}
                                                {{ $row->receiver_phone_no }}</p>
                                        </td>



                                        <td>
                                            @php
                                                $latestHistory = $row->messageHistories->first();

                                                $initiatedAt = $row->created_at->format('Y-m-d H:i:s');
                                                $updatedAt = $row->updated_at->format('Y-m-d H:i:s');
                                                $scheduledFor = 'N/A';
                                                $deliveredAt = 'N/A';

                                                if ($latestHistory) {
                                                    if (
                                                        $latestHistory->status === 'pending' &&
                                                        $latestHistory->schedule_at
                                                    ) {
                                                        $scheduledFor = $latestHistory->schedule_at->format(
                                                            'Y-m-d H:i:s',
                                                        );
                                                    }

                                                    if ($latestHistory->status === 'sent') {
                                                        $deliveredAt = $latestHistory->created_at->format(
                                                            'Y-m-d H:i:s',
                                                        );
                                                    }
                                                }
                                            @endphp

                                            <div>
                                                <p class="mb-0"><strong>Initiated At:</strong> {{ $initiatedAt }}
                                                </p>
                                                <p class="mb-0"><strong>Scheduled For:</strong> {{ $scheduledFor }}
                                                </p>
                                                <p class="mb-0"><strong>Delivered At:</strong> {{ $deliveredAt }}
                                                </p>
                                                <p class="mb-0"><strong>Updated At:</strong> {{ $updatedAt }}</p>
                                            </div>

                                        </td>


                                        <td>
                                            @php
                                                $latestHistory = $row->messageHistories->first();

                                                if ($latestHistory) {
                                                    switch ($latestHistory->status) {
                                                        case 'sent':
                                                            $statusText = 'Delivered';
                                                            $statusColor = 'text-success';
                                                            $statusIcon = 'fas fa-check-circle';
                                                            break;

                                                        case 'pending':
                                                            $statusText = 'Pending';
                                                            $statusColor = 'text-warning';
                                                            $statusIcon = 'fas fa-clock';
                                                            break;

                                                        case 'schedule':
                                                            $statusText = 'Schedule';
                                                            $statusColor = 'text-primary';
                                                            $statusIcon = 'fas fa-clock';
                                                            break;

                                                        case 'failed':
                                                            $statusText = 'Failed';
                                                            $statusColor = 'text-danger';
                                                            $statusIcon = 'fas fa-times-circle';
                                                            break;

                                                        default:
                                                            $statusText = $latestHistory->status;
                                                            $statusColor = 'text-secondary';
                                                            $statusIcon = 'fas fa-info-circle';
                                                            break;
                                                    }
                                                } else {
                                                    $statusText = 'No History';
                                                    $statusColor = 'text-muted';
                                                    $statusIcon = 'fas fa-question-circle';
                                                }
                                            @endphp


                                            <p class="text-sm font-weight-bold mb-0 {{ $statusColor }}">
                                                <i class="{{ $statusIcon }}"></i> {{ $statusText }}
                                            </p>


                                        </td>



                                        <td>


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





    <div wire:ignore.self class="modal fade" id="quickView" tabindex="-1" role="dialog" aria-labelledby="quickView"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="quickView">
                        {{ 'Update Message Log Status' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            @if ($selectedMessage)

                                @if ($selectedMessage->messageHistories->isNotEmpty())
                                    @foreach ($selectedMessage->messageHistories as $messageHistory)
                                        @if (!empty($messageHistory->error_message))
                                            <div class="text-danger mt-2">
                                                <strong>Error:</strong>
                                                {{ $messageHistory->error_message }}
                                            </div>
                                        @endif
                                    @endforeach
                                @endif

                                <div class="row g-4">
                                    {{-- Dispatched At --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label
                                                class="fw-bold text-muted">{{ 'Dispatched At' }}:</label>
                                            <div class="p-2 border rounded-3 bg-light">
                                                {{ $selectedMessage->created_at->format('D, M d, Y g:i A') }}
                                            </div>
                                        </div>
                                    </div>




                                    {{-- Email Subject --}}

                                    @if ($selectedMessage->type == 'email')
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label
                                                    class="fw-bold text-muted">{{ 'Email Subject' }}:</label>
                                                <div class="p-2 border rounded-3 bg-light">
                                                    {{ $selectedMessage->subject ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                    {{-- Email Body --}}
                                    <div class="col-md-12">
                                        <div class="form-group d-flex justify-content-between align-items-center">
                                            @if ($selectedMessage->type == 'email')
                                                <label
                                                    class="fw-bold text-muted m-0">{{ 'Email Body' }}:</label>
                                            @else
                                                <label
                                                    class="fw-bold text-muted m-0">{{ 'Message Body' }}:</label>
                                            @endif

                                            {{-- View Icon (Redirect to another page) --}}
                                            @if (auth()->user()->role == 'guest')
                                                <a href="{{ route('admin.email.view', $selectedMessage->id) }}"
                                                    target="_blank" class="text-primary" title="View Full Email">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @elseif(auth()->user()->role == 'company')
                                                <a href="{{ route('admin.email.view', $selectedMessage->id) }}"
                                                    target="_blank" class="text-primary" title="View Full Email">
                                                    <i class="fas fa-external-link-alt"></i>


                                                </a>
                                            @else
                                                <a href="{{ route('admin.email.view', $selectedMessage->id) }}"
                                                    target="_blank" class="text-primary" title="View Full Email">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @endif

                                        </div>
                                        <div class="p-2 border rounded-3 bg-light"
                                            style="max-height: 150px; overflow-y: auto;">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($selectedMessage->body), 150, '...') }}
                                        </div>
                                    </div>

                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ 'Cancel' }}
                        </button>
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
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('delete', id)
            }
        })
    }
</script>
