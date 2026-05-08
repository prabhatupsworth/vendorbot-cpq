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

        .geo-card {
            transition: all 0.2s ease;
        }

        .geo-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.06);
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
                            <a href="{{ route('projects.show', $project->id) }}" data-bs-toggle="tooltip"
                                data-bs-placement="top" data-bs-original-title="Refresh"><i
                                    class="ti ti-refresh-dot"></i></a>
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
                                    {{ $project->website_url }}
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
                                    <a class="nav-link d-flex align-items-center gap-2" data-bs-toggle="tab"
                                        href="#project-smtp">
                                        <i class="ti ti-mail"></i>SMTP
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  d-flex align-items-center gap-2" data-bs-toggle="tab"
                                        href="#project-geo">
                                        <i class="ti ti-map-pin"></i>Geo Filter</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  d-flex align-items-center gap-2" data-bs-toggle="tab"
                                        href="#project-field-mapping">
                                        <i class="ti ti-arrows-exchange"></i>Field Mapping</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link  d-flex align-items-center gap-2" data-bs-toggle="tab"
                                        href="#project-automation">
                                        <i class="ti ti-arrows-exchange"></i>Stage Mapping</a>
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
                                                    {{-- 🔥 Pipeline --}}
                                                    <div class="d-flex align-items-center gap-2">

                                                        <span class="badge bg-info-subtle text-info border">
                                                            <i class="ti ti-git-branch me-1"></i>
                                                            Pipeline
                                                        </span>

                                                        <span class="fw-medium">
                                                            {{ $project?->pipeline?->name ?? 'No Pipeline Selected' }}
                                                        </span>

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

                                <div class="card-body" id="company-section">

                                    @include('project::partials.company', ['company' => $company])

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
                                        <div class="row g-4" id="user-card">

                                            @foreach ($project->users as $user)
                                                @php $role = $user->getRoleNames()->first(); @endphp

                                                @include('project::partials.users-card', [
                                                    'user' => $user,
                                                    'projectId' => $project->id,
                                                    'role' => $role,
                                                ])
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

                        <div class="tab-pane fade" id="project-smtp">
                            <div class="card border-0 shadow-sm">

                                <!-- 🔹 Header -->
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 fw-semibold">SMTP Settings</h5>

                                    <button class="btn btn-sm btn-primary create-form" data-bs-toggle="offcanvas"
                                        data-bs-target="#smtpCanvas">
                                        <i class="ti ti-plus"></i> Add SMTP
                                    </button>
                                </div>

                                <!-- 🔹 Body -->
                                <div class="card-body">

                                    @if ($project->smtps->count())
                                        <div class="row g-3" id="smtp-section">
                                            @foreach ($project->smtps as $smtp)
                                                @include('project::partials.smtp-card', [
                                                    'smtp' => $smtp,
                                                    'projectId' => $project->id,
                                                ])
                                            @endforeach
                                        </div>
                                    @else
                                        <!-- 🔹 Empty -->
                                        <div class="text-center py-5">
                                            <i class="ti ti-mail fs-1 text-muted"></i>
                                            <p class="mt-2 text-muted">No SMTP configured</p>

                                            <button class="btn btn-primary btn-sm create-form" data-bs-toggle="offcanvas"
                                                data-bs-target="#smtpCanvas">
                                                <i class="ti ti-plus"></i> Add SMTP
                                            </button>
                                        </div>
                                    @endif

                                </div>

                            </div>


                        </div>

                        <div class="tab-pane fade" id="project-geo">
                            <div class="card border-0 shadow-sm">

                                <!-- 🔹 Header -->
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 fw-semibold">GEO Filter</h5>

                                    @if ($project->geoFilter)
                                        <button class="btn btn-sm btn-primary edit-form" data-bs-toggle="offcanvas"
                                            data-bs-target="#geoCanvas" data-type="edit"
                                            data-url="{{ route('projects.geo.store', $project->id) }}" data-method="POST"
                                            data-data='@json($project->geoFilter)' data-form="#geoForm">
                                            <i class="ti ti-edit"></i> Edit Geo Filter
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-success" data-bs-toggle="offcanvas"
                                            data-bs-target="#geoCanvas" data-form="#geoForm">
                                            <i class="ti ti-plus"></i> Add Geo Filter
                                        </button>
                                    @endif
                                </div>
                                <div class="card-body">

                                    @if ($project->geoFilter)
                                        @php $geo = $project->geoFilter; @endphp
                                        <div id="geo-section">
                                            @include('project::partials.geo', ['geo' => $geo])
                                        </div>
                                    @else
                                        <!-- 🔹 EMPTY STATE -->
                                        <div class="text-center py-5">

                                            <div class="mb-3">
                                                <i class="ti ti-current-location fs-1 text-muted"></i>
                                            </div>

                                            <h6 class="fw-semibold">No Geo Filter Configured</h6>

                                            <p class="text-muted small mb-3">
                                                Restrict or filter users based on geographic radius.
                                            </p>

                                            <button class="btn btn-primary" data-bs-toggle="offcanvas"
                                                data-bs-target="#geoCanvas">

                                                <i class="ti ti-map"></i> Configure Geo Filter
                                            </button>

                                        </div>
                                    @endif

                                </div>
                            </div>

                        </div>


                        <div class="tab-pane fade" id="project-field-mapping">

                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                                <!-- Header -->
                                <div
                                    class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">

                                    <div>

                                        <h5 class="fw-bold mb-1">
                                            Field Mapping
                                        </h5>

                                        <small class="text-muted">
                                            Map Pipedrive fields with your internal system fields.
                                        </small>

                                    </div>

                                    <button class="btn btn-primary d-flex align-items-center gap-2 px-3"
                                        data-bs-toggle="offcanvas" data-bs-target="#fieldMappingCanvas">

                                        <i class="ti ti-plus"></i>

                                        Add Mapping

                                    </button>

                                </div>

                                <!-- Body -->
                                <div class="card-body p-0">

                                    @if ($project->fieldMappings->count())
                                        <div class="table-responsive">

                                            <table class="table align-middle table-hover mb-0">

                                                <thead class="table-light">

                                                    <tr>
                                                        <th>
                                                            System Field
                                                        </th>

                                                        <th>
                                                            Pipedrive Field
                                                        </th>

                                                        <th width="150" class="text-end pe-4">
                                                            Actions
                                                        </th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    @foreach ($project->fieldMappings as $key => $mapping)

                                                       @include('project::partials.field-mapping',['projectId'=>$project->id,'mapping'=>$mapping])

                                                    @endforeach

                                                </tbody>

                                            </table>

                                        </div>
                                    @else
                                        <!-- Empty State -->

                                        <div class="text-center py-5 px-4">

                                            <div class="mx-auto mb-4 rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                style="width:90px;height:90px;">

                                                <i class="ti ti-arrows-exchange fs-1 text-primary"></i>

                                            </div>

                                            <h4 class="fw-bold mb-2">

                                                No Field Mappings Found

                                            </h4>

                                            <p class="text-muted mb-4 mx-auto" style="max-width:500px;">

                                                Create mappings between your internal system fields
                                                and Pipedrive fields to sync and normalize data properly.

                                            </p>

                                            <button class="btn btn-primary px-4" data-bs-toggle="offcanvas"
                                                data-bs-target="#fieldMappingCanvas">

                                                <i class="ti ti-plus me-1"></i>

                                                Create First Mapping

                                            </button>

                                        </div>
                                    @endif

                                </div>

                            </div>

                        </div>


                        <div class="tab-pane fade" id="project-automation">

                            <div class="card border-0 shadow-sm">

                                <!-- Header -->
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">

                                    <div>
                                        <h5 class="fw-semibold mb-1">
                                            Stage Actions
                                        </h5>

                                        <small class="text-muted">
                                            Configure actions for each pipeline stage.
                                        </small>
                                    </div>

                                    <button class="btn btn-primary" data-bs-toggle="offcanvas"
                                        data-bs-target="#automationCanvas">

                                        <i class="ti ti-plus me-1"></i>
                                        Add Stage Action

                                    </button>

                                </div>

                                <!-- Body -->
                                <div class="card-body">

                                    @if ($project->stageActions->count())
                                        <div class="table-responsive">

                                            <table class="table align-middle table-hover mb-0">

                                                <thead class="table-light">

                                                    <tr>
                                                        <th>Pipeline Stage</th>
                                                        <th>Trigger</th>
                                                        <th>Action Type</th>
                                                        <th>Status</th>
                                                        <th class="text-end">Actions</th>
                                                    </tr>

                                                </thead>

                                                <tbody id="satege-mapping">

                                                    @foreach ($project->stageActions as $key => $automation)
                                                        @include('project::partials.stage-mapping', [
                                                            'projectId' => $project->id,
                                                            'automation' => $automation,
                                                        ])
                                                    @endforeach

                                                </tbody>

                                            </table>

                                        </div>
                                    @else
                                        <!-- Empty State -->

                                        <div class="text-center py-5">

                                            <div class="mx-auto mb-4 rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                style="width:90px;height:90px;">

                                                <i class="ti ti-git-branch fs-1 text-primary"></i>

                                            </div>

                                            <h4 class="fw-bold mb-2">
                                                No Stage Actions Created
                                            </h4>

                                            <p class="text-muted mb-4 mx-auto" style="max-width:500px;">

                                                Create automations that trigger actions whenever
                                                a deal enters a selected pipeline stage.

                                            </p>

                                            <button class="btn btn-primary px-4" data-bs-toggle="offcanvas"
                                                data-bs-target="#automationCanvas">

                                                <i class="ti ti-plus me-1"></i>

                                                Create Stage Action

                                            </button>

                                        </div>
                                    @endif

                                </div>

                            </div>

                        </div>

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
                                        <a href="{{ route('history.module', ['module' => 'projects', 'recordId' => $project->id]) }}"
                                            class="btn btn-outline-primary px-4">
                                            Load More
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

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


        <x-offcanvas id="smtpCanvas" title="SMTP Settings" formId="smtpForm">

            <form id="smtpForm" class="ajax-form" method="POST"
                action="{{ route('projects.smtp.store', $project->id) }}">

                @csrf
                @php
                    $config = [
                        [
                            'name' => 'type',
                            'label' => 'Type',
                            'type' => 'select',

                            'options' => \App\Enums\SmtpType::options(), // ✅ direct use

                            'disabledOptions' => $existingTypes ?? [],

                            'required' => true,
                            'col' => 6,
                        ],

                        [
                            'name' => 'host',
                            'label' => 'Host',
                            'type' => 'text',
                            'col' => 6,
                            'required' => true,
                        ],

                        [
                            'name' => 'port',
                            'label' => 'Port',
                            'type' => 'number',
                            'col' => 6,
                        ],

                        [
                            'name' => 'username',
                            'label' => 'Username',
                            'type' => 'text',
                            'col' => 6,
                        ],

                        [
                            'name' => 'password',
                            'label' => 'Password',
                            'type' => 'password',
                            'col' => 6,
                        ],

                        [
                            'name' => 'encryption',
                            'label' => 'Encryption',
                            'type' => 'select',
                            'options' => [
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                            ],
                            'col' => 6,
                        ],

                        [
                            'name' => 'from_email',
                            'label' => 'From Email',
                            'type' => 'email',
                            'col' => 6,
                        ],

                        [
                            'name' => 'from_name',
                            'label' => 'From Name',
                            'type' => 'text',
                            'col' => 6,
                        ],

                        [
                            'name' => 'is_active',
                            'label' => 'Active Status',
                            'type' => 'checkbox',
                            'col' => 6,
                        ],
                    ];
                @endphp

                <x-form.fields :config="$config" />

            </form>

        </x-offcanvas>

        <x-offcanvas id="geoCanvas" title="GEO Settings" formId="geoForm">

            <form id="geoForm" class="ajax-form" method="POST"
                action="{{ route('projects.geo.store', $project->id) }}">
                @csrf
                @php
                    $config = [
                        [
                            'name' => 'latitude_range',
                            'label' => 'Latitude Range',
                            'type' => 'number',
                            'placeholder' => 'e.g. 0.03 (≈ 3 km)',
                            'col' => 6,
                        ],
                        [
                            'name' => 'longitude_range',
                            'label' => 'Longitude Range',
                            'type' => 'number',
                            'placeholder' => 'e.g. 0.03 (≈ 3 km)',
                            'col' => 6,
                        ],
                        [
                            'name' => 'status',
                            'label' => 'Enable Geo Filter',
                            'type' => 'checkbox',
                            'col' => 12,
                        ],
                    ];
                @endphp

                <x-form.fields :config="$config" />


            </form>

        </x-offcanvas>

        {{-- Test Smtp canvas --}}
        <x-offcanvas id="smtpTestCanvas" title="Test SMTP" formId="smtpTestForm">

            <form id="smtpTestForm" class="ajax-form" method="POST" action="#">

                @csrf

                {{-- 🔥 SMTP ID --}}
                <input type="hidden" name="smtp_id" id="smtp_test_id">

                @php
                    $config = [
                        [
                            'name' => 'to_email',
                            'label' => 'Recipient Email',
                            'type' => 'email',
                            'placeholder' => 'Enter recipient email',
                            'required' => true,
                            'col' => 12,
                        ],

                        [
                            'name' => 'subject',
                            'label' => 'Subject',
                            'type' => 'text',
                            'placeholder' => 'SMTP Test Mail',
                            'required' => true,
                            'col' => 12,
                        ],

                        [
                            'name' => 'message',
                            'label' => 'Message',
                            'type' => 'textarea',
                            'placeholder' => 'Write test email content...',
                            'required' => true,
                            'col' => 12,
                        ],
                    ];
                @endphp

                <x-form.fields :config="$config" />

            </form>

        </x-offcanvas>

        {{-- field mapping --}}

        <x-offcanvas id="fieldMappingCanvas" title="Field Mapping" formId="fieldMappingForm">

            <form id="fieldMappingForm" class="ajax-form" method="POST"
                action="{{ route('projects.field-mappings.store', $project->id) }}">

                @csrf

                @php

                    $config = [
                        [
                            'name' => 'system_field',
                            'label' => 'System Field',
                            'type' => 'select',
                            'options' => $systemFields ?? [],
                            'required' => true,
                            'col' => 12,
                        ],
                        [
                            'name' => 'pipedrive_field_key',
                            'label' => 'Pipedrive Field',
                            'type' => 'select',
                            'options' => $pipedriveFields ?? [],
                            'required' => true,
                            'col' => 12,
                        ],
                    ];

                @endphp

                <x-form.fields :config="$config" />

            </form>

        </x-offcanvas>

        <x-offcanvas id="automationCanvas" title="Add Stage Action" formId="stageMappingForm">

            <form id="stageMappingForm" class="ajax-form" method="POST"
                action="{{ route('projects.stages.store', $project->id) }}">

                @csrf

                @php

                    $config = [
                        [
                            'name' => 'action_type',
                            'label' => 'Action',
                            'type' => 'select',
                            'options' => $actions ?? [],
                            'required' => true,
                            'col' => 12,
                        ],
                        [
                            'name' => 'stage_id',
                            'label' => 'Stage',
                            'type' => 'select',
                            'options' => $stages ?? [],
                            'required' => true,
                            'col' => 12,
                        ],
                    ];

                @endphp

                <x-form.fields :config="$config" />

                <!-- 🔥 Dynamic Config -->
                <div id="action-config-wrapper"></div>

            </form>

        </x-offcanvas>
    </div>

    @push('scripts')
        <script>
            // 🔥 CREATE
            $(document).on("click", ".create-form", function() {

                let form = $("#smtpForm");

                // enable type select
                form.find('[name="type"]').prop("disabled", false);

                // remove hidden type input
                form.find('input[type="hidden"][name="type"]').remove();

                // reset form
                form[0].reset();

                // reset select2
                form.find('select').trigger('change');
            });


            // 🔥 EDIT
            $(document).on("click", ".edit-form", function() {

                let btn = $(this);

                let form = $(btn.data("form"));

                let data = btn.data("data");

                // disable only type select
                form.find('[name="type"]').prop("disabled", true);

                // remove old hidden input
                form.find('input[type="hidden"][name="type"]').remove();

                // hidden input so disabled select value submits
                form.append(`
            <input
                type="hidden"
                name="type"
                value="${data.type}"
            >
        `);
            });
        </script>
    @endpush
@endsection
