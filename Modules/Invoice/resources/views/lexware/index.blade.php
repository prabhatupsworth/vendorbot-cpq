@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="page-title">Lexware<span class="count-title">List</span></h4>
                            </div>
                            <div class="col-4 text-end">
                                <div class="head-icons">
                                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-original-title="Refresh"><i class="ti ti-refresh-dot"></i></a>
                                    <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-original-title="Collapse" id="collapse-header"><i
                                            class="ti ti-chevrons-up"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Settings Info -->
                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4 class="fw-semibold mb-3">Connected Accounts</h4>
                                </div>

                                <div class="col-md-8">
                                    <div class="d-flex align-items-center flex-wrap row-gap-2 justify-content-sm-end">

                                        <a href="javascript:void(0);" class="btn btn-primary mb-3"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvas_add_lexware"><i
                                                class="ti ti-square-rounded-plus me-2"></i>Add
                                            New</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <!-- App -->
                                @foreach ($settings as $setting)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <img width="50"
                                                        src="{{ asset('template/assets/img/icons/lexware.svg') }}"
                                                        alt="Icon">
                                                    <div>
                                                        <button
                                                            class="btn btn-sm btn-icon btn-primary rounded-pill edit-btn"
                                                            data-bs-toggle="offcanvas" data-id="{{ $setting->id }}"
                                                            data-bs-target="#offcanvas_add_lexware">
                                                            <i class="ti ti-edit text-white"></i>
                                                        </button>
                                                        <button
                                                            class="btn btn-light btn-icon btn-sm rounded-pill view-details"
                                                            data-bs-toggle="offcanvas"
                                                            data-bs-target="#offcanvas_view_lexware">
                                                            <i class="ti ti-eye text-muted"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <p class="mb-0">{{ $setting?->type }}</p>

                                                    <div class="connect-btn">
                                                        @if ($setting->is_verified)
                                                            <span class="badge badge-soft-success">Connected</span>
                                                        @else
                                                            <a href="{{ route('settings.invoice.lexware.connect', $setting->id) }}"
                                                                class="badge border bg-white text-default">Test
                                                                Connection</a>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- /App -->
                            </div>
                        </div>
                    </div>
                    <!-- /Settings Info -->

                </div>
            </div>
        </div>

        {{-- Add Lexware Account Offcanvas --}}
        <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add_lexware"
            aria-labelledby="offcanvasTitle">
            <div class="offcanvas-header border-bottom">
                <h5 class="fw-semibold" id="offcanvasTitle">Add Lexware Account</h5>
                <button type="button"
                    class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                    data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                <form id="lexwareForm" action="{{ route('settings.invoice.lexware.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        <!-- INVOICE TYPE -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="col-form-label">Invoice Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="type" required>
                                    <option disabled>Invoice Type</option>

                                    <option value="lexware" {{ old('type', 'lexware') == 'lexware' ? 'selected' : '' }}>
                                        Lexware
                                    </option>

                                    <option value="manual" {{ old('type') == 'manual' ? 'selected' : '' }}>
                                        Manual
                                    </option>

                                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>
                                        Other
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- API KEY -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="col-form-label">API Key <span class="text-danger" id="invoice_api_key_label">*</span></label>
                                <div class="icon-form-end">
                                    <span class="form-icon"><i class="ti ti-eye-off"></i></span>
                                    <input type="password" name="api_key"
                                        class="form-control @error('api_key') is-invalid @enderror"
                                        value="{{ old('api_key') }}" required>
                                </div>

                                @error('api_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- BASE URL -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="col-form-label">Base URL <span class="text-danger">*</span></label>
                                <input id="base_url" type="text" name="base_url"
                                    class="form-control @error('base_url') is-invalid @enderror"
                                    placeholder="https://yourcompany.pipedrive.com" value="{{ old('base_url') }}"
                                    required>

                                @error('base_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Currency --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="col-form-label">Currency <span class="text-danger">*</span></label>

                                <select class="form-select @error('currency') is-invalid @enderror" name="currency"
                                    required>
                                    <option disabled>Select Currency</option>

                                    <option value="EUR" {{ old('currency', 'EUR') == 'EUR' ? 'selected' : '' }}>
                                        Euro (€)
                                    </option>
                                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>
                                        US Dollar ($)
                                    </option>

                                </select>

                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Add</button>
                    </div>
                </form>
            </div>

        </div>

        {{--  --}}

        <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_view_lexware"
            aria-labelledby="offcanvasTitle">
            <div class="offcanvas-header border-bottom">
                <h5 class="fw-semibold">View Lexware Account</h5>
                <button type="button"
                    class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                    data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                <div class="card">

                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-bottom mb-3">
                            <li class="nav-item"><a class="nav-link active" href="#bottom-tab1"
                                    data-bs-toggle="tab">Overview</a></li>


                            <li class="nav-item"><a class="nav-link" href="#bottom-tab2"
                                    data-bs-toggle="tab">History</a>
                            </li>

                        </ul>

                        <div class="tab-content">

                            <div class="tab-pane show active" id="bottom-tab1">

                                <!-- 🔹 Account Header -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="mb-0" id="InvoiceName">--</h5>
                                        <small class="text-muted" id="invoiceAccountUrl">--</small>
                                    </div>

                                    <!-- Status -->
                                    <div id="accountStatus">
                                        <span class="badge bg-secondary">--</span>
                                    </div>

                                </div>

                                {{-- Currency   --}}
                                <div>

                                    Currency : <span class="badge bg-info" id="accountCurrency">--</span>
                                </div>

                            </div>

                            <div class="tab-pane" id="bottom-tab2">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Status</th>
                                                <th>Message</th>
                                                <th>Performed By</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="historyTableBody">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <a id="load_more" class="btn btn-danger nav-link mt-2" href="#">Load
                                        More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Edit Account Script --}}
        <script>
            const func = new Promise(((resolve, reject) => {
                setTimeout(() => {
                    resolve('resolved');
                }, 1000);
            }));

            $(document).ready(function() {
                // Handle Edit Button Click
                $('.edit-btn').on('click', function() {
                    let settingId = $(this).data('id');
                    $("#offcanvasTitle").text("Edit Lexware Account");
                    $('#submitBtn').text('Update');
                    $.ajax({
                        url: '/settings/invoice/lexware/' + settingId + '/edit',
                        method: 'GET',
                        success: function(response) {
                            $('#lexwareForm').attr('action', '/settings/invoice/lexware/' +
                                settingId + '/update');

                            $("#lexwareForm #invoice_api_key_label").remove();
                            $('#lexwareForm input[name="api_key"]').removeAttr('required');
                            $('#lexwareForm input[name="base_url"]').val(response.base_url);
                            $('#lexwareForm select[name="currency"]').val(response.currency);
                            $('#submitBtn').text('Update');
                        },
                        error: function() {
                            alert('Failed to fetch account details. Please try again.');
                        }
                    });
                });
            });
        </script>
        {{-- view details   --}}
        <script>
            $(document).ready(function() {
                $('.view-details').on('click', function() {
                    let settingId = $(this).closest('.card').find('.edit-btn').data('id');
                    $.ajax({
                        url: '/settings/invoice/lexware/' + settingId + '/details',
                        method: 'GET',
                        success: function(response) {
                            $('#InvoiceName').text(response.account.type);
                            $('#invoiceAccountUrl').text(response.account.base_url);
                            $('#accountCurrency').text(response.account.currency);
                            if (response.account.is_verified) {
                                $('#accountStatus').html(
                                    '<span class="badge bg-success">Connected</span>');
                            } else {
                                $('#accountStatus').html(
                                    '<span class="badge bg-danger">Not Connected</span>');
                            }

                            // Populate history table
                            let historyHtml = '';
                            response.activityLog.forEach(function(log) {
                                historyHtml += `<tr>
                                    <td><span class="badge bg-info">${log.status}</span></td>
                                    <td>${log.message}</td>
                                    <td>${log.user ? log.user.name : 'System'}</td>
                                    <td>${new Date(log.performed_at).toLocaleString()}</td>
                                </tr>`;
                            });
                            $('#historyTableBody').html(historyHtml);
                            $('#load_more').attr('href',
                            `/history/lexware/${response.account.id}`); // Set load more link
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to fetch account details. Please try again.',
                                confirmButtonColor: '#d33'
                            });

                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
