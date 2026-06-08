<div class="modal fade" id="importSupplierModal">

    <div class="modal-dialog">

        <form id="importSupplilerForm" action="{{ route('suppliers.import') }}" method="POST">

            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h4>Import Supplier</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Categories</label>

                        <select name="types[]" class="form-select select2" multiple required>
                            @foreach ($importCategories as $value => $label)
                                <option value="{{ $value }}">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                     <div class="mb-3">
                        @php

                            $cities = [
                                'berlin' => 'Berlin',
                                'hamburg' => 'Hamburg',
                                'munchen' => 'München',
                                'frankfurt' => 'Frankfurt',
                                'stuttgart' => 'Stuttgart',
                                'koln' => 'Köln',
                                'dusseldorf' => 'Düsseldorf',
                                'leipzig' => 'Leipzig',
                            ];
                        @endphp
                        <label class="form-label">Cities</label>
                        <select name="city" class="select">
                            @foreach ($cities as $value => $label)
                                <option value="{{ $value }}">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Country</label>
                        <select id="country-select" name="country_code" class="select2 form-control" required>
                            <option value="">Select Country</option>

                            @foreach ($countries as $code => $name)
                                <option value="{{ $code }}">
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                </div>

                <div class="modal-footer">

                    <button id="sendMailBtn" type="submit" class="btn btn-primary">
                        Import
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@push('scripts')
    <script>
        $(document).on('submit', '#importSupplilerForm', function(e) {

            const form = $(this);

            if (form.data('submitted')) {
                e.preventDefault();
                return false;
            }

            form.data('submitted', true);

            $('#sendMailBtn')
                .prop('disabled', true)
                .html(
                    '<span class="spinner-border spinner-border-sm me-1"></span>Importing...'
                );
        });

        $(document).on('shown.bs.modal', '#importSupplierModal', function() {

            $(this).find('select[name="country_code"]').select2({
                dropdownParent: $(this),
                width: '100%',
                placeholder: 'Select Country',
                allowClear: true
            });

        });
    </script>
@endpush
