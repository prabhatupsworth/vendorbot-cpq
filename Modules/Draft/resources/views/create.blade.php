@extends('layouts.app')
<style>
    .CodeMirror {
        height: 500px !important;
        border: 1px solid #e5e7eb;
        font-size: 14px;
        border-radius: 8px;
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

                            Create Draft

                        </h3>

                        <p class="text-muted mb-0">

                            Create and manage supplier communication drafts

                        </p>

                    </div>

                    <div class="col-lg-6 text-end">

                        <a href="{{ route('draft.index') }}" class="btn btn-light">

                            <i class="ti ti-arrow-left me-1"></i>

                            Back

                        </a>

                    </div>

                </div>

            </div>

            {{-- FORM CARD --}}

            <div class="card border-0 shadow-sm">

                <form action="{{ route('draft.store') }}" method="POST">

                    @csrf

                    <div class="card-body">

                        <div class="row">

                            {{-- CATEGORY --}}

                            <div class="col-md-6">

                                <div class="mb-4">

                                    <label class="form-label">

                                        Category

                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="draft_category_id"
                                        class="select @error('draft_category_id') is-invalid @enderror">

                                        <option value="">
                                            Select Category
                                        </option>

                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected(old('draft_category_id') == $category->id)>

                                                {{ $category->translations->first()?->name }}

                                            </option>
                                        @endforeach

                                    </select>

                                    @error('draft_category_id')
                                        <div class="invalid-feedback d-block">

                                            {{ $message }}

                                        </div>
                                    @enderror

                                </div>

                            </div>

                            {{-- SUBJECT --}}

                            <div class="col-md-6">

                                <div class="mb-4">

                                    <label class="form-label">

                                        Subject

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="text" name="subject"
                                        class="form-control @error('subject') is-invalid @enderror"
                                        placeholder="Enter draft subject" value="{{ old('subject') }}" required>

                                    @error('subject')
                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>
                                    @enderror

                                </div>

                            </div>

                            {{-- CONTENT --}}

                            <div class="row">
                                <div class="col-md-8">

                                    <div class="mb-4">

                                        <label class="form-label">

                                            Draft Content

                                            <span class="text-danger">*</span>

                                        </label>

                                        <textarea id="content" name="content" rows="12" class="form-control @error('content') is-invalid @enderror"
                                            placeholder="Write draft content here..." required>{{ old('content') }}</textarea>



                                        @error('content')
                                            <div class="invalid-feedback d-block">

                                                {{ $message }}

                                            </div>
                                        @enderror

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <div class="card border border-primary-subtle bg-light">

                                        <div class="card-header bg-primary-subtle">

                                            <h6 class="mb-0">

                                                <i class="ti ti-variable me-1"></i>

                                                Available Placeholders

                                            </h6>

                                        </div>

                                        <div class="card-body">

                                            <p class="text-muted mb-3">
                                                Click any placeholder to insert it into the editor.
                                            </p>

                                            <div class="d-flex flex-wrap gap-2">

                                                @php
                                                    $placeholders = [
                                                        '#Deal_Id#',
                                                        '#Deal_City#',
                                                        '#Deal_Pax#',
                                                        '#Deal_Date#',
                                                        '#Deal_Restaurant_StartTime#',
                                                        '#Deal_Language#',
                                                        '#Deal_Product_Name#',
                                                        '#Deal_Product_Description#',
                                                        '#Deal_Product2_Name#',
                                                        '#Deal_Product2_Description#',
                                                        '#Deal_Product_NettoCosts#',
                                                        '#Deal_Product_GruttoCosts#',
                                                        '#Deal_Data_Changes#',
                                                        '#Supplier_Name#',
                                                        '#Supplier_Address#',
                                                        '#Supplier_Link_ReplyPage#',
                                                        '#Restaurant_LatestMenu#',
                                                        '#Supplier_Link_Blacklist#',
                                                        '#Supplier_Link_ChangeData#',
                                                        '#Supplier_Winner_Name#',
                                                        '#Customer_Name#',
                                                        '#Customer_Orga#',
                                                        '#Customer_Link_ReplyPage#',
                                                        '#Customer_LatestMenu#',
                                                        '#Customer_Restaurant_List#',
                                                        '#Decline_Link#',
                                                    ];
                                                @endphp

                                                @foreach ($placeholders as $placeholder)
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary placeholder-copy"
                                                        data-value="{{ $placeholder }}">

                                                        {{ $placeholder }}

                                                    </button>
                                                @endforeach

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- FOOTER --}}

                    <div class="card-footer bg-white">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('draft.index') }}" class="btn btn-light">

                                Cancel

                            </a>

                            <button type="button" onclick="previewEmail()" class="btn btn-info">

                                <i class="ti ti-eye me-1"></i>

                                Preview Email

                            </button>

                            <button type="submit" class="btn btn-primary">

                                <i class="ti ti-device-floppy me-1"></i>

                                Save

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        let editor;

        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | CodeMirror
            |--------------------------------------------------------------------------
            */

            editor = CodeMirror.fromTextArea(
                document.getElementById('content'), {
                    mode: 'htmlmixed',
                    theme: 'white',
                    lineNumbers: true,
                    lineWrapping: true,
                    autoCloseTags: true,
                    matchTags: true,
                    indentUnit: 4,
                    tabSize: 4,
                }
            );

            editor.setSize('100%', 700);

            /*
            |--------------------------------------------------------------------------
            | Placeholder Buttons
            |--------------------------------------------------------------------------
            */

            document.querySelectorAll('.placeholder-copy')
                .forEach(button => {

                    button.addEventListener('click', function() {

                        insertPlaceholder(
                            this.dataset.value
                        );

                    });

                });

            /*
            |--------------------------------------------------------------------------
            | Sync Before Submit
            |--------------------------------------------------------------------------
            */

            document.querySelector('form')
                .addEventListener('submit', function() {

                    editor.save();

                });

        });

        /*
        |--------------------------------------------------------------------------
        | Insert Placeholder
        |--------------------------------------------------------------------------
        */

        function insertPlaceholder(value) {
            editor.replaceSelection(value);

            editor.focus();

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Placeholder inserted',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Preview Email
        |--------------------------------------------------------------------------
        */

        function previewEmail() {
            let html = editor.getValue();

            const sampleData = {

                '#Deal_Id#': '12345',
                '#Deal_City#': 'Berlin',
                '#Deal_Pax#': '25',
                '#Deal_Date#': '15 June 2026',
                '#Deal_Restaurant_StartTime#': '19:00',
                '#Deal_Language#': 'German',

                '#Deal_Product_Name#': 'Premium Dinner Package',
                '#Deal_Product_Description#': 'Premium Dinner Package',

                '#Deal_Product2_Name#': 'Welcome Drink',
                '#Deal_Product2_Description#': 'Cocktail',

                '#Deal_Product_NettoCosts#': '500 EUR',
                '#Deal_Product_GruttoCosts#': '595 EUR',

                '#Deal_Data_Changes#': 'Date Changed',

                '#Supplier_Name#': 'ABC Supplier',
                '#Supplier_Address#': 'Berlin, Germany',

                '#Supplier_Link_ReplyPage#': 'https://example.com/reply',
                '#Restaurant_LatestMenu#': 'https://example.com/menu',

                '#Supplier_Link_Blacklist#': 'https://example.com/blacklist',

                '#Supplier_Link_ChangeData#': 'https://example.com/change-data',

                '#Supplier_Winner_Name#': 'Restaurant XYZ',

                '#Customer_Name#': 'John Doe',
                '#Customer_Orga#': 'Google Inc',

                '#Customer_Link_ReplyPage#': 'https://example.com/customer-reply',

                '#Customer_LatestMenu#': 'https://example.com/customer-menu',

                '#Customer_Restaurant_List#': 'Restaurant A, Restaurant B',

                '#Decline_Link#': 'https://example.com/decline'
            };

            Object.keys(sampleData).forEach(key => {

                html = html.replaceAll(
                    key,
                    sampleData[key]
                );

            });

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

                showConfirmButton: true,

                confirmButtonText: `
        <i class="ti ti-x me-1"></i>
        Close Preview
    `,



                buttonsStyling: false,

                customClass: {

                    popup: 'shadow-lg',

                    title: 'border-bottom pb-3',

                    htmlContainer: 'p-0 m-0',
                    confirmButton: 'btn btn-danger'
                },

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
                    box-shadow:0 2px 10px rgba(0,0,0,.08);
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
@endpush
