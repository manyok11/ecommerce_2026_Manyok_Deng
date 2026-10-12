// =============================================================
// js/customer.js
// -------------------------------------------------------------
// Client-side validation for all customer-related forms.
// This runs in the browser BEFORE the form data is sent to the
// server. It gives instant feedback to the user without a page
// reload. The server (PHP action files) ALWAYS validates again
// independently — JS validation can be disabled or bypassed.
// =============================================================

// -------------------------------------------------------------
// Shared regex patterns — defined once, reused across forms.
// -------------------------------------------------------------

// Email: must have characters, then @, then a domain with a dot.
const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// Phone: 7–15 characters, digits only plus optional +, -, spaces.
const PHONE_REGEX = /^[0-9+\-\s]{7,15}$/;

// Password rules (mirrors the server-side check in login_action.php
// and register_action.php):
//   - At least 8 characters total
//   - At least one UPPERCASE letter  ((?=.*[A-Z]))
//   - At least one lowercase letter  ((?=.*[a-z]))
//   - At least one digit             ((?=.*\d))
//   - At least one special character ((?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]))
//
// This prevents simple passwords like "1234", "password", or
// "abcdefgh" from being accepted — they must look like a real
// password a person would actually use.
const PASSWORD_REGEX = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+\[\]{};':"\\|,.<>\/?]).{8,}$/;

// Name: at least 2 non-whitespace characters.
const NAME_REGEX = /^.{2,}$/;

// -------------------------------------------------------------
// showError / clearError
// -------------------------------------------------------------
// Each input has a matching <span id="<fieldId>-error"> next to
// it in the HTML. These two helpers write/clear that span so the
// user sees exactly which field failed and why.

function showError(fieldId, message) {
    const el = document.getElementById(fieldId + '-error');
    if (el) {
        el.textContent = message;
        el.style.color = '#c0392b';
        el.style.fontSize = '0.85em';
    }
}

function clearError(fieldId) {
    const el = document.getElementById(fieldId + '-error');
    if (el) el.textContent = '';
}

function clearAllErrors(fieldIds) {
    fieldIds.forEach(clearError);
}

// -------------------------------------------------------------
// registerCustomer()
// -------------------------------------------------------------
// Called by the Register button (onclick="registerCustomer()").
// Validates every field, sends the form via fetch() if valid,
// and shows the server's response message to the user.

function registerCustomer() {
    const fields = ['customer_name','customer_email','customer_pass',
                    'customer_country','customer_city','customer_contact'];
    clearAllErrors([...fields, 'form']);

    // Read values
    const name    = document.getElementById('customer_name').value.trim();
    const email   = document.getElementById('customer_email').value.trim();
    const pass    = document.getElementById('customer_pass').value; // don't trim passwords
    const country = document.getElementById('customer_country').value.trim();
    const city    = document.getElementById('customer_city').value.trim();
    const contact = document.getElementById('customer_contact').value.trim();

    let valid = true;

    // Validate each field individually so the user sees ALL errors at once
    if (!NAME_REGEX.test(name)) {
        showError('customer_name', 'Full name must be at least 2 characters.');
        valid = false;
    }

    if (!EMAIL_REGEX.test(email)) {
        showError('customer_email', 'Please enter a valid email address.');
        valid = false;
    }

    // Password: enforce real-password strength
    if (!PASSWORD_REGEX.test(pass)) {
        showError('customer_pass',
            'Password must be at least 8 characters and include an uppercase ' +
            'letter, a lowercase letter, a number, and a special character ' +
            '(e.g. MyPass1!).');
        valid = false;
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
        showError('customer_contact', 'Contact must be 7–15 digits (+ - spaces allowed).');
        valid = false;
    }

    // Stop here and let the user fix the highlighted fields
    if (!valid) return;

    // Disable the button to prevent double-submission
    const btn = document.getElementById('register-btn');
    if (btn) {
        btn.disabled = true;
        btn.textContent = 'Registering…';
    }

    // FormData picks up every input's name="" value from the form
    const formData = new FormData(document.getElementById('registerForm'));

    fetch('../actions/customer_register_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const msgEl = document.getElementById('formMessage');
        if (msgEl) {
            msgEl.textContent = data.message;
            msgEl.style.color = data.success ? '#27ae60' : '#c0392b';
        }
        if (data.success) {
            document.getElementById('registerForm').reset();
        }
    })
    .catch(() => {
        const msgEl = document.getElementById('formMessage');
        if (msgEl) {
            msgEl.textContent = 'A network error occurred. Please try again.';
            msgEl.style.color = '#c0392b';
        }
    })
    .finally(() => {
        if (btn) {
            btn.disabled = false;
            btn.textContent = 'Register';
        }
    });
}

// -------------------------------------------------------------
// loginCustomer()
// -------------------------------------------------------------
// Called by the Login button (onclick="loginCustomer()").
// Light validation only — just checks the fields aren't blank
// and the email looks valid. The server does the credential check.

function loginCustomer() {
    clearAllErrors(['login_email', 'login_pass', 'form']);

    const email = document.getElementById('login_email').value.trim();
    const pass  = document.getElementById('login_pass').value;

    let valid = true;

    if (!EMAIL_REGEX.test(email)) {
        showError('login_email', 'Please enter a valid email address.');
        valid = false;
    }

    if (pass.length === 0) {
        showError('login_pass', 'Please enter your password.');
        valid = false;
    }

    if (!valid) return;

    // Let the real form submit happen (login form uses method="POST"
    // and action pointing to login_action.php — no fetch needed here
    // because a successful login needs to set session cookies via PHP).
    document.getElementById('loginForm').submit();
}
