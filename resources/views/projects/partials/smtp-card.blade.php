<div class="col-md-6 smtp-item" data-id="{{ $smtp->id }}">

    <div class="smtp-card p-3 border rounded-3 bg-white h-100">

        <!-- 🔹 Top -->
        <div class="d-flex justify-content-between align-items-center mb-2">

            <span class="badge bg-primary text-uppercase">
                {{ $smtp->type }}
            </span>

            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-light border edit-form" data-type="edit" data-bs-toggle="offcanvas"
                    data-bs-target="#smtpCanvas" data-data='@json($smtp)' data-form="#smtpForm"
                    data-method="PUT" data-url="{{ route('projects.smtp.update', [$projectId, $smtp->id]) }}">
                    <i class="ti ti-edit"></i>
                </button>

                <button class="btn btn-sm btn-light border text-danger delete-btn"
                    data-url="{{ route('projects.smtp.delete', [$projectId, $smtp->id]) }}">
                    <i class="ti ti-trash"></i>
                </button>
            </div>

        </div>

        <!-- 🔹 Host -->
        <h6 class="mb-1 fw-semibold">{{ $smtp->host }}</h6>

        <!-- 🔹 Details -->
        <div class="small text-muted">

            <div>Port: {{ $smtp->port }}</div>
            <div>User: {{ $smtp->username }}</div>
            <div>Encryption: {{ $smtp->encryption ?? 'None' }}</div>

        </div>

        <!-- 🔹 Divider -->
        <hr class="my-2">

        <!-- 🔹 Footer -->
        <div class="d-flex justify-content-between align-items-center small">

            <div>
                <strong>{{ $smtp->from_name }}</strong><br>
                <span class="text-muted">{{ $smtp->from_email }}</span>
            </div>

            @if ($smtp->is_active)
                <span class="badge bg-success">Active</span>
            @else
                <span class="badge bg-primary text-uppercase">
                    Inactive
                </span>
            @endif

        </div>

    </div>

</div>
