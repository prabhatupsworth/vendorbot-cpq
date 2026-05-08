@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="page-title">Pipedrive<span class="count-title">List</span></h4>
                            </div>
                            <div class="col-4 text-end">
                                <div class="head-icons">
                                    <a href="manage-users.html" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-original-title="Refresh"><i class="ti ti-refresh-dot"></i></a>
                                    <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-original-title="Collapse" id="collapse-header"><i
                                            class="ti ti-chevrons-up"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Settings Info -->
                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4 class="fw-semibold mb-3">Connected Accounts</h4>
                                </div>

                                <div class="col-md-8">
                                    <div class="d-flex align-items-center flex-wrap row-gap-2 justify-content-sm-end">

                                        <a href="javascript:void(0);" class="btn btn-primary mb-3"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvas_add_pipedrive"><i
                                                class="ti ti-square-rounded-plus me-2"></i>Add
                                            New</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <!-- App -->
                                @foreach ($accounts as $account)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <img class="rounded-circle" width="50"
                                                        src="{{ asset('template/assets/img/icons/pipedrive.png') }}"
                                                        alt="Icon">
                                                    <div>
                                                        <button
                                                            class="btn btn-sm btn-icon btn-primary rounded-pill edit-btn"
                                                            data-bs-toggle="offcanvas"
                                                            data-bs-target="#offcanvas_add_pipedrive"
                                                            data-id="{{ $account->id }}"
                                                            data-name="{{ $account->account_name }}"
                                                            data-url="{{ $account->base_url }}">
                                                            <i class="ti ti-edit text-white"></i>
                                                        </button>
                                                        <button
                                                            class="btn btn-light btn-icon btn-sm rounded-pill view-details"
                                                            data-bs-toggle="offcanvas"
                                                            data-bs-target="#offcanvas_view_pipedrive"
                                                            data-id="{{ $account->id }}">
                                                            <i class="ti ti-eye text-muted"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <p class="mb-0">{{ $account->account_name }}</p>

                                                    <div class="connect-btn">
                                                        @if ($account->is_verified)
                                                            <span class="badge badge-soft-success">Connected</span>
                                                        @else
                                                            <a href="{{ route('settings.pipedrive.connect', $account->id) }}"
                                                                class="badge border bg-white text-default">Test
                                                                Connection</a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div>
                                                    @if ($account->is_verified)
                                                        <div class="d-flex gap-2">
                                                            @if ($account->sync_stages)
                                                                <button class="btn btn-outline-success btn-sm sync-btn"
                                                                    data-id="{{ $account->id }}" data-type="stages">
                                                                    <i class="fas fa-sync-alt me-2"></i> Re-sync Stages
                                                                </button>
                                                            @else
                                                                <button class="btn btn-outline-danger btn-sm sync-btn"
                                                                    data-id="{{ $account->id }}" data-type="stages">
                                                                    <i class="fas fa-sync-alt me-2"></i> Sync Stages
                                                                </button>
                                                            @endif

                                                            @if ($account->sync_fields)
                                                                <button class="btn btn-outline-success btn-sm sync-btn"
                                                                    data-id="{{ $account->id }}" data-type="fields">
                                                                    <i class="fas fa-sync-alt me-2"></i> Re-sync Fields

                                                                </button>
                                                            @else
                                                                <button class="btn btn-outline-info btn-sm sync-btn"
                                                                    data-id="{{ $account->id }}" data-type="fields">
                                                                    <i class="fas fa-sync-alt me-2"></i> Sync Fields
                                                                </button>
                                                            @endif

                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- /App -->
                            </div>
                        </div>
                    </div>
                    <!-- /Settings Info -->

                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add_pipedrive">
            <div class="offcanvas-header border-bottom">
                <h5 class="fw-semibold" id="offcanvasTitle">Add Pipedrive Account</h5>
                <button type="button"
                    class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                    data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                <form id="pipedriveForm" action="{{ route('settings.pipedrive.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        <!-- ACCOUNT NAME -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="col-form-label">Account Name <span class="text-danger">*</span></label>
                                <input type="text" name="account_name" id="account_name"
                                    class="form-control @error('account_name') is-invalid @enderror"
                                    value="{{ old('account_name') }}" required>

                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- API KEY -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="col-form-label">API Key <span class="text-danger">*</span></label>
                                <div class="icon-form-end">
                                    <span class="form-icon"><i class="ti ti-eye-off"></i></span>
                                    <input type="password" name="api_key"
                                        class="form-control @error('api_key') is-invalid @enderror"
                                        value="{{ old('api_key') }}" required>
                                </div>

                                @error('api_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- BASE URL -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="col-form-label">Base URL <span class="text-danger">*</span></label>
                                <input id="base_url" type="text" name="base_url"
                                    class="form-control @error('base_url') is-invalid @enderror"
                                    placeholder="https://yourcompany.pipedrive.com" value="{{ old('base_url') }}"
                                    required>

                                @error('base_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Add</button>
                    </div>
                </form>
            </div>

        </div>

        {{-- View Pipedrive Account Offcanvas --}}
        <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_view_pipedrive">
            <div class="offcanvas-header border-bottom">
                <h5 class="fw-semibold">View Pipedrive Account</h5>
                <button type="button"
                    class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                    data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                <div class="card">

                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-bottom mb-3">
                            <li class="nav-item"><a class="nav-link active" href="#bottom-tab0"
                                    data-bs-toggle="tab">Overview</a></li>
                            <li class="nav-item"><a class="nav-link" href="#bottom-tab1" data-bs-toggle="tab">Stages</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#bottom-tab2" data-bs-toggle="tab">Fields</a>
                            </li>

                            <li class="nav-item"><a class="nav-link" href="#bottom-tab3"
                                    data-bs-toggle="tab">History</a>
                            </li>

                        </ul>

                        <div class="tab-content">

                            <div class="tab-pane show active" id="bottom-tab0">

                                <!-- 🔹 Account Header -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="mb-0" id="accountName">--</h5>
                                        <small class="text-muted" id="accountUrl">--</small>
                                    </div>

                                    <!-- Status -->
                                    <div id="accountStatus">
                                        <span class="badge bg-secondary">--</span>
                                    </div>
                                </div>

                                <hr>

                                <!-- 🔹 Info Grid -->
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <div class="p-3 border rounded bg-light">
                                            <small class="text-muted">Sync Stages</small>
                                            <p class="mb-0" id="syncStages">
                                                <span class="badge bg-secondary">--</span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="p-3 border rounded bg-light">
                                            <small class="text-muted">Sync Fields</small>
                                            <p class="mb-0" id="syncFields">
                                                <span class="badge bg-secondary">--</span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="p-3 border rounded bg-light">
                                            <small class="text-muted">Created At</small>
                                            <p class="mb-0 fw-semibold" id="createdAt">--</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="p-3 border rounded bg-light">
                                            <small class="text-muted">Last Updated</small>
                                            <p class="mb-0 fw-semibold" id="updatedAt">--</p>
                                        </div>
                                    </div>

                                </div>

                                <hr>

                                <!-- 🔹 Actions -->
                                <div class="d-flex gap-2 mt-3">

                                    <button class="btn btn-outline-danger btn-sm sync-btn"
                                        id="syncStagesBtn"data-type="stages" data-id="">
                                        <i class="ti ti-refresh me-1"></i> Sync Stages
                                    </button>

                                    <button class="btn btn-outline-info btn-sm sync-btn" id="syncFieldsBtn"
                                        data-type="fields" data-id="">
                                        <i class="ti ti-refresh me-1"></i> Sync Fields
                                    </button>

                                </div>

                            </div>
                            <div class="tab-pane show" id="bottom-tab1">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Pipeline</th>
                                            </tr>
                                        </thead>
                                        <tbody id="stagesTableBody">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="bottom-tab2">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Field Key</th>
                                                <th>Field Type</th>
                                            </tr>
                                        </thead>
                                        <tbody id="fieldsTableBody">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="bottom-tab3">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Status</th>
                                                <th>Message</th>
                                                <th>Performed By</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="historyTableBody">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <a id="load_more" class="btn btn-danger nav-link mt-2" href="#">Load
                                        More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.view-details').forEach(btn => {
                btn.addEventListener('click', function() {

                    let id = this.dataset.id;

                    // 🔹 Elements
                    const accountName = document.getElementById('accountName');
                    const accountUrl = document.getElementById('accountUrl');
                    const accountStatus = document.getElementById('accountStatus');
                    const syncStages = document.getElementById('syncStages');
                    const syncFields = document.getElementById('syncFields');


                    const stagesTableBody = document.getElementById('stagesTableBody');
                    const fieldsTableBody = document.getElementById('fieldsTableBody');

                    // 🔹 Loading state
                    stagesTableBody.innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';
                    fieldsTableBody.innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';

                    fetch(`/settings/pipedrive/${id}/details`)
                        .then(res => {
                            if (!res.ok) throw new Error('Network response failed');
                            return res.json();
                        })
                        .then(data => {
                            renderHistoryTable(data.activityLog);
                            // 🔥 ACCOUNT DETAILS
                            accountName.textContent = data.account.account_name || '--';
                            accountUrl.textContent = data.account.base_url || '--';

                            accountStatus.innerHTML = data.account.is_verified ?
                                '<span class="badge bg-outline-success">Connected</span>' :
                                '<span class="badge bg-outline-danger">Not Connected</span>';

                            syncStages.innerHTML = data.account.sync_stages ?
                                '<span class="badge bg-outline-success">Synced</span>' :
                                '<span class="badge bg-outline-secondary">Not Synced</span>';

                            syncFields.innerHTML = data.account.sync_fields ?
                                '<span class="badge bg-outline-success">Synced</span>' :
                                '<span class="badge bg-outline-secondary">Not Synced</span>';

                            document.getElementById('createdAt').textContent = data.account.created_at ?
                                moment(data.account.created_at).format('DD MMM YYYY, hh:mm A') :
                                '--';

                            document.getElementById('updatedAt').textContent = data.account.updated_at ?
                                moment(data.account.updated_at).format('DD MMM YYYY, hh:mm A') :
                                '--';

                            document.getElementById('syncStagesBtn').dataset.id = id;
                            document.getElementById('syncFieldsBtn').dataset.id = id;
                            // 🔥 CLEAR TABLES
                            stagesTableBody.innerHTML = '';
                            fieldsTableBody.innerHTML = '';

                            document.getElementById('load_more').href = `/history/pipedrive/${id}`;

                            // 🔥 STAGES TABLE
                            if (data.stages && data.stages.length > 0) {
                                data.stages.forEach(stage => {
                                    let row = `
                            <tr>
                                <td>${stage.name} (${stage.stage_id})</td>
                                <td>${stage.pipeline.name}</td>
                            </tr>
                        `;
                                    stagesTableBody.insertAdjacentHTML('beforeend', row);
                                });
                            } else {
                                stagesTableBody.innerHTML =
                                    '<tr><td colspan="4" class="text-center text-muted">No stages found</td></tr>';
                            }

                            // 🔥 FIELDS TABLE
                            if (data.fields && data.fields.length > 0) {
                                data.fields.forEach(field => {
                                    let row = `
                            <tr>
                                <td>${field.name}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="field-key">${field.field_key}</span>

                                        <button
                                            class="btn btn-light btn-sm copy-btn"
                                            data-value="${field.field_key}"
                                            title="Copy">
                                            <i class="ti ti-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td><span class="badge bg-info">${field.field_type}</span></td>
                            </tr>
                        `;
                                    fieldsTableBody.insertAdjacentHTML('beforeend', row);
                                });
                            } else {
                                fieldsTableBody.innerHTML =
                                    '<tr><td colspan="4" class="text-center text-muted">No fields found</td></tr>';
                            }

                        })
                        .catch(error => {
                            console.error(error);

                            stagesTableBody.innerHTML =
                                '<tr><td colspan="4" class="text-center text-danger">Failed to load data</td></tr>';
                            fieldsTableBody.innerHTML =
                                '<tr><td colspan="4" class="text-center text-danger">Failed to load data</td></tr>';
                        });
                });
            });
        </script>

        <script>
            document.addEventListener('click', function(e) {

                const btn = e.target.closest('.edit-btn');
                if (!btn) return;
                // 👉 Change title & button
                document.getElementById('offcanvasTitle').innerText = 'Edit Pipedrive Account';
                document.getElementById('submitBtn').innerText = 'Update';

                // 👉 Set form action
                const form = document.getElementById('pipedriveForm');
                form.action = `/settings/pipedrive/${btn.dataset.id}/update`;

                // 👉 Fill values
                document.querySelector('#account_name').value = btn.dataset.name;
                document.querySelector('#base_url').value = btn.dataset.url;

            });
        </script>

        {{-- Copy to Clipboard Script --}}
        <script>
            // 🔥 Copy click handler (works for dynamic elements)
            document.addEventListener('click', function(e) {

                const btn = e.target.closest('.copy-btn');
                if (!btn) return;

                const value = btn.getAttribute('data-value');

                // Copy to clipboard
                navigator.clipboard.writeText(value).then(() => {

                    // ✅ UI feedback
                    btn.innerHTML = '<i class="ti ti-check text-success"></i>';

                    setTimeout(() => {
                        btn.innerHTML = '<i class="ti ti-copy"></i>';
                    }, 1500);

                    // ✅ Optional SweetAlert (you already use it)
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Copied!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                }).catch(() => {
                    console.error('Copy failed');
                });
            });
        </script>

        {{-- Sync Buttons Script --}}
        <script>
            document.addEventListener('click', function(e) {

                const btn = e.target.closest('.sync-btn');
                if (!btn) return;

                const id = btn.dataset.id;
                const type = btn.dataset.type;

                const url = type === 'stages' ?
                    `/settings/pipedrive/${id}/sync-stages` :
                    `/settings/pipedrive/${id}/sync-fields`;

                const originalHTML = btn.innerHTML;

                // 🔥 Loader
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Syncing...';
                btn.disabled = true;

                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        isSuccess = true;
                        btn.classList.remove('btn-outline-danger', 'btn-outline-info');
                        btn.classList.add('btn-outline-success');
                        btn.innerHTML = type === 'stages' ?
                            '<i class="fas fa-sync-alt me-2"></i> Re-sync Stages' :
                            '<i class="fas fa-sync-alt me-2"></i> Re-sync Fields';

                        // 🔥 Optional: reload modal / data
                        // const viewBtn = document.querySelector(`.view-details[data-id="${id}"]`);
                        // if (viewBtn) viewBtn.click();

                        // 🔥 SweetAlert
                        // if (typeof Swal !== 'undefined') {
                        //     Swal.fire({
                        //         toast: true,
                        //         position: 'top-end',
                        //         icon: 'success',
                        //         title: data.message || 'Synced successfully',
                        //         showConfirmButton: false,
                        //         timer: 1500
                        //     });
                        // }

                    })
                    .catch(() => {
                        btn.innerHTML = '<i class="fas fa-times text-danger me-2"></i> Failed';
                    })
                    .finally(() => {
                        setTimeout(() => {

                            // ❌ only revert if NOT success
                            if (!isSuccess) {
                                btn.innerHTML = originalHTML;
                            }

                            btn.disabled = false;

                        }, 1500);
                    });

            });
        </script>


        <script>
            function renderHistoryTable(data) {
                const tbody = document.getElementById('historyTableBody');
                tbody.innerHTML = '';

                if (!data.length) {
                    tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-muted">No records found</td>
            </tr>
        `;
                    return;
                }

                data.forEach(item => {

                    // 👉 Status badge
                    let statusBadge = '';
                    if (item.status === 'success') {
                        statusBadge = `<span class="badge bg-success">Success</span>`;
                    } else if (item.status === 'failed') {
                        statusBadge = `<span class="badge bg-danger">Failed</span>`;
                    } else {
                        statusBadge = `<span class="badge bg-secondary">${item.status}</span>`;
                    }

                    // 👉 Format date
                    const date = new Date(item.performed_at).toLocaleString();

                    const row = `
            <tr>
                <td>${statusBadge}</td>
                <td>${item.message ?? '-'}</td>
                <td>${item.user?.name ?? 'System'}</td>
                <td>${date}</td>
            </tr>
        `;

                    tbody.insertAdjacentHTML('beforeend', row);
                });
            }
        </script>
    @endpush
@endsection
