<div>
    <div class="row" wire:poll>
        <div class="col-lg-12">
            <div class="row align-items-center">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row align-items-center px-2">
                                <div class="col-8">
                                    <div class="numbers py-2">
                                        <p class="text-sm mb-3 text-uppercase">{{ 'Total Messages' }}</p>
                                        <h5 class="font-weight-bolder">{{ $totalMessages }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-secondary text-center rounded-circle">
                                        <i class="ni ni-email-83 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row align-items-center px-2">
                                <div class="col-8">
                                    <div class="numbers py-2">
                                        <p class="text-sm mb-3 text-uppercase">{{ 'Emails Sent' }}</p>
                                        <h5 class="font-weight-bolder">{{ $emailCount }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-primary text-center rounded-circle">
                                        <i class="ni ni-single-copy-04 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row align-items-center px-2">
                                <div class="col-8">
                                    <div class="numbers py-2">
                                        <p class="text-sm mb-3 text-uppercase">{{ 'SMS Sent' }}</p>
                                        <h5 class="font-weight-bolder">{{ $smsCount }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-warning text-center rounded-circle">
                                        <i class="ni ni-mobile-button text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row align-items-center px-2">
                                <div class="col-8">
                                    <div class="numbers py-2">
                                        <p class="text-sm mb-3 text-uppercase">{{ 'WhatsApp Sent' }}</p>
                                        <h5 class="font-weight-bolder">{{ $whatsappCount }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-success text-center rounded-circle">
                                        <i class="ni ni-chat-round text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row align-items-center px-2">
                                <div class="col-8">
                                    <div class="numbers py-2">
                                        <p class="text-sm mb-3 text-uppercase">{{ 'Pending' }}</p>
                                        <h5 class="font-weight-bolder">{{ $pendingCount }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-danger text-center rounded-circle">
                                        <i class="ni ni-watch-time text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row align-items-center px-2">
                                <div class="col-8">
                                    <div class="numbers py-2">
                                        <p class="text-sm mb-3 text-uppercase">{{ 'Gateways' }}</p>
                                        <h5 class="font-weight-bolder">{{ $gatewayCount }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-secondary text-center rounded-circle">
                                        <i class="ni ni-settings-gear-65 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row align-items-center px-2">
                                <div class="col-8">
                                    <div class="numbers py-2">
                                        <p class="text-sm mb-3 text-uppercase">{{ 'Templates' }}</p>
                                        <h5 class="font-weight-bolder">{{ $templateCount }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-primary text-center rounded-circle">
                                        <i class="ni ni-bullet-list-67 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row align-items-center px-2">
                                <div class="col-8">
                                    <div class="numbers py-2">
                                        <p class="text-sm mb-3 text-uppercase">{{ 'Contacts' }}</p>
                                        <h5 class="font-weight-bolder">{{ $contactCount }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-info text-center rounded-circle">
                                        <i class="ni ni-badge text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header pb-3 pt-3">
                    <h5 class="pb-0 fw-500">{{ 'Recent Messages' }}</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-sm align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ 'Type' }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ 'Subject / Content' }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ 'Recipient' }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ 'Date' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentMessages as $item)
                                    <tr>
                                        <td>
                                            <span class="badge badge-sm
                                                {{ $item->type == 'email' ? 'bg-primary' : ($item->type == 'phone' ? 'bg-warning' : 'bg-success') }}">
                                                {{ ucfirst($item->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark text-xs fw-600">{{ $item->subject ?? Str::limit($item->content, 40) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-xs">{{ $item->receiver_email ?? $item->receiver_phone_no }}</span>
                                        </td>
                                        <td>
                                            <span class="text-xs">{{ $item->created_at ? $item->created_at->format('d M, Y h:i A') : '' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary text-xs py-4">
                                            {{ 'No messages sent yet.' }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header pb-3 pt-3">
                    <h5 class="pb-0 fw-500">{{ 'Recent Contacts' }}</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-sm align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ 'Name' }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ 'Email / Phone' }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ 'Source' }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ 'User Type' }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ 'Added' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentContacts as $contact)
                                    <tr>
                                        <td>
                                            <span class="text-dark text-xs fw-600">{{ $contact->name ?: 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-xs">{{ $contact->email ?: $contact->phone }}</span>
                                        </td>
                                        <td>
                                            @if ($contact->source)
                                                <span class="badge badge-sm bg-gradient-primary text-uppercase text-white">{{ $contact->source }}</span>
                                            @else
                                                <span class="text-xs text-secondary">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($contact->user_type)
                                                <span class="badge badge-sm text-uppercase text-white {{ $contact->user_type == 'customer' ? 'bg-success' : 'bg-info' }}">{{ ucfirst($contact->user_type) }}</span>
                                            @else
                                                <span class="text-xs text-secondary">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-xs">{{ $contact->created_at ? $contact->created_at->format('d M, Y h:i A') : '' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary text-xs py-4">
                                            {{ 'No contacts added yet.' }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h5 class="pb-4 fw-500">{{ 'Overview' }}</h5>
                </div>
                <div class="card-body pt-0 pb-2">
                    <div class="row">
                        <div class="col-12 text-start mb-4" wire:ignore>
                            <div class="chart">
                                <canvas id="doughnut-chart" class="chart-canvas" height="300px"></canvas>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <span class="badge badge-md badge-dot ms-4 text-start">
                                    <i class="bg-primary"></i>
                                    <span class="text-dark text-xs">{{ 'Email' }}</span>
                                </span>
                                <span class="badge badge-md badge-dot me-4 text-start">
                                    <i class="bg-warning"></i>
                                    <span class="text-dark text-xs">{{ 'SMS' }}</span>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="badge badge-md badge-dot ms-4 text-start">
                                    <i class="bg-success"></i>
                                    <span class="text-dark text-xs">{{ 'WhatsApp' }}</span>
                                </span>
                                <span class="badge badge-md badge-dot me-4 text-start">
                                    <i class="bg-danger"></i>
                                    <span class="text-dark text-xs">{{ 'Pending' }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="" id="chartdata" value="{{ $array }}">
    @push('js')
        <script>
            "use strict";
            var ctx3 = document.getElementById("doughnut-chart").getContext("2d");
            var chartdata = document.getElementById("chartdata").value;
            new Chart(ctx3, {
                type: "doughnut",
                data: {
                    datasets: [{
                        label: "Messages",
                        weight: 9,
                        cutout: 60,
                        tension: 0.9,
                        pointRadius: 2,
                        borderWidth: 2,
                        backgroundColor: ['#5e72e4', '#faae42', '#2dce89', '#f5365c'],
                        data: JSON.parse(chartdata),
                        fill: false
                    }],
                    labels: ['Email', 'SMS', 'WhatsApp', 'Pending'],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        }
                    },
                    interaction: {
                        intersect: true,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                            },
                            ticks: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                            },
                            ticks: {
                                display: false,
                            }
                        },
                    },
                },
            });
        </script>
    @endpush
</div>
