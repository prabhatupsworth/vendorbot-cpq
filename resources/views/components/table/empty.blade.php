@props([
    'colspan' => 1,
    'title' => 'No Data Found',
    'subtitle' => 'No records available.',
])

<tr>
    <td colspan="{{ $colspan }}">

        <div class="text-center py-5">

            <div class="avatar avatar-xxl bg-light rounded-circle mx-auto mb-3">

                <i class="ti ti-inbox fs-1 text-primary"></i>

            </div>

            <h5 class="fw-semibold">
                {{ $title }}
            </h5>

            <p class="text-muted mb-0">
                {{ $subtitle }}
            </p>

        </div>

    </td>
</tr>
