/*************************************
 * 🔥 SWR REGISTRY
 *************************************/
const SWR_INSTANCES = {};

/*************************************
 * 🔗 NORMAL BIND (single object)
 *************************************/
function bind(container, data) {
    container.querySelectorAll('[data-bind]').forEach(el => {
        const path = el.getAttribute('data-bind');
        const value = path.split('.').reduce((o, i) => o?.[i], data);

        if (el.tagName === 'IMG') {
            el.src = value ? `/storage/${value}` : 'https://via.placeholder.com/120';
        } else {
            el.innerText = value ?? '-';
        }
    });
}

/*************************************
 * 📊 LIST RENDER (NEW)
 *************************************/
function renderList(container, data) {

    const listKey = container.getAttribute('data-list');

    let listData = listKey ? data[listKey] : data;

    if (!Array.isArray(listData)) return;

    const template = container.querySelector('template[data-template]');
    container.innerHTML = '';
    container.appendChild(template);

    // 🔥 Empty state
    if (listData.length === 0) {
        container.innerHTML = `
            <tr>
                <td colspan="100%" class="text-center text-muted">No data found</td>
            </tr>
        `;
        return;
    }

    listData.forEach(item => {

        const clone = template.content.cloneNode(true);

        /*************************************
         * 🔗 NORMAL DATA BIND
         *************************************/
        clone.querySelectorAll('[data-bind]').forEach(el => {

            const path = el.getAttribute('data-bind');
            const value = path.split('.').reduce((o, i) => o?.[i], item);

            if (el.tagName === 'IMG') {
                el.src = value ? `/storage/${value}` : 'https://via.placeholder.com/120';
            } else {
                el.innerText = value ?? '-';
            }
        });

        /*************************************
         * 🔥 DYNAMIC URL BIND (NEW)
         *************************************/
        clone.querySelectorAll('[data-bind-url]').forEach(el => {

            let url = el.getAttribute('data-bind-url');

            Object.keys(item).forEach(key => {
                url = url.replace(`{${key}}`, item[key]);
            });

            el.setAttribute('data-url', url);
        });

        /*************************************
         * 🔥 DYNAMIC ACTION BIND (NEW)
         *************************************/
        clone.querySelectorAll('[data-bind-action]').forEach(el => {

            let action = el.getAttribute('data-bind-action');

            Object.keys(item).forEach(key => {
                action = action.replace(`{${key}}`, item[key]);
            });

            el.setAttribute('data-action', action);
        });

        /*************************************
         * 🔥 DATA-* AUTO BIND (NEW)
         *************************************/
        clone.querySelectorAll('[data-bind-attr]').forEach(el => {

            const attrMap = el.getAttribute('data-bind-attr'); // e.g. data-id:id

            attrMap.split(',').forEach(pair => {

                const [attr, key] = pair.split(':');

                if (item[key] !== undefined) {
                    el.setAttribute(attr, item[key]);
                }

            });
        });

        container.appendChild(clone);
    });
}

/*************************************
 * 🔄 STATE HANDLER
 *************************************/
function setState(container, type, show) {
    container.querySelectorAll(`[data-${type}]`).forEach(el => {
        el.style.display = show ? 'block' : 'none';
    });
}

/*************************************
 * ⚡ SWR CORE
 *************************************/
function createSWR(key, container, url) {

    async function fetchData() {

        setState(container, 'loading', true);
        setState(container, 'error', false);

        try {
            const res = await fetch(url, {
                headers: { 'Accept': 'application/json' }
            });

            if (!res.ok) throw new Error('API failed');

            let data = await res.json();
            data = data.data ?? data;

            // 🔥 AUTO DETECT LIST OR SINGLE
            if (container.hasAttribute('data-list')) {
                renderList(container, data);
            } else {
                bind(container, data);
            }

            setState(container, 'loading', false);

        } catch (err) {

            console.error(err);

            setState(container, 'loading', false);
            setState(container, 'error', true);
        }
    }

    fetchData();

    return {
        revalidate: fetchData,

        mutate(newData, options = {}) {

            // 🔥 IMPORTANT: handle revalidate
            if (options.revalidate) {
                fetchData(); // ✅ THIS FIXES YOUR ISSUE
                return;
            }

            // 🔥 normal UI update
            if (container.hasAttribute('data-list')) {
                renderList(container, newData);
            } else {
                bind(container, newData);
            }
        }
    };
}

/*************************************
 * 🚀 AUTO INITIALIZER
 *************************************/
function initSWR() {

    document.querySelectorAll('[data-swr]').forEach(container => {

        const key = container.getAttribute('data-swr');
        const url = container.getAttribute('data-url');

        if (!key || !url) return;

        if (SWR_INSTANCES[key]) return;

        SWR_INSTANCES[key] = createSWR(key, container, url);
    });
}

/*************************************
 * 🔄 AUTO RE-INIT
 *************************************/
const observer = new MutationObserver(() => {
    initSWR();
});

/*************************************
 * 🚀 START
 *************************************/
document.addEventListener('DOMContentLoaded', () => {
    initSWR();

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});

/*************************************
 * 🌍 GLOBAL ACCESS
 *************************************/
window.SWR = SWR_INSTANCES;
