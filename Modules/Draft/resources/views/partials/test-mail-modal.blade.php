<div class="modal fade" id="testMailModal">

    <div class="modal-dialog">

        <form id="testMailForm" action="{{ route('draft.send-test', $draft->id) }}" method="POST">

            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h4>Send Test Email</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">SMTP Account</label>

                        <select name="smtp_id" class="form-select" required>
                            <option value="">Select SMTP</option>
                            @foreach ($smtp as $type => $id)
                                <option value="{{ $id }}">
                                    {{ ucwords(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <input type="email" name="email" class="form-control" placeholder="Enter email address"
                        required>

                </div>

                <div class="modal-footer">

                    <button id="sendMailBtn" type="submit" class="btn btn-primary">
                        Send
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@push('scripts')
    <script>
        $('#testMailForm').on('submit', function(e) {


            const form = $(this);

            if (form.data('submitted')) {
                e.preventDefault();
                return false;
            }

            form.data('submitted', true);

            $('#sendMailBtn')
                .prop('disabled', true)
                .html(
                    '<span class="spinner-border spinner-border-sm me-1"></span>Sending...'
                );
        });
    </script>
@endpush
