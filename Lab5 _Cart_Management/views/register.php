<?php
require_once __DIR__ . '/../core/core.php';

if (is_logged_in()) redirect('../index.php');

$error   = get_flash('error');
$success = get_flash('success');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — Shoppn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="auth-body" style="align-items:flex-start;padding:2rem 1rem">

<div class="auth-card glass" style="max-width:500px;margin:0 auto">

    <div class="auth-logo">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        Shoppn
    </div>

    <h1 class="auth-title">Create an account</h1>
    <p class="auth-sub">Join Shoppn — it only takes a minute.</p>

    <?php if ($error): ?>
        <div class="alert alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form id="registerForm" method="POST" action="../actions/customer_register_action.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">

        <div class="form-field">
            <label class="form-label" for="customer_name">Full Name</label>
            <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <input type="text" id="customer_name" name="customer_name"
                       placeholder="Jane Doe" autocomplete="name">
            </div>
            <span id="customer_name-error" class="form-error"></span>
        </div>

        <div class="form-field">
            <label class="form-label" for="customer_email">Email Address</label>
            <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                <input type="email" id="customer_email" name="customer_email"
                       placeholder="you@example.com" autocomplete="email">
            </div>
            <span id="customer_email-error" class="form-error"></span>
        </div>

        <div class="form-field">
            <label class="form-label" for="customer_pass">Password</label>
            <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
                <input type="password" id="customer_pass" name="customer_pass" autocomplete="new-password">
                <button type="button" class="toggle-pass"
                        onclick="togglePassword('toggleRegPass','customer_pass')"
                        id="toggleRegPass" aria-label="Show/hide password">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <path d="M1 12S5 4 12 4s11 8 11 8-4 8-11 8S1 12 1 12z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            <span class="form-hint">Min 8 chars · uppercase · lowercase · number · special character</span>
            <span id="customer_pass-error" class="form-error"></span>
        </div>

        <div class="form-field">
            <label class="form-label" for="customer_pass_confirm">Confirm Password</label>
            <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
                <input type="password" id="customer_pass_confirm" name="customer_pass_confirm"
                       placeholder="Re-enter your password" autocomplete="new-password">
                <button type="button" class="toggle-pass"
                        onclick="togglePassword('toggleRegConfirm','customer_pass_confirm')"
                        id="toggleRegConfirm" aria-label="Show/hide confirm password">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <path d="M1 12S5 4 12 4s11 8 11 8-4 8-11 8S1 12 1 12z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            <span id="customer_pass_confirm-error" class="form-error"></span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
            <div class="form-field" style="margin-bottom:0">
                <label class="form-label" for="customer_country">Country</label>
                <input type="text" id="customer_country" name="customer_country"
                       placeholder="Ghana" autocomplete="country-name">
                <span id="customer_country-error" class="form-error"></span>
            </div>
            <div class="form-field" style="margin-bottom:0">
                <label class="form-label" for="customer_city">City</label>
                <input type="text" id="customer_city" name="customer_city"
                       placeholder="Accra" autocomplete="address-level2">
                <span id="customer_city-error" class="form-error"></span>
            </div>
        </div>
        <div style="margin-bottom:1.1rem"></div>

        <div class="form-field">
            <label class="form-label" for="customer_contact">Contact Number</label>
            <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 .18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7a2 2 0 011.72 2.03z"/>
                </svg>
                <input type="tel" id="customer_contact" name="customer_contact"
                       placeholder="+233241234567" autocomplete="tel">
            </div>
            <span id="customer_contact-error" class="form-error"></span>
        </div>

        <button type="button" id="register-btn" class="btn btn-auth" onclick="registerCustomer()">
            Create Account
        </button>

        <p id="formMessage" style="margin-top:.75rem;font-size:.875rem;text-align:center" aria-live="polite"></p>
    </form>

    <p class="auth-footer">
        Already have an account? <a href="login.php">Sign in</a>
    </p>

</div>

<script src="../assets/js/admin.js"></script>
<script src="../js/validate.js"></script>
</body>
</html>
