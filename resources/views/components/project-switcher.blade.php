@php

    $user = auth()->user();

    // super admin -> all projects
    if ($user?->hasRole('super_admin')) {

        $projects = \Modules\Project\Models\Project::orderBy('name')
            ->get();

    } else {

        // normal user -> only assigned projects
        $projects = $user?->projects()
            ->orderBy('name')
            ->get();

    }

    $currentProjectId = $user?->current_project_id;

@endphp

<div class="dropdown me-2">

    <a class="dropdown-toggle d-flex align-items-center text-capitalize" data-bs-toggle="dropdown" href="javascript:void(0);"
        aria-expanded="false">

        <i class="ti ti-briefcase me-2"></i>

        {{ auth()->user()?->currentProject?->name ?? 'Select Project' }}

    </a>

    <div class="dropdown-menu dropdown-menu-end">

        @forelse($projects as $project)
            <form action="{{ route('projects.switch') }}" method="POST">

                @csrf

                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <button type="submit"
                    class="dropdown-item text-capitalize mb-2 py-2 d-flex align-items-center justify-content-between
                    {{ $currentProjectId == $project->id ? 'active text-white' : '' }}">

                    <span>

                        {{ $project->name }}

                    </span>

                    @if ($currentProjectId == $project->id)
                        <i class="ti ti-check ms-2"></i>
                    @endif

                </button>

            </form>

        @empty

            <span class="dropdown-item text-muted">

                No Projects Found

            </span>
        @endforelse

    </div>

</div>
