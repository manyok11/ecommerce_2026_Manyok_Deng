<?php
// sidebar.php - shared admin sidebar
// $activePage is set by the including admin page to highlight the current link.
$activePage = $activePage ?? '';
$base       = BASE_URL;

// Returns the CSS class that highlights the active nav link.
function sidebarActive($page, $current) {
    return $page === $current ? ' active' : '';
}

$adminName = htmlspecialchars($_SESSION['customer_name'] ?? 'Admin');
$avatarImg = $_SESSION['customer_image'] ?? null;

// Build initials from the first letter of the first and last name.
$initials  = strtoupper(substr($_SESSION['customer_name'] ?? 'A', 0, 1));
$nameParts = explode(' ', trim($_SESSION['customer_name'] ?? ''));
if (count($nameParts) > 1) {
    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
}
?>

<!-- Dark overlay shown behind sidebar on mobile -->
<div id="sidebarOverlay" style="display:none;position:fixed;inset:0;z-index:190;background:rgba(0,0,0,.35);"></div>

<!-- Mobile hamburger button -->
<button id="sidebarToggle" class="sidebar-toggle" aria-label="Open menu">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="20" height="20">
        <line x1="3" y1="6"  x2="21" y2="6"/>
        <line x1="3" y1="12" x2="21" y2="12"/>
        <line x1="3" y1="18" x2="21" y2="18"/>
    </svg>
</button>

<aside id="sidebar" class="sidebar" role="navigation" aria-label="Admin navigation">

    <a href="<?php echo $base; ?>views/admin/dashboard.php" class="sidebar-logo">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="28" height="28">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        Shoppn
    </a>

    <nav class="sidebar-nav">
        <span class="sidebar-group-label">Main</span>

        <a href="<?php echo $base; ?>views/admin/dashboard.php"
           class="sidebar-link<?php echo sidebarActive('dashboard', $activePage); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            Dashboard
        </a>

        <a href="<?php echo $base; ?>views/admin/customers.php"
           class="sidebar-link<?php echo sidebarActive('customers', $activePage); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
            </svg>
            Customers
        </a>

        <span class="sidebar-group-label">Catalogue</span>

        <a href="<?php echo $base; ?>views/admin/brand.php"
           class="sidebar-link<?php echo sidebarActive('brands', $activePage); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                <line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
            Brands
        </a>

        <a href="<?php echo $base; ?>views/admin/category.php"
           class="sidebar-link<?php echo sidebarActive('categories', $activePage); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="8" y1="6"  x2="21" y2="6"/>
                <line x1="8" y1="12" x2="21" y2="12"/>
                <line x1="8" y1="18" x2="21" y2="18"/>
                <line x1="3" y1="6"  x2="3.01" y2="6"/>
                <line x1="3" y1="12" x2="3.01" y2="12"/>
                <line x1="3" y1="18" x2="3.01" y2="18"/>
            </svg>
            Categories
        </a>
    </nav>

    <!-- Sidebar footer: profile link and logout -->
    <div class="sidebar-footer">
        <a href="<?php echo $base; ?>views/admin/profile.php"
           class="sidebar-link<?php echo sidebarActive('profile', $activePage); ?>"
           style="margin:0;border-radius:var(--radius-xs)">
            <?php if ($avatarImg): ?>
                <img src="<?php echo $base . 'uploads/avatars/' . htmlspecialchars($avatarImg); ?>"
                     alt="<?php echo $adminName; ?>" class="avatar avatar--sm">
            <?php else: ?>
                <span class="avatar avatar--sm avatar-initials" style="font-size:.62rem">
                    <?php echo htmlspecialchars($initials); ?>
                </span>
            <?php endif; ?>
            <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.8rem">
                <?php echo $adminName; ?>
            </span>
        </a>
        <a href="<?php echo $base; ?>logout.php"
           class="sidebar-link" style="margin:.15rem 0 0;border-radius:var(--radius-xs)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Logout
        </a>
    </div>

</aside>
