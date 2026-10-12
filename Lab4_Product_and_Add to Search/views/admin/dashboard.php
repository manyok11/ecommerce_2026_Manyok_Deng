<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/CustomerController.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

require_admin();

$pageTitle  = 'Dashboard — Shoppn Admin';
$activePage = 'dashboard';

$custCtrl  = new CustomerController();
$prodCtrl  = new ProductController();
$custStats = $custCtrl->getCustomerStats();
$summary   = $prodCtrl->getStoreSummary();  // now includes total_products
$recent    = $custCtrl->getRecentCustomers(5);

$flashSuccess = get_flash('success');
$flashError   = get_flash('error');

$firstName = explode(' ', trim($_SESSION['customer_name'] ?? 'Admin'))[0];
$hour      = (int)date('G');
$greeting  = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');

$avatarImg = $_SESSION['customer_image'] ?? null;
$initials  = strtoupper(substr($_SESSION['customer_name'] ?? 'A', 0, 1));
$nameParts = explode(' ', trim($_SESSION['customer_name'] ?? ''));
if (count($nameParts) > 1) {
    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
}

include __DIR__ . '/partials/head.php';
include __DIR__ . '/partials/sidebar.php';
?>

<div class="admin-shell">
<main class="admin-main">
<?php include __DIR__ . '/partials/topbar.php'; ?>
<div class="page-content">

<?php if ($flashSuccess): ?>
<script>document.addEventListener('DOMContentLoaded',function(){ showToast(<?php echo json_encode($flashSuccess); ?>,'success'); });</script>
<?php endif; ?>
<?php if ($flashError): ?>
<script>document.addEventListener('DOMContentLoaded',function(){ showToast(<?php echo json_encode($flashError); ?>,'error'); });</script>
<?php endif; ?>

<!-- Welcome banner -->
<div class="welcome-card anim-fade-up">
    <div>
        <div class="greeting"><?php echo $greeting . ', ' . htmlspecialchars($firstName); ?> 👋</div>
        <div class="date"><?php echo date('l, d F Y'); ?> &mdash; Here is what is happening in your store</div>
    </div>
    <?php if ($avatarImg): ?>
        <img src="<?php echo BASE_URL . 'uploads/avatars/' . htmlspecialchars($avatarImg); ?>"
             alt="<?php echo htmlspecialchars($_SESSION['customer_name']); ?>" class="avatar avatar--lg">
    <?php else: ?>
        <span class="avatar avatar--lg avatar-initials"><?php echo htmlspecialchars($initials); ?></span>
    <?php endif; ?>
</div>

<!-- Stat cards — now 5: customers, brands, categories, products, admins -->
<div class="stat-grid" style="grid-template-columns:repeat(5,1fr)">

    <div class="card stat-card glass--static anim-fade-up anim-delay-1">
        <div class="stat-tile stat-tile--green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
            </svg>
        </div>
        <div class="stat-num num"><?php echo number_format($custStats['role_customer']); ?></div>
        <div class="stat-lbl">Customers</div>
        <a href="<?php echo BASE_URL; ?>views/admin/customers.php" class="stat-link">
            View all <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
    </div>

    <div class="card stat-card glass--static anim-fade-up anim-delay-2">
        <div class="stat-tile stat-tile--teal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                <line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
        </div>
        <div class="stat-num num"><?php echo number_format($summary['total_brands']); ?></div>
        <div class="stat-lbl">Brands</div>
        <a href="<?php echo BASE_URL; ?>views/admin/brand.php" class="stat-link">
            Manage <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
    </div>

    <div class="card stat-card glass--static anim-fade-up anim-delay-3">
        <div class="stat-tile stat-tile--gold">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                <line x1="8" y1="18" x2="21" y2="18"/>
                <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/>
                <line x1="3" y1="18" x2="3.01" y2="18"/>
            </svg>
        </div>
        <div class="stat-num num"><?php echo number_format($summary['total_categories']); ?></div>
        <div class="stat-lbl">Categories</div>
        <a href="<?php echo BASE_URL; ?>views/admin/category.php" class="stat-link">
            Manage <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
    </div>

    <!-- Products stat — new for Task 9 -->
    <div class="card stat-card glass--static anim-fade-up anim-delay-4">
        <div class="stat-tile" style="background:linear-gradient(135deg,#376B46,#4E9265);color:#fff">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                <line x1="8" y1="21" x2="16" y2="21"/>
                <line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
        </div>
        <div class="stat-num num"><?php echo number_format($summary['total_products']); ?></div>
        <div class="stat-lbl">Products</div>
        <a href="<?php echo BASE_URL; ?>views/admin/product.php" class="stat-link">
            Manage <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
    </div>

    <div class="card stat-card glass--static anim-fade-up" style="animation-delay:.25s;opacity:0">
        <div class="stat-tile stat-tile--violet">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        </div>
        <div class="stat-num num"><?php echo number_format($custStats['role_admin']); ?></div>
        <div class="stat-lbl">Admins</div>
        <a href="<?php echo BASE_URL; ?>views/admin/customers.php" class="stat-link">
            View <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
    </div>

</div>

<!-- Quick actions -->
<div class="quick-actions anim-fade-up">
    <a href="<?php echo BASE_URL; ?>views/admin/product.php" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Product
    </a>
    <a href="<?php echo BASE_URL; ?>views/admin/brand.php" class="btn btn-outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Brand
    </a>
    <a href="<?php echo BASE_URL; ?>views/admin/category.php" class="btn btn-outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Category
    </a>
    <a href="<?php echo BASE_URL; ?>views/admin/customers.php" class="btn btn-ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
        </svg>
        View Customers
    </a>
    <a href="<?php echo BASE_URL; ?>index.php" class="btn btn-ghost" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/>
            <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
        </svg>
        View Store
    </a>
</div>

<!-- Two-column lower section -->
<div class="two-col">

    <!-- Recent accounts table -->
    <div class="card glass--static anim-fade-up">
        <div class="card-header">
            <h2 class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                </svg>
                Recent Accounts
                <span class="badge badge-count"><?php echo count($recent); ?></span>
            </h2>
            <a href="<?php echo BASE_URL; ?>views/admin/customers.php" class="btn btn-ghost btn-sm">
                View all <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
        <?php if (empty($recent)): ?>
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                <p>No customers have registered yet.</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>Name</th><th>Email</th><th>Country</th><th>Role</th></tr></thead>
                    <tbody>
                        <?php foreach ($recent as $c):
                            $ci = strtoupper(substr($c['customer_name'], 0, 1));
                            $cp = explode(' ', trim($c['customer_name']));
                            if (count($cp) > 1) $ci = strtoupper(substr($cp[0],0,1) . substr(end($cp),0,1));
                            $ca = $c['customer_image'] ?? null;
                        ?>
                        <tr>
                            <td>
                                <div class="row-identity">
                                    <?php if ($ca): ?>
                                        <img src="<?php echo BASE_URL . 'uploads/avatars/' . htmlspecialchars($ca); ?>" alt="" class="avatar avatar--sm">
                                    <?php else: ?>
                                        <span class="avatar avatar--sm avatar-initials" style="font-size:.58rem"><?php echo htmlspecialchars($ci); ?></span>
                                    <?php endif; ?>
                                    <span><?php echo htmlspecialchars($c['customer_name']); ?></span>
                                </div>
                            </td>
                            <td style="color:var(--muted);font-size:.8rem"><?php echo htmlspecialchars($c['customer_email']); ?></td>
                            <td><?php echo htmlspecialchars($c['customer_country']); ?></td>
                            <td>
                                <?php if ((int)$c['user_role'] === 1): ?>
                                    <span class="badge badge-admin">Admin</span>
                                <?php else: ?>
                                    <span class="badge badge-customer">Customer</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Store setup checklist -->
    <div class="card glass--static anim-fade-up">
        <div class="card-header">
            <h2 class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 11 12 14 22 4"/>
                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                </svg>
                Store Setup
            </h2>
        </div>
        <ul class="setup-list">
            <li class="setup-item <?php echo $summary['total_brands'] > 0 ? 'setup-item--ok' : 'setup-item--warn'; ?>">
                <?php if ($summary['total_brands'] > 0): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <span><?php echo $summary['total_brands']; ?> brand(s) configured</span>
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>No brands — <a href="<?php echo BASE_URL; ?>views/admin/brand.php">add one</a> first</span>
                <?php endif; ?>
            </li>
            <li class="setup-item <?php echo $summary['total_categories'] > 0 ? 'setup-item--ok' : 'setup-item--warn'; ?>">
                <?php if ($summary['total_categories'] > 0): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <span><?php echo $summary['total_categories']; ?> categorie(s) configured</span>
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>No categories — <a href="<?php echo BASE_URL; ?>views/admin/category.php">add one</a></span>
                <?php endif; ?>
            </li>
            <li class="setup-item <?php echo $summary['total_products'] > 0 ? 'setup-item--ok' : 'setup-item--warn'; ?>">
                <?php if ($summary['total_products'] > 0): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <span><?php echo $summary['total_products']; ?> product(s) in the store</span>
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>No products yet — <a href="<?php echo BASE_URL; ?>views/admin/product.php">add your first product</a></span>
                <?php endif; ?>
            </li>
            <li class="setup-item setup-item--ok">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <span>Customer registration active</span>
            </li>
        </ul>
    </div>

</div>

</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
</main>
</div>
