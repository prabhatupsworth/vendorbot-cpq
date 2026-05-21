@forelse ($projects as $project)
    @include('project::partials.list', [
        'project' => $project,
    ])

@empty

    <tr>

        <td colspan="8" class="text-center py-5">

            <div class="d-flex flex-column align-items-center">

                <i class="ti ti-folder-off fs-1 text-muted mb-2"></i>

                <h6 class="mb-1">
                    No Projects Found
                </h6>

                <span class="text-muted">
                    Try another keyword
                </span>

            </div>

        </td>

    </tr>
@endforelse
