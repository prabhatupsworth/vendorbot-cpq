<tr class="project-list" data-id={{ $project->id }}>
    <td>{{ $project->name }}</td>
    <td><span class="badge bg-outline-success">{{ $project->event_name }}</span></td>
    <td><span class="badge bg-outline-info">{{ $project->flow_type }}</span></td>
    <td>

        @if ($project->invoice_enabled)
            <span class="badge bg-outline-success">

                <i class="ti ti-check me-1"></i>

                Enabled

            </span>
        @else
            <span class="badge bg-outline-primary">

                <i class="ti ti-x me-1"></i>

                Disabled

            </span>
        @endif

    </td>
    <td>{{ $project->pipedriveAccount ? $project->pipedriveAccount->account_name : 'N/A' }}
    </td>
    <td>{{ $project->invoiceAccount ? $project->invoiceAccount->type : 'N/A' }}
    </td>
    <td>{{ $project->created_at->format('Y-m-d H:i:s') }}</td>
    <td class="text-end">
        <div class="dropdown table-action">
            <a href="#" class="action-icon show" data-bs-toggle="dropdown" aria-expanded="true"><i
                    class="fa fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right"
                style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(-104px, 35px, 0px);"
                data-popper-placement="bottom-start" data-popper-reference-hidden="" data-popper-escaped="">

                <a class="dropdown-item" href="{{ route('projects.show', $project->id) }}"><i
                        class="ti ti-eye text-success"></i> View</a>

                <a href="#" class="dropdown-item edit-form" data-bs-toggle="offcanvas"
                    data-bs-target="#projectCanvas" data-type="edit"
                    data-url="{{ route('projects.update', $project->id) }}" data-method="PUT"
                    data-data='@json($project)' data-form="#projectForm">
                    <i class="ti ti-edit text-blue"></i> Edit
                </a>
                <a class="dropdown-item delete-btn" href="#"
                    data-url="{{ route('projects.destroy', $project->id) }}"><i class="ti ti-trash text-danger"></i>
                    Delete</a>
            </div>
        </div>
    </td>
</tr>
