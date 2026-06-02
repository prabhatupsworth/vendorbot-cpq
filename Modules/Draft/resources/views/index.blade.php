@extends('layouts.app')

@section('content')
    <div class="page-wrapper">

        <div class="content">

            {{-- PAGE HEADER --}}

            <div class="page-header mb-4">

                <div class="row align-items-center">

                    <div class="col-lg-6">

                        <h3 class="page-title fw-bold">

                            Drafts

                            <span class="badge bg-primary ms-2">
                                {{ $drafts->total() }}
                            </span>

                        </h3>

                        <p class="text-muted mb-0">
                            Manage all supplier communication drafts
                        </p>

                    </div>

                    <div class="col-lg-6 text-end">
                        @if(userCan('draft.create'))
                        <a href="{{ route('draft.create') }}" class="btn btn-primary">

                           <i class="ti ti-square-rounded-plus me-2"> </i>

                            Create

                        </a>
                        @endif
                    </div>

                </div>

            </div>


            {{-- TABLE CARD --}}

            <div class="card border-0 shadow-sm">

                {{-- FILTERS --}}

                <div class="card-header bg-white border-0">

                    <form method="GET" action="{{ route('draft.index') }}">

                        <div class="row">

                            <div class="col-lg-4">

                                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Search subject or content">

                            </div>

                            <div class="col-lg-3">

                                <select name="category" class="form-select">

                                    <option value="">
                                        All Categories
                                    </option>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(request('category') == $category->id)>

                                            {{ $category->translations->first()?->name }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            <div class="col-lg-5">

                                <button type="submit" class="btn btn-primary">

                                    <i class="ti ti-filter"></i>

                                    Filter

                                </button>

                                <a href="{{ route('draft.index') }}" class="btn btn-light">

                                    Reset

                                </a>

                            </div>

                        </div>

                    </form>

                </div>

                {{-- TABLE --}}

                <div class="card-body p-0">

                    <x-table.table class="table table-hover align-middle mb-0">

                        <x-table.thead>

                            <x-table.th width="70">#</x-table.th>

                            <x-table.th>
                                Category
                            </x-table.th>

                            <x-table.th>
                                Subject
                            </x-table.th>

                            <x-table.th>
                                Created At
                            </x-table.th>

                            <x-table.th>
                                Updated At
                            </x-table.th>

                            <x-table.th width="180" class="text-end">
                                Actions
                            </x-table.th>

                        </x-table.thead>

                        <x-table.tbody>

                            @forelse($drafts as $index => $draft)
                                <x-table.tr>

                                    <x-table.td>

                                        {{ $drafts->firstItem() + $index }}

                                    </x-table.td>

                                    <x-table.td>

                                        <span class="badge bg-outline-primary">

                                            {{ $draft->category?->translations->first()?->name }}

                                        </span>

                                    </x-table.td>

                                    <x-table.td>

                                        <h6 class="fw-semibold mb-0">

                                            {{ $draft->subject }}

                                        </h6>

                                    </x-table.td>


                                    <x-table.td>

                                        {{ $draft->created_at->format('d M Y h:i A') }}

                                    </x-table.td>

                                    <x-table.td>

                                        {{ $draft->updated_at->format('d M Y h:i A') }}
                                    </x-table.td>

                                    <x-table.action-buttons :viewUrl="route('draft.show', $draft->id)" viewPermission="draft.view" :editUrl="route('draft.edit', $draft->id)"
                                        editPermission="draft.edit" :editData="$draft" :deleteUrl="route('draft.destroy', $draft->id)"
                                        deletePermission="draft.delete" :deleteId="$draft->id" />
                                        {{-- :emailUrl="route('draft.email', $draft->id)" emailPermission="draft.edit" --}}

                                </x-table.tr>

                            @empty

                                <x-table.empty colspan="7" title="No Drafts Found" subtitle="Create your first draft." />
                            @endforelse

                        </x-table.tbody>

                    </x-table.table>

                </div>

                @if ($drafts->hasPages())
                    <div class="card-footer bg-white border-0">

                        {{ $drafts->links('pagination::bootstrap-5') }}

                    </div>
                @endif

            </div>

        </div>

    </div>
@endsection
