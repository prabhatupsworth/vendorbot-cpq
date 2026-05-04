@extends('layouts.app')

@section('content')
    <style>
        .detail-box {
            padding: 12px 14px;
            border: 1px solid #eee;
            border-radius: 10px;
            background: #f8f9fa;
            transition: 0.2s ease;
        }

        .detail-box:hover {
            background: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .user-card {
            transition: all 0.2s ease;
        }

        .user-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .avatar-lg {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .role-badge {
            font-size: 11px;
            padding: 5px 10px;
            border-radius: 20px;
        }
    </style>
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-4">
                        <h4 class="page-title">Project<span class="count-title">{{ $project?->name }}</span></h4>
                    </div>
                    <div class="col-sm-8 text-sm-end">
                        <div class="head-icons">
                            <a href="profile.html" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-original-title="Refresh"><i class="ti ti-refresh-dot"></i></a>
                            <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-chevrons-up"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm border-0 mb-3">
                        {{-- 🔥 Header --}}
                        <div
                            class="card-header bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">

                            {{-- Left: Title + Meta --}}
                            <div>
                                <h5 class="fw-bold mb-1">Project Details</h5>

                                <div class="text-muted small">
                                    {{ $project->name }}
                                    <span class="mx-2">•</span>
                                    {{ $project->slug }}
                                </div>
                            </div>

                            {{-- Right: Status --}}
                            <div class="d-flex gap-2">

                                {{-- Sync --}}
                                <span
                                    class="badge
                {{ $project->pipedrive_sync_status ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $project->pipedrive_sync_status ? 'Synced' : 'Not Synced' }}
                                </span>

                                {{-- Plugin --}}
                                <span
                                    class="badge
                {{ $project->plugin_connected ? 'bg-success' : 'bg-danger' }}">
                                    {{ $project->plugin_connected ? 'Connected' : 'Disconnected' }}
                                </span>

                            </div>
                        </div>
                        <div class="card-body">

                            <ul class="nav nav-tabs nav-tabs-bottom mb-3">
                                <li class="nav-item">
                                    <a class="nav-link  d-flex align-items-center gap-2 active" data-bs-toggle="tab"
                                        href="#project-overview">
                                        <i class="ti ti-layout-dashboard"></i>Overview</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  d-flex align-items-center gap-2" data-bs-toggle="tab"
                                        href="#project-status">
                                        <i class="ti ti-activity"></i>Status</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center gap-2" data-bs-toggle="tab"
                                        href="#project-company">
                                        <i class="ti ti-building"></i>
                                        Company
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center gap-2" data-bs-toggle="tab"
                                        href="#project-users">
                                        <i class="ti ti-users"></i>
                                        Users
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  d-flex align-items-center gap-2" data-bs-toggle="tab"
                                        href="#project-history">
                                        <i class="ti ti-clock"></i>History</a>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <div class="tab-content">

                        <!-- ✅ OVERVIEW -->
                        <div class="tab-pane show active" id="project-overview">

                            <div class="card shadow-sm border-0">
                                <div
                                    class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold mb-0">Project Overview</h5>
                                    <span class="badge bg-primary text-uppercase">{{ $project->flow_type }}</span>
                                </div>

                                <div class="card-body">

                                    <div class="row g-4">

                                        {{-- Website --}}
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start gap-3 p-3 rounded bg-light-subtle border">
                                                <i class="ti ti-world fs-4 text-primary"></i>
                                                <div>
                                                    <small class="text-muted">Website</small>
                                                    <div class="fw-semibold">
                                                        {{ $project->website_url ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Event --}}
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start gap-3 p-3 rounded bg-light-subtle border">
                                                <i class="ti ti-calendar-event fs-4 text-success"></i>
                                                <div>
                                                    <small class="text-muted">Event</small>
                                                    <div class="fw-semibold">
                                                        {{ $project->event_name ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Pipedrive --}}
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start gap-3 p-3 rounded bg-light-subtle border">
                                                <i class="ti ti-briefcase fs-4 text-info"></i>
                                                <div>
                                                    <small class="text-muted">Pipedrive</small>
                                                    <div class="fw-semibold">
                                                        {{ $project?->pipedriveAccount?->account_name ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Invoice Account --}}
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start gap-3 p-3 rounded bg-light-subtle border">
                                                <i class="ti ti-receipt fs-4 text-warning"></i>
                                                <div>
                                                    <small class="text-muted">Invoice Account</small>
                                                    <div class="fw-semibold">
                                                        {{ ucfirst($project?->invoiceAccount?->type ?? '-') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Invoice Status --}}
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start gap-3 p-3 rounded bg-light-subtle border">
                                                <i class="ti ti-check fs-4 text-success"></i>
                                                <div>
                                                    <small class="text-muted">Invoice Status</small>
                                                    <div>
                                                        {!! $project?->invoice_enabled
                                                            ? '<span class="badge bg-success px-3 py-1">Enabled</span>'
                                                            : '<span class="badge bg-secondary px-3 py-1">Disabled</span>' !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>

                        <!-- ✅ STATUS -->
                        <div class="tab-pane" id="project-status">
                            <div class="card shadow-sm border-0">

                                <div class="card-header bg-white border-bottom d-flex justify-content-between">
                                    <h5 class="fw-bold mb-0">Project Status</h5>
                                </div>

                                <div class="card-body">
                                    <div class="row g-4">

                                        {{-- Sync Status --}}
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start gap-3 p-3 rounded border bg-light-subtle">
                                                <i class="ti ti-refresh fs-4 text-primary"></i>
                                                <div>
                                                    <small class="text-muted">Pipedrive Sync</small>
                                                    <div>
                                                        {!! $project->pipedrive_sync_status
                                                            ? '<span class="badge bg-success px-3 py-1">Synced</span>'
                                                            : '<span class="badge bg-warning text-dark px-3 py-1">Not Synced</span>' !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Plugin Status --}}
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start gap-3 p-3 rounded border bg-light-subtle">
                                                <i class="ti ti-plug fs-4 text-success"></i>
                                                <div>
                                                    <small class="text-muted">Plugin Status</small>
                                                    <div>
                                                        {!! $project->plugin_connected
                                                            ? '<span class="badge bg-success px-3 py-1">Connected</span>'
                                                            : '<span class="badge bg-danger px-3 py-1">Disconnected</span>' !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Connected At --}}
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start gap-3 p-3 rounded border bg-light-subtle">
                                                <i class="ti ti-calendar fs-4 text-info"></i>
                                                <div>
                                                    <small class="text-muted">Connected At</small>
                                                    <div class="fw-semibold">
                                                        {{ $project->plugin_connected_at ? $project->plugin_connected_at->format('d M Y, h:i A') : '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Last Ping --}}
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start gap-3 p-3 rounded border bg-light-subtle">
                                                <i class="ti ti-activity fs-4 text-warning"></i>
                                                <div>
                                                    <small class="text-muted">Last Ping</small>
                                                    <div class="fw-semibold">
                                                        {{ $project->plugin_last_ping_at ? $project->plugin_last_ping_at->diffForHumans() : 'Never' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        @php
                            $company = $project->companyDetails;
                        @endphp

                        <div class="tab-pane fade" id="project-company">

                            <div class="card shadow-sm border-0">

                                {{-- Header --}}
                                <div
                                    class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold mb-0">Company Details</h5>

                                    {{-- ✅ Button Toggle --}}
                                    @if ($company)
                                        <button class="btn btn-sm btn-primary edit-form" data-bs-toggle="offcanvas"
                                            data-bs-target="#companyCanvas" data-type="edit"
                                            data-url="{{ route('projects.company.store', $project->id) }}"
                                            data-method="POST" data-data='@json($company)'
                                            data-bs-toggle="offcanvas" data-form="#companyForm">
                                            <i class="ti ti-edit"></i> Edit Company
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-success" data-bs-toggle="offcanvas"
                                            data-bs-target="#companyCanvas" data-form="#companyForm">
                                            <i class="ti ti-plus"></i> Add Company
                                        </button>
                                    @endif
                                </div>

                                <div class="card-body">

                                    @if ($company)
                                        {{-- ✅ SHOW COMPANY DATA --}}
                                        <div class="row g-4">

                                            {{-- Logo --}}
                                            <div class="col-md-4">
                                                <div class="text-center p-4 border rounded bg-light-subtle h-100">

                                                    @if ($company->logo)
                                                        <img src="{{ asset('storage/' . $company->logo) }}"
                                                            class="img-fluid rounded mb-3" style="max-height:120px;">
                                                    @else
                                                        <div class="text-muted py-4">
                                                            <i class="ti ti-photo fs-1"></i>
                                                            <div>No Logo</div>
                                                        </div>
                                                    @endif

                                                    <h6 class="fw-semibold">
                                                        {{ $company->company_name }}
                                                    </h6>

                                                    <small class="text-muted">
                                                        {{ $company->email ?? '-' }}
                                                    </small>

                                                </div>
                                            </div>

                                            {{-- Details --}}
                                            <div class="col-md-8">
                                                <div class="row g-3">

                                                    <div class="col-md-6">
                                                        <div class="detail-box">
                                                            <small>Contact Person</small>
                                                            <div class="fw-semibold">
                                                                {{ $company->contact_name ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="detail-box">
                                                            <small>Phone</small>
                                                            <div class="fw-semibold">
                                                                {{ $company->phone ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="detail-box">
                                                            <small>Address</small>
                                                            <div class="fw-semibold">
                                                                {{ $company->address_line1 ?? '' }}
                                                                {{ $company->address_line2 ?? '' }},
                                                                {{ $company->city ?? '' }},
                                                                {{ $company->state ?? '' }},
                                                                {{ $company->country ?? '' }}
                                                                {{ $company->postal_code ?? '' }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    @else
                                        {{-- ❌ NO COMPANY STATE --}}
                                        <div class="text-center py-5">

                                            <i class="ti ti-building fs-1 text-muted mb-3"></i>

                                            <h6 class="fw-semibold">No Company Details Added</h6>

                                            <p class="text-muted mb-3">
                                                Add company information to manage billing, invoices, and integrations.
                                            </p>

                                            <button class="btn btn-success" data-bs-toggle="offcanvas"
                                                data-bs-target="#companyCanvas">
                                                <i class="ti ti-plus"></i> Add Company
                                            </button>

                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="project-users">

                            <div class="card border-0 shadow-sm">

                                <!-- Header -->
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="fw-semibold mb-0">Project Users</h5>

                                    <button class="btn btn-sm btn-primary" data-bs-toggle="offcanvas"
                                        data-bs-target="#userCanvas" data-form="#userForm">
                                        <i class="ti ti-user-plus"></i> Add User
                                    </button>
                                </div>

                                <!-- Body -->

                                <div class="card-body">

                                    @if ($project->users->count())
                                        <div class="row g-4">

                                            @foreach ($project->users as $user)
                                                @php $role = $user->getRoleNames()->first(); @endphp

                                                <div class="col-md-4">

                                                    <div
                                                        class="user-card p-3 rounded-3 border bg-white h-100 position-relative">

                                                        <!-- 🔹 Top Section -->
                                                        <div class="d-flex align-items-center gap-3">

                                                            <!-- Avatar -->
                                                            <div class="avatar-lg">
                                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                                            </div>

                                                            <!-- Info -->
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0 fw-semibold">{{ $user->name }}</h6>
                                                                <small class="text-muted">
                                                                    {{ $user->email ?? 'No Email' }}
                                                                </small>
                                                            </div>

                                                            <!-- Role Badge -->
                                                            <span
                                                                class="badge role-badge
                                {{ $role === 'admin' ? 'bg-success' : 'bg-secondary' }}">
                                                                {{ ucfirst($role ?? 'user') }}
                                                            </span>
                                                        </div>

                                                        <!-- 🔹 Divider -->
                                                        <hr class="my-3">

                                                        <!-- 🔹 Actions -->
                                                        <div class="d-flex justify-content-between align-items-center">

                                                            <small class="text-muted">
                                                                Added to project
                                                            </small>

                                                            <div class="d-flex gap-2">
                                                                <button
                                                                    data-url="{{ route('projects.users.remove', [$project->id, $user->id]) }}"
                                                                    class="btn btn-sm btn-light border text-danger delete-btn">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>
                                            @endforeach

                                        </div>
                                    @else
                                        <!-- Empty State -->
                                        <div class="text-center py-5">

                                            <div class="mb-3">
                                                <i class="ti ti-users fs-1 text-muted"></i>
                                            </div>

                                            <h6 class="fw-semibold">No Users Assigned</h6>

                                            <p class="text-muted small mb-3">
                                                Add users to collaborate on this project.
                                            </p>

                                            <button class="btn btn-sm btn-primary" data-bs-toggle="offcanvas"
                                                data-bs-target="#userCanvas" data-form="#userForm">
                                                <i class="ti ti-user-plus"></i> Add User
                                            </button>

                                        </div>
                                    @endif

                                </div>
                            </div>

                        </div>
                        <!-- ✅ HISTORY -->
                        <div class="tab-pane" id="project-history">
                            <div class="card shadow-sm border-0">

                                <div
                                    class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold mb-0">Activity History</h5>
                                    <span class="text-muted small">{{ count($activityLog) }} records</span>
                                </div>

                                <div class="card-body">

                                    {{-- 🔥 Timeline View --}}
                                    <div class="timeline mb-4">
                                        @foreach ($activityLog as $log)
                                            <div class="d-flex gap-3 mb-3">

                                                {{-- Status Dot --}}
                                                <div>
                                                    <span
                                                        class="badge
                                {{ $log->status == 'success' ? 'bg-success' : ($log->status == 'error' ? 'bg-danger' : 'bg-info') }}">
                                                        ●
                                                    </span>
                                                </div>

                                                {{-- Content --}}
                                                <div class="flex-grow-1">

                                                    <div class="d-flex justify-content-between">
                                                        <strong>{{ ucfirst($log->status) }}</strong>
                                                        <small class="text-muted">
                                                            {{ $log->performed_at ? $log->performed_at->diffForHumans() : '-' }}
                                                        </small>
                                                    </div>

                                                    <div class="text-muted">
                                                        {{ $log->message }}
                                                    </div>

                                                    <small class="text-secondary">
                                                        by {{ $log->user ? $log->user->name : 'System' }}
                                                    </small>

                                                </div>

                                            </div>
                                        @endforeach
                                    </div>

                                    <hr>

                                    {{-- 🔥 Table View (Compact) --}}
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Status</th>
                                                    <th>Message</th>
                                                    <th>User</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($activityLog as $log)
                                                    <tr>

                                                        {{-- Status --}}
                                                        <td>
                                                            <span
                                                                class="badge
                                        {{ $log->status == 'success' ? 'bg-success' : ($log->status == 'error' ? 'bg-danger' : 'bg-info') }}">
                                                                {{ ucfirst($log->status) }}
                                                            </span>
                                                        </td>

                                                        {{-- Message --}}
                                                        <td class="text-muted">
                                                            {{ $log->message }}
                                                        </td>

                                                        {{-- User --}}
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <i class="ti ti-user text-secondary"></i>
                                                                {{ $log->user ? $log->user->name : 'System' }}
                                                            </div>
                                                        </td>

                                                        {{-- Date --}}
                                                        <td>
                                                            <small class="text-muted">
                                                                {{ $log->performed_at ? $log->performed_at->format('d M Y, h:i A') : '-' }}
                                                            </small>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Load More --}}
                                    <div class="text-center mt-3">
                                        <button id="load_more" class="btn btn-outline-primary px-4">
                                            Load More
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
        {{-- For Test Only --}}
        {{-- <div data-swr="company_1" data-url="/projects/10/company">

            <div data-loading>Loading...</div>
            <div data-error style="display:none;">Error loading</div>

            <h5 data-bind="company_name"></h5>
            <p data-bind="email"></p>

        </div> --}}
        <x-offcanvas id="companyCanvas" title="Company Details" formId="companyForm">

            <form id="companyForm" class="ajax-form" method="POST"
                action="{{ route('projects.company.store', $project->id) }}" enctype="multipart/form-data">

                @csrf

                {{-- Hidden Project ID --}}
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                @php
                    $company = $project->companyDetails ?? null;

                    $config = [
                        [
                            'name' => 'company_name',
                            'label' => 'Company Name',
                            'placeholder' => 'Enter company name',
                            'type' => 'text',
                            'required' => true,
                            'col' => 6,
                            'value' => $company?->company_name,
                        ],

                        [
                            'name' => 'contact_name',
                            'label' => 'Contact Name',
                            'placeholder' => 'Enter contact person',
                            'type' => 'text',
                            'col' => 6,
                            'value' => $company?->contact_name,
                        ],

                        [
                            'name' => 'email',
                            'label' => 'Email',
                            'type' => 'email',
                            'col' => 6,
                            'value' => $company?->email,
                        ],

                        [
                            'name' => 'phone',
                            'label' => 'Phone',
                            'type' => 'text',
                            'col' => 6,
                            'value' => $company?->phone,
                        ],

                        [
                            'name' => 'address_line1',
                            'label' => 'Address Line 1',
                            'type' => 'text',
                            'col' => 6,
                            'value' => $company?->address_line1,
                        ],

                        [
                            'name' => 'address_line2',
                            'label' => 'Address Line 2',
                            'type' => 'text',
                            'col' => 6,
                            'value' => $company?->address_line2,
                        ],

                        [
                            'name' => 'city',
                            'label' => 'City',
                            'type' => 'text',
                            'col' => 4,
                            'value' => $company?->city,
                        ],

                        [
                            'name' => 'state',
                            'label' => 'State',
                            'type' => 'text',
                            'col' => 4,
                            'value' => $company?->state,
                        ],

                        [
                            'name' => 'country',
                            'label' => 'Country',
                            'type' => 'text',
                            'col' => 4,
                            'value' => $company?->country,
                        ],

                        [
                            'name' => 'postal_code',
                            'label' => 'Postal Code',
                            'type' => 'text',
                            'col' => 6,
                            'value' => $company?->postal_code,
                        ],

                        [
                            'name' => 'logo',
                            'label' => 'Company Logo',
                            'type' => 'file',
                            'col' => 6,
                        ],
                    ];
                @endphp

                <x-form.fields :config="$config" />

            </form>

        </x-offcanvas>
        {{-- add project users --}}

        <x-offcanvas id="userCanvas" title="Project User" formId="userForm">

            <form id="userForm" class="ajax-form" method="POST"
                action="{{ route('projects.users.add', $project->id) }}">

                @csrf

                <input type="hidden" name="project_id" value="{{ $project->id }}">

                @php
                    $config = [
                        [
                            'name' => 'user_ids[]',
                            'label' => 'Select User',
                            'type' => 'select',
                            'multiple' => true,
                            'options' => $allUsers ?? [],
                            'required' => true,
                            'col' => 6,
                        ],
                    ];
                @endphp

                <x-form.fields :config="$config" />

            </form>

        </x-offcanvas>

    </div>


@endsection
