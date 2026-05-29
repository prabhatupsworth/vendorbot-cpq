@extends('layouts.app')
<style>
    .CodeMirror {
        height: 500px !important;
        border: none !important;
        font-size: 14px;
    }

    .CodeMirror-scroll {
        min-height: 500px;
    }
</style>
@section('content')
    <div class="page-wrapper">

        <div class="content">

            {{-- PAGE HEADER --}}

            <div class="page-header mb-4">

                <div class="row align-items-center">

                    <div class="col-lg-6">

                        <h3 class="page-title fw-bold">

                            Draft Details

                        </h3>

                        <p class="text-muted mb-0">

                            View email template draft

                        </p>

                    </div>

                    <div class="col-lg-6">

                       <div class="d-flex justify-content-end gap-2">
                         <a href="{{ route('draft.index') }}" class="btn btn-light">

                            <i class="ti ti-arrow-left me-1"></i>

                            Back

                        </a>

                        <a href="{{ route('draft.edit', $draft->id) }}" class="btn btn-primary">

                            <i class="ti ti-edit me-1"></i>

                            Edit

                        </a>
                       </div>

                    </div>

                </div>

            </div>

            {{-- DETAILS CARD --}}

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-4">

                            <div class="border rounded p-3 h-100 bg-light">

                                <small class="text-muted d-block mb-2">
                                    Category
                                </small>

                                <span class="badge bg-outline-primary px-3 py-2">

                                    {{ $draft->category?->translations->first()?->name }}

                                </span>

                            </div>

                        </div>

                        <div class="col-md-6 mb-4">

                            <div class="border rounded p-3 h-100 bg-light">

                                <small class="text-muted d-block mb-2">
                                    Subject
                                </small>

                                <h6 class="mb-0 fw-semibold">

                                    {{ $draft->subject }}

                                </h6>

                            </div>

                        </div>

                        <div class="col-md-12">

                            <div class="border rounded overflow-hidden">

                                <div
                                    class="px-3 py-2 border-bottom bg-light d-flex justify-content-between align-items-center">

                                    <div>

                                        <h6 class="mb-0 fw-semibold">

                                            <i class="ti ti-code me-2 text-primary"></i>

                                            HTML Template

                                        </h6>

                                    </div>

                                    <span class="badge bg-dark">

                                        HTML
                                    </span>

                                </div>

                                <div class="p-0">

                                    <textarea id="htmlViewer">{{ trim($draft->content) }}</textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-footer bg-white">

                    <div class="d-flex justify-content-end gap-2">

                        <button type="button" class="btn btn-outline-primary" onclick="copyHtml()">

                            <i class="ti ti-copy me-1"></i>

                            Copy HTML

                        </button>

                        <button type="button" class="btn btn-info" onclick="previewEmail()">

                            <i class="ti ti-eye me-1"></i>

                            Preview Email

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function copyHtml() {
            navigator.clipboard.writeText(
                @json($draft->content)
            );

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'HTML copied',
                showConfirmButton: false,
                timer: 1500
            });
        }

        function previewEmail() {
            let html =
                @json($draft->content);

            Swal.fire({

                title: `
           <div class="d-flex align-items-center justify-content-between">

            <div class="d-flex align-items-center">

                <i class="ti ti-mail me-2 text-primary"></i>

                <span>Email Preview</span>

            </div>

        </div>
        `,

                width: '95%',

                showCloseButton: true,

                confirmButtonText: `<i class="ti ti-x me-1"></i>Close Preview`,

                customClass: {
                    confirmButton: 'btn btn-danger'
                },

                buttonsStyling: false,


                html: `

            <div
                style="
                    background:#f8fafc;
                    padding:15px;
                    border-radius:10px;
                ">

                <iframe
                    id="previewFrame"
                    style="
                        width:100%;
                        height:80vh;
                        border:1px solid #dee2e6;
                        border-radius:10px;
                        background:#fff;
                    ">
                </iframe>

            </div>

        `,

                didOpen: () => {

                    document
                        .getElementById('previewFrame')
                        .srcdoc = html;

                }

            });
        }
    </script>

    <script>
        const editor = CodeMirror.fromTextArea(
            document.getElementById('htmlViewer'), {
                mode: 'htmlmixed',
                theme: 'white',
                readOnly: true,
                lineNumbers: true
            }

        );
        editor.setSize('100%', 400);
    </script>
@endpush
