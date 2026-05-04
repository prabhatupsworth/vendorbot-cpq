/*************************************
 * 🚀 SWR FORM HELPER
 *************************************/
(function () {

    /*************************************
     * 📤 FORM SUBMIT (CREATE + UPDATE)
     *************************************/
    function handleFormSubmit(e) {
        const form = e.target;

        if (!form.classList.contains('swr-form')) return;

        e.preventDefault();

        const url = form.action;
        const method =
            form.querySelector('input[name=_method]')?.value ||
            form.dataset.method ||
            form.method ||
            'POST';

        const key = form.dataset.swr;
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // 🔥 FIX: overwrite token
        formData.set('_token', csrfToken);

        console.log([...formData.entries()]);

        const realMethod = method.toUpperCase();

        const options = {
            method: realMethod === 'PUT' ? 'POST' : realMethod, // ✅ KEY FIX
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'same-origin'
        };

        if (options.method !== 'GET') {
            options.body = formData;
        }

        fetch(url, options)
            .then(async res => {
                const data = await res.json();

                if (!res.ok) throw data;

                // 🔥 SWR UPDATE
                if (key && window.SWR?.[key]) {
                    window.SWR[key].mutate(data.data ?? data, { revalidate: true });
                }

                // 🔥 reset form after create (optional)
                if (!form.dataset.mode || form.dataset.mode === 'create') {
                    form.reset();
                }

                // 🔥 success event
                form.dispatchEvent(new CustomEvent('swr:success', { detail: data, bubbles: true }));
            })
            .catch(err => {

                console.error(err);

                // 🔥 CLEAR OLD ERRORS
                form.querySelectorAll('[data-error]').forEach(el => {
                    el.innerText = '';
                });

                // 🔥 SHOW VALIDATION ERRORS
                if (err?.errors) {

                    Object.keys(err.errors).forEach(field => {

                        const messages = err.errors[field];

                        const errorEl = form.querySelector(`[data-error="${field}"]`);
                        if (errorEl) {
                            errorEl.innerText = messages[0];
                        }

                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                        }
                    });

                    // ✅ 🔥 ADD HERE (AFTER marking invalid inputs)
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) firstError.focus();
                }

                form.dispatchEvent(new CustomEvent('swr:error', { detail: err, bubbles: true }));

            });
    }

    /*************************************
     * ✏️ EDIT BUTTON HANDLER (AUTO FILL)
     *************************************/
    async function handleEditClick(e) {

        const btn = e.target.closest('.swr-edit-btn');
        if (!btn) return;

        const formSelector = btn.dataset.form;
        const url = btn.dataset.url;

        const form = document.querySelector(formSelector);

        if (!form || !url) return;

        try {
            const res = await fetch(url, {
                headers: { 'Accept': 'application/json' }
            });

            if (!res.ok) throw new Error('Failed to fetch');

            const data = await res.json();
            const values = data.data ?? data;

            // 🔥 AUTO FILL INPUTS
            form.querySelectorAll('[name]').forEach(input => {

                const name = input.name;
                const value = name.split('.').reduce((o, i) => o?.[i], values);

                if (input.type === 'checkbox') {
                    input.checked = !!value;
                } else if (input.type === 'radio') {
                    input.checked = input.value == value;
                } else {
                    input.value = value ?? '';
                }
            });

            // 🔥 mark edit mode
            form.dataset.mode = 'edit';

            // 🔥 change form action (optional dynamic)
            if (btn.dataset.action) {
                form.action = btn.dataset.action;
            }

            // 🔥 set method PUT automatically
            let methodInput = form.querySelector('input[name=_method]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';

            // 🔥 open modal/offcanvas automatically
            const container = form.closest('.offcanvas, .modal');
            if (container) {
                if (container.classList.contains('offcanvas')) {
                    new bootstrap.Offcanvas(container).show();
                } else {
                    new bootstrap.Modal(container).show();
                }
            }

        } catch (err) {
            console.error('Edit Load Error:', err);
        }
    }

    /*************************************
     * 🔄 RESET FORM TO CREATE MODE
     *************************************/
    function handleCreateMode(e) {

        const btn = e.target.closest('.swr-create-btn');
        if (!btn) return;

        const formSelector = btn.dataset.form;
        const form = document.querySelector(formSelector);

        if (!form) return;

        form.reset();
        form.dataset.mode = 'create';

        // remove PUT method
        const methodInput = form.querySelector('input[name=_method]');
        if (methodInput) methodInput.remove();
    }

    /*************************************
     * 🎯 GLOBAL EVENTS
     *************************************/
    document.addEventListener('submit', handleFormSubmit);
    document.addEventListener('click', handleEditClick);
    document.addEventListener('click', handleCreateMode);

})();
