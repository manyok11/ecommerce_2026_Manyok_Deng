<?php
// index.php - application entry point (customer-facing home page)
// Admins are sent straight to the dashboard.
// Customers and guests see the store landing page.

require_once 'core/core.php';

if (is_admin()) {
    redirect('views/admin/dashboard.php');
}

$flashSuccess = get_flash('success');
$flashError   = get_flash('error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shoppn — Welcome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #203A2A;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        a { text-decoration: none; }

        /* Navbar */
        .navbar { background: #203A2A; border-bottom: 1px solid rgba(255,255,255,.1); padding: 0 2rem; }
        .navbar-inner {
            max-width: 1100px; margin: 0 auto; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .nav-logo {
            color: #fff; font-size: 1.35rem; font-weight: 800;
            display: flex; align-items: center; gap: .5rem;
        }
        .nav-logo svg { width: 26px; height: 26px; }
        .nav-links { display: flex; align-items: center; gap: 1.5rem; list-style: none; }
        .nav-links a { color: rgba(255,255,255,.8); font-size: .9rem; font-weight: 500; transition: color .15s; }
        .nav-links a:hover { color: #fff; }
        .nav-links .btn-nav {
            background: #fff; color: #203A2A;
            padding: .4rem 1.1rem; border-radius: 9999px; font-weight: 600; font-size: .875rem;
        }
        .nav-links .btn-nav:hover { background: #e8f5ec; }

        /* Hero */
        .hero {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center; padding: 4rem 1.5rem;
            background: linear-gradient(160deg, #203A2A 0%, #376B46 60%, #4E9265 100%);
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: .5rem;
            background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
            color: #fff; font-size: .8rem; font-weight: 600;
            padding: .35rem .9rem; border-radius: 9999px; margin-bottom: 1.5rem;
        }
        .hero h1 {
            font-size: clamp(2rem, 5vw, 3.25rem); font-weight: 800; color: #fff;
            line-height: 1.15; margin-bottom: .75rem; letter-spacing: -.02em;
        }
        .hero-sub { font-size: 1.1rem; color: rgba(255,255,255,.75); margin-bottom: 2.25rem; max-width: 480px; }
        .hero-actions { display: flex; gap: .85rem; flex-wrap: wrap; justify-content: center; }

        .btn-white {
            background: #fff; color: #203A2A; padding: .75rem 1.75rem; border-radius: 9999px;
            font-weight: 700; font-size: .95rem; display: inline-flex; align-items: center; gap: .4rem;
            box-shadow: 0 4px 16px rgba(0,0,0,.2); transition: transform .15s, box-shadow .15s;
        }
        .btn-white:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.25); color: #203A2A; }
        .btn-outline-white {
            background: transparent; color: #fff; border: 2px solid rgba(255,255,255,.6);
            padding: .75rem 1.75rem; border-radius: 9999px; font-weight: 600; font-size: .95rem;
            display: inline-flex; align-items: center; gap: .4rem; transition: background .15s;
        }
        .btn-outline-white:hover { background: rgba(255,255,255,.1); border-color: #fff; color: #fff; }

        /* Features strip */
        .features { background: #fff; padding: 2.5rem 2rem; }
        .features-inner {
            max-width: 900px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(3,1fr); gap: 1.5rem; text-align: center;
        }
        .feature-icon {
            width: 48px; height: 48px; background: #EEF7F2; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; margin: 0 auto .75rem;
        }
        .feature-icon svg { width: 22px; height: 22px; color: #376B46; }
        .feature-title { font-weight: 700; color: #0F172A; margin-bottom: .25rem; font-size: .95rem; }
        .feature-desc  { font-size: .82rem; color: #64748B; }

        /* Flash messages */
        .flash-wrap { padding: .75rem 2rem 0; max-width: 700px; margin: 0 auto; }
        .msg-success { background:#DCFCE7; color:#166534; border:1px solid #BBF7D0; padding:.75rem 1rem; border-radius:8px; font-size:.9rem; margin-top:.75rem; }
        .msg-error   { background:#FEE2E2; color:#991B1B; border:1px solid #FECACA; padding:.75rem 1rem; border-radius:8px; font-size:.9rem; margin-top:.75rem; }

        /* Footer */
        .site-footer { background: #162B1F; color: rgba(255,255,255,.5); text-align: center; padding: 1.25rem; font-size: .8rem; }

        @media (max-width: 600px) {
            .features-inner { grid-template-columns: 1fr; }
            .hero h1 { font-size: 2rem; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <a href="index.php" class="nav-logo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 01-8 0"/>
            </svg>
            Shoppn
        </a>
        <ul class="nav-links">
            <?php if (is_logged_in()): ?>
                <?php if (is_admin()): ?>
                    <li><a href="views/admin/dashboard.php">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="views/login.php">Login</a></li>
                <li><a href="views/register.php" class="btn-nav">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<?php if ($flashSuccess || $flashError): ?>
<div class="flash-wrap">
    <?php if ($flashSuccess): ?><p class="msg-success"><?php echo htmlspecialchars($flashSuccess); ?></p><?php endif; ?>
    <?php if ($flashError):   ?><p class="msg-error"><?php echo htmlspecialchars($flashError); ?></p><?php endif; ?>
</div>
<?php endif; ?>

<section class="hero">
    <div class="hero-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
        </svg>
        Your one-stop online store
    </div>
    <h1>Welcome to<br>Shoppn</h1>
    <p class="hero-sub">Discover great products, great brands, and a great shopping experience — all in one place.</p>
    <div class="hero-actions">
        <?php if (!is_logged_in()): ?>
            <a href="views/login.php" class="btn-white">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15">
                    <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Sign In
            </a>
            <a href="views/register.php" class="btn-outline-white">Create Account</a>
        <?php else: ?>
            <p style="color:#fff;font-size:1rem">
                Welcome back, <strong><?php echo htmlspecialchars($_SESSION['customer_name']); ?></strong>!
            </p>
        <?php endif; ?>
    </div>
</section>

<div class="features">
    <div class="features-inner">
        <div>
            <div class="feature-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                    <line x1="7" y1="7" x2="7.01" y2="7"/>
                </svg>
            </div>
            <div class="feature-title">Top Brands</div>
            <div class="feature-desc">Shop from a curated selection of trusted brands.</div>
        </div>
        <div>
            <div class="feature-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 01-8 0"/>
                </svg>
            </div>
            <div class="feature-title">Easy Shopping</div>
            <div class="feature-desc">Browse categories, add to cart, and checkout in seconds.</div>
        </div>
        <div>
            <div class="feature-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                    <line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
            </div>
            <div class="feature-title">Secure Payments</div>
            <div class="feature-desc">Pay safely with Paystack — your details are always protected.</div>
        </div>
    </div>
</div>

<footer class="site-footer">
    &copy; <?php echo date('Y'); ?> Shoppn. All rights reserved.
</footer>

</body>
</html>
