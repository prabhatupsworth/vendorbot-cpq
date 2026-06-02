@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="container">

            <div class="d-flex justify-content-between mb-3">

                <h4>Email Preview</h4>

                @if (userCan('draft.create'))
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#testMailModal">
                        Send Test Email
                    </button>
                @endif

            </div>


            <div class="page-header mb-4">

                <div class="row align-items-center">

                    <div class="col-lg-6">

                        <h3 class="page-title fw-bold">

                            Email Preview

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

                            <i class="ti ti-plus me-1"></i>

                            Create Draft

                        </a>
                        @endif
                    </div>

                </div>

            </div>


            <div class="card">
                <div class="card-body">

                    {!! $draft->content !!}

                </div>
            </div>

        </div>
    </div>
    @include('draft::partials.test-mail-modal')
@endsection
