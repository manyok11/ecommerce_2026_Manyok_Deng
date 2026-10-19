<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/CustomerController.php';

require_admin();

$pageTitle  = 'Customers — Shoppn Admin';
$activePage = 'customers';

$controller   = new CustomerController();
$customers    = $controller->getAllCustomers();
$stats        = $controller->getCustomerStats();
$flashSuccess = get_flash('success');

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

<div class="page-header">
    <div class="page-header-left">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="<?php echo BASE_URL; ?>views/admin/dashboard.php">Dashboard</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <span>Customers</span>
        </nav>
        <h1>All Customers</h1>
        <p>
            <span class="badge badge-count"><?php echo $stats['total']; ?> total</span>
            &nbsp;
            <span class="badge badge-admin"><?php echo $stats['role_admin']; ?> admin<?php echo $stats['role_admin'] !== 1 ? 's' : ''; ?></span>
            &nbsp;
            <span class="badge badge-customer"><?php echo $stats['role_customer']; ?> customer<?php echo $stats['role_customer'] !== 1 ? 's' : ''; ?></span>
        </p>
    </div>
</div>

<div class="card glass--static">
    <div class="card-header">
        <h2 class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
            </svg>
            Customer List
            <span class="badge badge-count num"><?php echo count($customers); ?></span>
        </h2>
        <?php if (!empty($customers)): ?>
        <div class="search-input-wrap" style="max-width:260px">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="search" id="custSearch" placeholder="Search name, email, country…"
                   oninput="filterTable('custSearch','custsTable')">
        </div>
        <?php endif; ?>
    </div>

    <?php if (empty($customers)): ?>
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
            </svg>
            <p>No customers have registered yet.</p>
        </div>
    <?php else: ?>
    <div class="table-wrap">
        <table class="data-table" id="custsTable">
            <thead>
                <tr>
                    <th>ID</th><th>Name</th><th>Email</th>
                    <th>Country</th><th>City</th><th>Contact</th><th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $c):
                    $ci = strtoupper(substr($c['customer_name'], 0, 1));
                    $cp = explode(' ', trim($c['customer_name']));
                    if (count($cp) > 1) $ci = strtoupper(substr($cp[0],0,1) . substr(end($cp),0,1));
                    $ca = $c['customer_image'] ?? null;
                ?>
                <tr>
                    <td class="num" style="color:var(--muted-lt);font-size:.8rem"><?php echo (int)$c['customer_id']; ?></td>
                    <td>
                        <div class="row-identity">
                            <?php if ($ca): ?>
                                <img src="<?php echo BASE_URL . 'uploads/avatars/' . htmlspecialchars($ca); ?>"
                                     alt="" class="avatar avatar--sm">
                            <?php else: ?>
                                <span class="avatar avatar--sm avatar-initials" style="font-size:.58rem">
                                    <?php echo htmlspecialchars($ci); ?>
                                </span>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($c['customer_name']); ?>
                        </div>
                    </td>
                    <td style="color:var(--muted);font-size:.82rem"><?php echo htmlspecialchars($c['customer_email']); ?></td>
                    <td><?php echo htmlspecialchars($c['customer_country']); ?></td>
                    <td><?php echo htmlspecialchars($c['customer_city']); ?></td>
                    <td><?php echo htmlspecialchars($c['customer_contact']); ?></td>
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
    <p id="custsTable-empty" style="display:none;color:var(--muted);font-size:.85rem;padding:.75rem 0">
        No customers match your search.
    </p>
    <?php endif; ?>
</div>

</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
</main>
</div>

<script>
window.pageFilterConfig = { inputId: 'custSearch', tableId: 'custsTable' };
</script>
