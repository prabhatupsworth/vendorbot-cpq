window.UI = {

    handle: function (res) {
        if (!res) return;

        let action = res.action || "replace";

        switch (action) {
            case "replace":
                this.replace(res);
                break;

            case "append":
                this.append(res);
                break;

            case "prepend":
                this.prepend(res);
                break;

            case "update":
                this.update(res);
                break;

            case "delete":
                this.remove(res);
                break;
        }
    },

    replace: function (res) {
        if (!res.target || !res.html) return;

        let el = document.querySelector(res.target);
        if (el) el.innerHTML = res.html;
    },

    append: function (res) {
        if (!res.target || !res.html) return;

        let el = document.querySelector(res.target);
        if (el) el.insertAdjacentHTML("beforeend", res.html);
    },

    prepend: function (res) {
        if (!res.target || !res.html) return;

        let el = document.querySelector(res.target);
        if (el) el.insertAdjacentHTML("afterbegin", res.html);
    },

    update: function (res) {
        if (!res.target || !res.id || !res.html) return;

        let el = document.querySelector(`${res.target}[data-id="${res.id}"]`);
        if (el) el.outerHTML = res.html;
    },

    remove: function (res) {
        if (!res.target || !res.id) return;

        let el = document.querySelector(`${res.target}[data-id="${res.id}"]`);
        if (el) el.remove();
    }
};
