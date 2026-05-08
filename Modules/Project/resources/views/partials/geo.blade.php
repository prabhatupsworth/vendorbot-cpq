<div class="geo-card p-3 border rounded-3 bg-white">

    <!-- 🔹 Top -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div class="d-flex align-items-center gap-2">
            <i class="ti ti-map-pin text-primary"></i>
            <h6 class="mb-0 fw-semibold">Geo Filter Active</h6>
        </div>

        <!-- Status -->
        @if ($geo->status)
            <span class="badge bg-success">Enabled</span>
        @else
            <span class="badge bg-secondary">Disabled</span>
        @endif

    </div>

    <!-- 🔹 Range Info -->
    <div class="row text-center g-3">

        <div class="col-md-6">
            <div class="p-2 rounded bg-light">
                <small class="text-muted d-block">Latitude Range</small>
                <strong>{{ $geo->latitude_range }}</strong>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-2 rounded bg-light">
                <small class="text-muted d-block">Longitude Range</small>
                <strong>{{ $geo->longitude_range }}</strong>
            </div>
        </div>

    </div>

    <!-- 🔹 Distance Info -->
    <div class="mt-3 text-center">
        <small class="text-muted">
            Approx Coverage:
            <strong>{{ number_format($geo->latitude_range * 111, 1) }} KM</strong>
        </small>
    </div>

    <!-- 🔹 Divider -->
    <hr class="my-3">

    <!-- 🔹 Footer -->
    <div class="d-flex justify-content-between align-items-center">

        <small class="text-muted">
            Used for location-based filtering
        </small>

    </div>

</div>
