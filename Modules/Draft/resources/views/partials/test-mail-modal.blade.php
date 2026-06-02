<div class="modal fade"
     id="testMailModal">

    <div class="modal-dialog">

        <form
            action="{{ route('draft.send-test',$draft->id) }}"
            method="POST">

            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Send Test Email</h5>
                </div>

                <div class="modal-body">

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Enter email address"
                        required>

                </div>

                <div class="modal-footer">

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Send
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
