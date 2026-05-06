<tr class="project-list" data-id={{ $project->id }}>
    <td>{{ $project->name }}</td>
    <td>{{ $project->event_name }}</td>
    <td>{{ $project->flow_type }}</td>
    <td>{{ $project->invoice_enabled ? 'Yes' : 'No' }}</td>
    <td>{{ $project->pipedriveAccount ? $project->pipedriveAccount->account_name : 'N/A' }}
    </td>
    <td>{{ $project->invoiceAccount ? $project->invoiceAccount->type : 'N/A' }}
    </td>
    {{-- Sync Status --}}
    {{-- <td>
                                                    @if ($project->pipedrive_sync_status)
                                                        <span class="badge bg-success">Synced</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Not Synced</span>
                                                    @endif
                                                </td> --}}

    {{-- Connected At --}}
    {{-- <td>
                                                    {{ $project->plugin_connected_at ? $project->plugin_connected_at->format('d M Y, h:i A') : '-' }}
                                                </td> --}}

    {{-- Last Ping --}}
    {{-- <td>
                                                    {{ $project->plugin_last_ping_at ? $project->plugin_last_ping_at->diffForHumans() : 'Never' }}
                                                </td> --}}

    {{-- Plugin Status --}}
    {{-- <td>
                                                    @if ($project->plugin_connected)
                                                        <span class="badge bg-success">Connected</span>
                                                    @else
                                                        <span class="badge bg-danger">Disconnected</span>
                                                    @endif
                                                </td> --}}
    <td>{{ $project->created_at->format('Y-m-d H:i:s') }}</td>
    <td class="text-end">
        <div class="dropdown table-action">
            <a href="#" class="action-icon show" data-bs-toggle="dropdown" aria-expanded="true"><i
                    class="fa fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right"
                style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(-104px, 35px, 0px);"
                data-popper-placement="bottom-start" data-popper-reference-hidden="" data-popper-escaped="">
                {{-- <a class="dropdown-item viewProject"
                                                                data-id="{{ $project->id }}" data-bs-toggle="offcanvas"
                                                                data-bs-target="#offcanvas_view_project" href="#"><i
                                                                    class="ti ti-eye text-success"></i> View</a> --}}
                <a class="dropdown-item" href="{{ route('projects.show', $project->id) }}"><i
                        class="ti ti-eye text-success"></i> View</a>

                <a href="#" class="dropdown-item edit-form" data-bs-toggle="offcanvas"
                    data-bs-target="#projectCanvas" data-type="edit"
                    data-url="{{ route('projects.update', $project->id) }}" data-method="PUT"
                    data-data='@json($project)' data-form="#projectForm">
                    <i class="ti ti-edit text-blue"></i> Edit
                </a>
                <a class="dropdown-item delete-btn" href="#"  data-url="{{ route('projects.destroy',  $project->id) }}"><i
                        class="ti ti-trash text-danger"></i> Delete</a>
            </div>
        </div>
    </td>
</tr>
