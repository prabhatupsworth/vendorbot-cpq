@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="row">
                <div class="col-md-12">

                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="page-title">Settings</h4>
                            </div>
                            <div class="col-4 text-end">
                                <div class="head-icons">
                                    <a href="security.html" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-original-title="Refresh"><i class="ti ti-refresh-dot"></i></a>
                                    <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-original-title="Collapse" id="collapse-header"><i
                                            class="ti ti-chevrons-up"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->

                    <!-- Settings Menu -->
                    @include('profile.setting-menu')
                    <!-- /Settings Menu -->

                    <div class="row">
                        @include('profile.sidebar')

                        <div class="col-xl-9 col-lg-12">

                            <!-- Settings Info -->
                            <div class="card">
                                <div class="card-body pb-0">
                                    <h4 class="fw-semibold mb-3">Security Settings</h4>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 d-flex">
                                            <div class="card border shadow-none flex-fill mb-3">
                                                <div class="card-body d-flex justify-content-between flex-column">
                                                    <div class="mb-3">
                                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                                            <h6 class="fw-semibold">Password</h6>
                                                        </div>
                                                        <p>
                                                            Last Changed
                                                            {{ auth()->user()->updated_at?->format('d M Y, h:i A') ?? 'Never' }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-light" data-bs-toggle="offcanvas"
                                                            data-bs-target="#changePassCanvas">
                                                            Change Password
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- /Settings Info -->

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <x-offcanvas id="changePassCanvas" title="Change Password" formId="changePassForm">

            <form id="changePassForm" class="ajax-form" method="POST" action="{{ route('change.password') }}">
                @csrf
                @php
                    $config = [
                        [
                            'name' => 'current_password',
                            'label' => 'Current Password',
                            'type' => 'password',
                            'placeholder' => 'Enter current password',
                            'required' => true,
                            'col' => 12,
                        ],

                        [
                            'name' => 'new_password',
                            'label' => 'New Password',
                            'type' => 'password',
                            'placeholder' => 'Enter new password',
                            'required' => true,
                            'col' => 12,
                        ],

                        [
                            'name' => 'new_password_confirmation',
                            'label' => 'Confirm New Password',
                            'type' => 'password',
                            'placeholder' => 'Confirm new password',
                            'required' => true,
                            'col' => 12,
                        ],
                    ];
                @endphp

                <x-form.fields :config="$config" />


            </form>

        </x-offcanvas>
    </div>
@endsection
