<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/CustomerController.php';

require_login();

$pageTitle  = 'My Profile — Shoppn Admin';
$activePage = 'profile';

$controller = new CustomerController();
$customer   = $controller->getCustomerById((int)$_SESSION['customer_id']);

if (!$customer) {
    set_flash('error', 'Could not load profile. Please log in again.');
    redirect(BASE_URL . 'views/login.php');
}

$flashSuccess = get_flash('success');
$flashError   = get_flash('error');

$avatarImg = $customer['customer_image'] ?? null;
$initials  = strtoupper(substr($customer['customer_name'], 0, 1));
$parts     = explode(' ', trim($customer['customer_name']));
if (count($parts) > 1) $initials = strtoupper(substr($parts[0],0,1) . substr(end($parts),0,1));

include __DIR__ . '/partials/head.php';
include __DIR__ . '/partials/sidebar.php';
?>

<style>
/* Confirmation modal */
.modal-overlay {
    display: none; position: fixed; inset: 0; z-index: 9000;
    background: rgba(15,23,42,.45);
    backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
    align-items: center; justify-content: center;
}
.modal-overlay.open { display: flex; animation: fade-in .18s ease; }
@keyframes fade-in { from { opacity:0 } to { opacity:1 } }
.modal-box {
    background: #fff; border-radius: 16px;
    box-shadow: 0 24px 64px rgba(15,23,42,.2);
    padding: 2rem; max-width: 380px; width: 90%; text-align: center;
    animation: slide-up .2s ease;
}
@keyframes slide-up { from { opacity:0; transform:translateY(16px) } to { opacity:1; transform:translateY(0) } }
.modal-icon {
    width: 52px; height: 52px; background: #FEE2E2; border-radius: 50%;
    display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;
}
.modal-icon svg { width: 24px; height: 24px; color: #DC2626; }
.modal-title { font-size: 1.05rem; font-weight: 700; color: #0F172A; margin-bottom: .4rem; }
.modal-desc  { font-size: .875rem; color: #64748B; margin-bottom: 1.5rem; }
.modal-actions { display: flex; gap: .75rem; justify-content: center; }
.modal-actions button { min-width: 110px; justify-content: center; }
</style>

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

<div class="page-header">
    <div class="page-header-left">
        <nav class="breadcrumb">
            <a href="<?php echo BASE_URL; ?>views/admin/dashboard.php">Dashboard</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <span>My Profile</span>
        </nav>
        <h1>My Profile</h1>
        <p>Manage your account information and profile photo.</p>
    </div>
</div>

<div class="two-col">

    <!-- Avatar card -->
    <div class="card glass--static">
        <div class="profile-avatar-wrap">
            <?php if ($avatarImg): ?>
                <div id="avatar-preview" class="avatar avatar--xl"
                     style="background-image:url('<?php echo BASE_URL . 'uploads/avatars/' . htmlspecialchars($avatarImg); ?>');background-size:cover;background-position:center;"
                     role="img" aria-label="Profile photo"></div>
            <?php else: ?>
                <div id="avatar-preview" class="avatar avatar--xl avatar-initials"
                     role="img" aria-label="Profile initials">
                    <?php echo htmlspecialchars($initials); ?>
                </div>
            <?php endif; ?>

            <div style="text-align:center">
                <div style="font-weight:700;font-size:1.1rem"><?php echo htmlspecialchars($customer['customer_name']); ?></div>
                <div style="font-size:.85rem;color:var(--muted)"><?php echo htmlspecialchars($customer['customer_email']); ?></div>
                <div style="margin-top:.4rem">
                    <span class="badge <?php echo (int)$customer['user_role'] === 1 ? 'badge-admin' : 'badge-customer'; ?>">
                        <?php echo (int)$customer['user_role'] === 1 ? 'Admin' : 'Customer'; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Upload form -->
        <form method="POST" action="<?php echo BASE_URL; ?>actions/upload_avatar_action.php"
              enctype="multipart/form-data" style="padding:0 1.5rem 1.25rem">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
            <div class="form-field">
                <label class="form-label" for="avatar_file">Change Photo</label>
                <!-- onchange shows a live preview before the user clicks Save -->
                <input type="file" id="avatar_file" name="avatar_file"
                       accept="image/jpeg,image/png,image/webp"
                       onchange="previewAvatar(this)">
                <span class="form-hint">JPG, PNG or WEBP &middot; max 2 MB</span>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                Save Photo
            </button>
        </form>

        <?php if ($avatarImg): ?>
        <!-- Remove photo — opens a custom in-page modal instead of browser confirm() -->
        <form id="removePhotoForm" method="POST"
              action="<?php echo BASE_URL; ?>actions/remove_avatar_action.php"
              style="padding:0 1.5rem 1.5rem">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
            <button type="button" class="btn btn-danger btn-sm" onclick="openRemoveModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                    <path d="M10 11v6"/><path d="M14 11v6"/>
                </svg>
                Remove Photo
            </button>
        </form>
        <?php endif; ?>
    </div>

    <!-- Account details -->
    <div class="card glass--static">
        <div class="card-header">
            <h2 class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Account Details
            </h2>
        </div>
        <dl style="display:grid;grid-template-columns:auto 1fr;gap:.6rem 1.5rem;font-size:.875rem;align-items:center">
            <dt style="color:var(--muted);font-weight:600">Name</dt>
            <dd><?php echo htmlspecialchars($customer['customer_name']); ?></dd>
            <dt style="color:var(--muted);font-weight:600">Email</dt>
            <dd><?php echo htmlspecialchars($customer['customer_email']); ?></dd>
            <dt style="color:var(--muted);font-weight:600">Country</dt>
            <dd><?php echo htmlspecialchars($customer['customer_country']); ?></dd>
            <dt style="color:var(--muted);font-weight:600">City</dt>
            <dd><?php echo htmlspecialchars($customer['customer_city']); ?></dd>
            <dt style="color:var(--muted);font-weight:600">Contact</dt>
            <dd><?php echo htmlspecialchars($customer['customer_contact']); ?></dd>
            <dt style="color:var(--muted);font-weight:600">Role</dt>
            <dd>
                <span class="badge <?php echo (int)$customer['user_role'] === 1 ? 'badge-admin' : 'badge-customer'; ?>">
                    <?php echo (int)$customer['user_role'] === 1 ? 'Admin' : 'Customer'; ?>
                </span>
            </dd>
        </dl>
    </div>

</div>

</div>

<!-- Custom remove photo confirmation modal -->
<div id="removeModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="modal-box">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
            </svg>
        </div>
        <div class="modal-title" id="modal-title">Remove Profile Photo?</div>
        <div class="modal-desc">Your photo will be permanently deleted. This cannot be undone.</div>
        <div class="modal-actions">
            <button type="button" class="btn btn-ghost" onclick="closeRemoveModal()">Cancel</button>
            <button type="button" class="btn btn-danger" onclick="confirmRemovePhoto()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                </svg>
                Yes, Remove
            </button>
        </div>
    </div>
</div>

<script>
function openRemoveModal()   { document.getElementById('removeModal').classList.add('open'); document.querySelector('#removeModal .btn-ghost').focus(); }
function closeRemoveModal()  { document.getElementById('removeModal').classList.remove('open'); }
function confirmRemovePhoto(){ closeRemoveModal(); document.getElementById('removePhotoForm').submit(); }
document.getElementById('removeModal').addEventListener('click', function(e){ if (e.target === this) closeRemoveModal(); });
document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeRemoveModal(); });
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
</main>
</div>
