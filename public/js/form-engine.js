// $(document).on("click", ".edit-form", function () {

//     let btn = $(this);
//     let form = $(btn.data("form"));
//     form[0].reset();

//     form.attr("action", btn.data("url"));

//     form.find("input[name=_method]").remove();

//     if (btn.data("method")) {
//         form.append(`<input type="hidden" name="_method" value="${btn.data("method")}">`);
//     }

//     // 🔥 THIS IS THE MAGIC
//     let data = btn.data("data");
//     if (data) {
//         Object.keys(data).forEach(key => {
//             let input = form.find(`[name="${key}"]`);

//             if (!input.length) return;

//             if (input.is("select")) {
//                 input.val(data[key]).trigger("change");
//             } else if (input.attr("type") === "checkbox") {
//                 input.prop("checked", !!data[key]);
//             } else {
//                 input.val(data[key] ?? "");
//             }
//         });
//     }

// });


$(document).on("click", ".edit-form", function () {

    let btn = $(this);
    let form = $(btn.data("form"));
    form[0].reset();

    form.attr("action", btn.data("url"));

    form.find("input[name=_method]").remove();

    if (btn.data("method")) {
        form.append(`<input type="hidden" name="_method" value="${btn.data("method")}">`);
    }

    let data = btn.data("data");

    if (data) {

        // 🔥 STEP 1: store pipeline_id BEFORE triggering change
        form.find('#pipelineSelect').data('selected', data.pipeline_id);

        // 🔥 STEP 2: trigger pipedrive change (this loads pipelines via AJAX)
        form.find('[name="pipedrive_account_id"]')
            .val(data.pipedrive_account_id)
            .trigger("change");

        // 🔥 STEP 3: fill remaining fields (EXCLUDE pipeline_id)
        Object.keys(data).forEach(key => {

            if (key === "pipeline_id" || key === "pipedrive_account_id") return;

            let input = form.find(`[name="${key}"]`);
            if (!input.length) return;

            if (input.is("select")) {
                input.val(data[key]).trigger("change");
            } else if (input.attr("type") === "checkbox") {
                input.prop("checked", !!data[key]);
            } else {
                input.val(data[key] ?? "");
            }
        });
    }

});

// Create || Update Method
$(document).on("submit", ".ajax-form", function (e) {
    e.preventDefault();

    let form = $(this);
    let btn = form.find(".js-submit-btn");

    // reset errors
    form.find(".is-invalid").removeClass("is-invalid");
    form.find(".invalid-feedback").text("");

    // loader ON
    btn.prop("disabled", true)
        .html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');


    let formData = new FormData(form[0]);

    $.ajax({
        url: form.attr("action"),
        method: "POST", // always POST (Laravel spoofing)
        data: formData,

        processData: false, // ✅ IMPORTANT
        contentType: false, // ✅ IMPORTANT

        success: function (res) {

            Swal.fire({
                icon: 'success',
                title: res.message,
                timer: 1500,
                showConfirmButton: false
            });

            UI.handle(res);
            let canvasEl = form.closest(".offcanvas")[0];

            if (canvasEl) {
                let canvas = bootstrap.Offcanvas.getInstance(canvasEl)
                    || new bootstrap.Offcanvas(canvasEl);

                canvas.hide();
            }

            form[0].reset();
        },

        error: function (xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;

                Object.keys(errors).forEach(field => {
                    let input = form.find(`[name="${field}"]`);
                    input.addClass("is-invalid");
                    form.find(`.error-${field}`).text(errors[field][0]);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Something went wrong'
                });
            }
        }
    });
});

// Delete method

$(document).on("click", ".delete-btn", function () {

    let btn = $(this);
    let url = btn.data("url");

    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {

        if (!result.isConfirmed) return;

        $.ajax({
            url: url,
            method: "POST", // Laravel spoofing
            data: {
                _method: "DELETE",
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
                btn.prop("disabled", true).text("Deleting...");
            },

            success: function (res) {
                UI.handle(res);
                Swal.fire({
                    icon: 'success',
                    title: res.message,
                    timer: 1500,
                    showConfirmButton: false
                });

                // 🔥 DataTable reload (dynamic safe)
                let table = btn.closest("table").DataTable();
                table.ajax.reload(null, false);
            },

            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Delete failed'
                });
            },

            complete: function () {
                btn.prop("disabled", false).text("Delete");
            }
        });

    });
});
