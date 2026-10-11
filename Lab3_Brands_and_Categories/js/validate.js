// validate.js - client-side validation for registration and login forms
// The server always validates again independently — this is just a UX layer.

// ---- Shared regex patterns ----
const EMAIL_REGEX    = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const PHONE_REGEX    = /^[0-9+\-\s]{7,15}$/;
const NAME_REGEX     = /^.{2,}$/;

// Password rules (must match the server-side check in customer_register_action.php):
// at least 8 characters, one uppercase, one lowercase, one digit, one special character
const PASSWORD_REGEX = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+\[\]{};':"\\|,.<>\/?]).{8,}$/;


// ---- Inline error helpers ----

function showError(fieldId, message) {
    const el = document.getElementById(fieldId + '-error');
    if (el) {
        el.textContent = message;
        el.style.color   = '#F43F5E';
        el.style.fontSize = '0.76em';
        el.style.display  = 'block';
    }
}

function clearError(fieldId) {
    const el = document.getElementById(fieldId + '-error');
    if (el) { el.textContent = ''; el.style.display = 'none'; }
}

function clearAllErrors(fieldIds) {
    fieldIds.forEach(clearError);
}


// ---- Registration form ----
// Called by the Register button (type="button"). Validates all fields,
// then sends the form via fetch() so the page does not reload.

function registerCustomer() {
    const fields = ['customer_name','customer_email','customer_pass','customer_pass_confirm',
                    'customer_country','customer_city','customer_contact'];
    clearAllErrors([...fields, 'form']);

    const name    = document.getElementById('customer_name').value.trim();
    const email   = document.getElementById('customer_email').value.trim();
    const pass    = document.getElementById('customer_pass').value;
    const confirm = (document.getElementById('customer_pass_confirm') || {}).value || '';
    const country = document.getElementById('customer_country').value.trim();
    const city    = document.getElementById('customer_city').value.trim();
    const contact = document.getElementById('customer_contact').value.trim();

    let valid = true;

    if (!NAME_REGEX.test(name)) {
        showError('customer_name', 'Full name must be at least 2 characters.');
        valid = false;
    }
    if (!EMAIL_REGEX.test(email)) {
        showError('customer_email', 'Please enter a valid email address.');
        valid = false;
    }
    if (!PASSWORD_REGEX.test(pass)) {
        showError('customer_pass', 'Password must be at least 8 characters with uppercase, lowercase, number, and special character (e.g. MyPass1!).');
        valid = false;
    }
    if (document.getElementById('customer_pass_confirm')) {
        if (confirm === '') {
            showError('customer_pass_confirm', 'Please confirm your password.');
            valid = false;
        } else if (confirm !== pass) {
            showError('customer_pass_confirm', 'Passwords do not match.');
            valid = false;
        }
    }
    if (country.length < 2) {
        showError('customer_country', 'Please enter your country.');
        valid = false;
    }
    if (city.length < 2) {
        showError('customer_city', 'Please enter your city.');
        valid = false;
    }
    if (!PHONE_REGEX.test(contact)) {
        showError('customer_contact', 'Contact must be 7-15 digits (+ - spaces allowed).');
        valid = false;
    }

    if (!valid) return;

    const btn = document.getElementById('register-btn');
    if (btn) { btn.disabled = true; btn.textContent = 'Registering…'; }

    // FormData automatically includes the hidden csrf_token field
    fetch('../actions/customer_register_action.php', {
        method: 'POST',
        body: new FormData(document.getElementById('registerForm'))
    })
    .then(res => res.json())
    .then(data => {
        const msgEl = document.getElementById('formMessage');
        if (msgEl) {
            msgEl.textContent = data.message;
            msgEl.style.color = data.success ? '#16a34a' : '#dc2626';
        }
        if (data.success) {
            document.getElementById('registerForm').reset();
            setTimeout(() => { window.location.href = 'login.php'; }, 1800);
        }
    })
    .catch(() => {
        const msgEl = document.getElementById('formMessage');
        if (msgEl) { msgEl.textContent = 'A network error occurred. Please try again.'; msgEl.style.color = '#dc2626'; }
    })
    .finally(() => {
        if (btn) { btn.disabled = false; btn.textContent = 'Create Account'; }
    });
}


// ---- Login form ----
// Light validation only — the server does the actual credential check.

function loginCustomer() {
    clearAllErrors(['login_email','login_pass']);

    const email = document.getElementById('login_email').value.trim();
    const pass  = document.getElementById('login_pass').value;
    let valid   = true;

    if (!EMAIL_REGEX.test(email)) {
        showError('login_email', 'Please enter a valid email address.');
        valid = false;
    }
    if (pass.length === 0) {
        showError('login_pass', 'Please enter your password.');
        valid = false;
    }

    if (!valid) return;

    // Normal POST form submit — PHP sets the session cookie server-side
    document.getElementById('loginForm').submit();
}


// ---- Character counter ----
// Shows how many characters have been typed out of the allowed maximum.
// Turns amber near the limit and red within 10 characters of it.

function charCount(inputId, counterId, maxLen) {
    const input   = document.getElementById(inputId);
    const counter = document.getElementById(counterId);
    if (!input || !counter) return;
    const used = input.value.length;
    counter.textContent = used + ' / ' + maxLen;
    counter.style.color =
        used > maxLen - 10 ? '#F43F5E' :
        used > maxLen - 25 ? '#F59E0B' : '#94A3B8';
}


// ---- Live table filter ----
// Hides rows that do not contain the search string.
// Shows a "no results" message if all rows are hidden.

function filterTable(inputId, tableId) {
    const filter   = document.getElementById(inputId).value.toLowerCase();
    const table    = document.getElementById(tableId);
    const emptyMsg = document.getElementById(tableId + '-empty');
    if (!table) return;

    let visible = 0;
    table.querySelectorAll('tbody tr').forEach(function(row) {
        const match = row.textContent.toLowerCase().includes(filter);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    if (emptyMsg) {
        emptyMsg.style.display = (visible === 0 && filter !== '') ? 'block' : 'none';
    }
}
