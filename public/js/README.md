# 🚀 SWR Vanilla JS System (Laravel Integration)

## 📌 Overview

This system provides:

- ⚡ Auto data fetching (SWR-like)
- 🔄 Live UI updates (no page reload)
- 🧠 Auto DOM binding
- 📝 Reusable form handling (Create + Edit)
- ♻️ Cache + mutate + revalidate
- 🧩 Works with Laravel Blade + Vanilla JS

---

## 🧠 Core Concept

```
HTML (data-swr + data-url)
        ↓
Auto JS Init
        ↓
Fetch API
        ↓
Bind Data → UI
        ↓
Form Submit → mutate()
        ↓
UI updates instantly 🚀
```

---

## 📁 File Setup

```
public/js/
    app.js          (SWR system)
    swr-helper.js   (form + edit handler)

resources/views/layouts/app.blade.php
```

---

## 🔧 1. Include Scripts

```blade
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/swr-helper.js') }}"></script>
```

---

## 📦 2. Data Fetch (SWR Usage)

```html
<div data-swr="company_1" data-url="/projects/1/company">
    <div data-loading>Loading...</div>
    <div data-error style="display:none;">Error loading</div>

    <h5 data-bind="company_name"></h5>
    <p data-bind="email"></p>
</div>
```

---

## ⚙️ Attributes

| Attribute | Description |
|----------|------------|
| data-swr | Unique key |
| data-url | API endpoint |
| data-bind | Field binding |

---

## 🔁 3. Data Binding

```json
{
  "company_name": "ABC",
  "email": "test@mail.com"
}
```

```html
<span data-bind="company_name"></span>
<span data-bind="email"></span>
```

---

## 🔄 4. Create Form

```html
<form class="swr-form" data-swr="company_1" action="/projects/1/company/store" method="POST">
    <input type="text" name="company_name">
    <input type="text" name="email">
    <button type="submit">Save</button>
</form>
```

---

## ✏️ 5. Edit Flow

```html
<button class="swr-edit-btn"
        data-form="#companyForm"
        data-url="/projects/1/company"
        data-action="/projects/1/company/update">
    Edit
</button>
```

---

## 🔄 6. Validation System

```html
<input name="email">
<span data-error="email" class="text-danger"></span>
```

- Laravel validation JSON is automatically mapped
- First error field gets focus
- Old errors auto clear on submit

---

## 🔄 7. Events

### Success

```js
document.addEventListener('swr:success', e => {
    console.log(e.detail);
});
```

### Error

```js
document.addEventListener('swr:error', e => {
    console.error(e.detail);
});
```

---

## ⚠️ IMPORTANT FIX (EVENT BUBBLE)

Make sure events are dispatched with bubbling:

```js
form.dispatchEvent(new CustomEvent('swr:success', {
    detail: data,
    bubbles: true
}));
```

---

## 🎯 Features

- Auto fetch
- Auto bind
- No reload
- Create & Edit
- Multi module support
- Validation system
- Global events
- Laravel friendly

---

## 🚀 Final Result

Just use:

```html
<div data-swr="key" data-url="/api"></div>
<form class="swr-form"></form>
```

Everything works automatically ⚡
