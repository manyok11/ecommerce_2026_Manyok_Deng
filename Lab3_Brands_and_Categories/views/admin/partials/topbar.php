<?php
// topbar.php - sticky glass topbar included at the top of every admin page
$adminName = htmlspecialchars($_SESSION['customer_name'] ?? 'Admin');
$avatarImg = $_SESSION['customer_image'] ?? null;

$initials  = strtoupper(substr($_SESSION['customer_name'] ?? 'A', 0, 1));
$nameParts = explode(' ', trim($_SESSION['customer_name'] ?? ''));
if (count($nameParts) > 1) {
    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
}
$base = BASE_URL;
?>
<header class="topbar" role="banner">

    <div class="topbar-left">
        <!-- Search input — proxied to the active page's table filter via topbarSearchProxy() -->
        <div class="topbar-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="search" id="topbarSearch" placeholder="Search this page…"
                   autocomplete="off" oninput="topbarSearchProxy(this.value)">
        </div>
    </div>

    <div class="topbar-right">
        <a href="<?php echo $base; ?>index.php" class="topbar-store-link" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/>
                <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
            </svg>
            View Store
        </a>

        <div class="topbar-user">
            <button id="topbarAvatarBtn" class="topbar-avatar-btn"
                    aria-haspopup="true" aria-expanded="false">
                <?php if ($avatarImg): ?>
                    <img src="<?php echo $base . 'uploads/avatars/' . htmlspecialchars($avatarImg); ?>"
                         alt="<?php echo $adminName; ?>" class="avatar avatar--sm">
                <?php else: ?>
                    <span class="avatar avatar--sm avatar-initials" style="font-size:.62rem">
                        <?php echo htmlspecialchars($initials); ?>
                    </span>
                <?php endif; ?>
                <span class="topbar-user-name"><?php echo $adminName; ?></span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>

            <div id="topbarDropdown" class="topbar-dropdown" role="menu">
                <a href="<?php echo $base; ?>views/admin/profile.php" role="menuitem">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    My Profile
                </a>
                <div class="topbar-dropdown-divider"></div>
                <a href="<?php echo $base; ?>logout.php" role="menuitem">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </div>

</header>

<script>
// Proxy the topbar search input to the active page's table filter.
// Each admin page sets window.pageFilterConfig = { inputId, tableId }.
function topbarSearchProxy(val) {
    if (window.pageFilterConfig) {
        var inp = document.getElementById(window.pageFilterConfig.inputId);
        if (inp) {
            inp.value = val;
            filterTable(window.pageFilterConfig.inputId, window.pageFilterConfig.tableId);
        }
    }
}
</script>
