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
    <title>Login — Shoppn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="auth-body">

<div class="auth-card glass">

    <div class="auth-logo">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        Shoppn
    </div>

    <h1 class="auth-title">Welcome back</h1>
    <p class="auth-sub">Sign in to your account to continue.</p>

    <?php if ($error): ?>
        <div class="alert alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form id="loginForm" method="POST" action="../actions/login_action.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">

        <div class="form-field">
            <label class="form-label" for="login_email">Email Address</label>
            <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                <input type="email" id="login_email" name="login_email"
                       placeholder="you@example.com" autocomplete="email"
                       value="<?php echo htmlspecialchars($_POST['login_email'] ?? ''); ?>">
            </div>
            <span id="login_email-error" class="form-error"></span>
        </div>

        <div class="form-field">
            <label class="form-label" for="login_pass">Password</label>
            <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
                <input type="password" id="login_pass" name="login_pass" autocomplete="current-password">
                <button type="button" class="toggle-pass"
                        onclick="togglePassword('toggleLoginPass','login_pass')"
                        id="toggleLoginPass" aria-label="Show/hide password">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <path d="M1 12S5 4 12 4s11 8 11 8-4 8-11 8S1 12 1 12z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            <span id="login_pass-error" class="form-error"></span>
        </div>

        <button type="button" class="btn btn-auth" style="margin-top:.5rem" onclick="loginCustomer()">
            Sign In
        </button>
    </form>

    <p class="auth-footer">
        Don't have an account? <a href="register.php">Create one</a>
    </p>

</div>

<script src="../assets/js/admin.js"></script>
<script src="../js/validate.js"></script>
</body>
</html>
