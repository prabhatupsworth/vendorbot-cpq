@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="container">

            <div class="page-header mb-4 py-4">

                <div class="row align-items-center">

                    <div class="col-lg-6">

                        <h3 class="page-title fw-bold">

                            Email Preview

                        </h3>

                        <p class="text-muted mb-0">
                            Manage all supplier communication drafts
                        </p>

                    </div>

                    <div class="col-lg-6 text-end">
                        @if (userCan('draft.create'))
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#testMailModal">
                                Send Test Email
                            </button>
                        @endif
                    </div>

                </div>

            </div>


            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    {!! $draft->content !!}

                </div>
            </div>

        </div>
    </div>
    @include('draft::partials.test-mail-modal',['smtp'=>$smtp])
@endsection
