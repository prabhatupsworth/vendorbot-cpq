@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-4">
                        <h4 class="page-title">{{ ucfirst($module) }} History</h4>
                    </div>
                    <div class="col-sm-8 text-sm-end">
                        <div class="head-icons">
                            <a href="{{ route('history.module', ['module' => $module, 'recordId' => $recordId]) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-original-title="Refresh"><i class="ti ti-refresh-dot"></i></a>
                            <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-chevrons-up"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Status</th>
                            <th>Message</th>
                            <th>User</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ ucwords(str_replace('_', ' ', $log->action)) }}</td>

                                <td>
                                    @if ($log->status == 'success')
                                        <span class="badge bg-success">Success</span>
                                    @elseif($log->status == 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $log->status }}</span>
                                    @endif
                                </td>

                                <td>{{ $log->message ?? '-' }}</td>

                                <td>{{ $log->user->name ?? 'System' }}</td>

                                <td>{{ \Carbon\Carbon::parse($log->performed_at)->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No records found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                <x-custom-pagination :paginator="$logs" />
            </div>
        </div>
    </div>
@endsection
