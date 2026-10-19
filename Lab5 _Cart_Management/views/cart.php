<?php
// views/cart.php - shopping cart page
// Shows all items in the cart for the current IP address.
// Two separate forms handle remove and update operations.

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$pageTitle  = 'My Cart — Shoppn';
$controller = new CartController();
$ip         = get_ip();
$items      = $controller->getCartItems($ip);
$total      = $controller->getCartTotal($ip);

$flashSuccess = get_flash('success');
$flashError   = get_flash('error');
$flashInfo    = get_flash('info');
$base         = BASE_URL;

include __DIR__ . '/layout/header.php';
?>

<main class="store-main">

    <nav class="store-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo $base; ?>index.php">Home</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"/>
        </svg>
        <span>Shopping Cart</span>
    </nav>

    <h1 class="cart-heading">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.95-1.57l1.65-8.42H6"/>
        </svg>
        Shopping Cart
        <?php if (!empty($items)): ?>
            <span class="cart-count-badge"><?php echo count($items); ?></span>
        <?php endif; ?>
    </h1>

    <?php if ($flashSuccess): ?>
        <div class="store-alert store-alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            <?php echo htmlspecialchars($flashSuccess); ?>
        </div>
    <?php endif; ?>
    <?php if ($flashError): ?>
        <div class="store-alert store-alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            <?php echo htmlspecialchars($flashError); ?>
        </div>
    <?php endif; ?>
    <?php if ($flashInfo): ?>
        <div class="store-alert store-alert-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <?php echo htmlspecialchars($flashInfo); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>

        <div class="cart-empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.95-1.57l1.65-8.42H6"/>
            </svg>
            <h2>Your cart is empty</h2>
            <p>Looks like you haven't added anything yet.</p>
            <a href="<?php echo $base; ?>index.php" class="btn-store btn-store-primary">
                Start Shopping
            </a>
        </div>

    <?php else: ?>

        <div class="cart-layout">

            <!-- Cart items table -->
            <div class="cart-table-wrap">

                <!--
                    Remove form — one checkbox per row (name="remove[]").
                    Submitting this form removes all ticked items at once.
                -->
                <form method="POST"
                      action="<?php echo $base; ?>actions/remove_from_cart_action.php"
                      id="removeForm">
                    <input type="hidden" name="csrf_token"
                           value="<?php echo htmlspecialchars(csrf_token()); ?>">

                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th class="col-remove"></th>
                                <th class="col-product">Product</th>
                                <th class="col-price">Price</th>
                                <th class="col-qty">Qty</th>
                                <th class="col-subtotal">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item):
                                $subtotal = (float)$item['product_price'] * (int)$item['qty'];
                            ?>
                            <tr class="cart-row">
                                <td class="col-remove">
                                    <label class="cart-check-label" aria-label="Remove item">
                                        <input type="checkbox" name="remove[]"
                                               value="<?php echo (int)$item['p_id']; ?>">
                                        <span class="cart-check-box">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <polyline points="20 6 9 17 4 12"/>
                                            </svg>
                                        </span>
                                    </label>
                                </td>
                                <td class="col-product">
                                    <div class="cart-product-cell">
                                        <?php if ($item['product_image']): ?>
                                            <img src="<?php echo $base . 'images/products/' . htmlspecialchars($item['product_image']); ?>"
                                                 alt="<?php echo htmlspecialchars($item['product_title']); ?>"
                                                 class="cart-product-img">
                                        <?php else: ?>
                                            <div class="cart-product-img cart-product-img--placeholder">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                                    <polyline points="21 15 16 10 5 21"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        <div class="cart-product-info">
                                            <a href="<?php echo $base; ?>views/single_product.php?pro_id=<?php echo (int)$item['p_id']; ?>"
                                               class="cart-product-name">
                                                <?php echo htmlspecialchars($item['product_title']); ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="col-price cart-price">
                                    GHS <?php echo number_format((float)$item['product_price'], 2); ?>
                                </td>
                                <td class="col-qty">
                                    <!--
                                        Qty inputs are part of the separate updateForm below.
                                        The hidden update[] field ties each qty to its product ID.
                                        We use JS to sync this into the update form on change.
                                    -->
                                    <input type="number"
                                           class="cart-qty-input"
                                           data-product-id="<?php echo (int)$item['p_id']; ?>"
                                           value="<?php echo (int)$item['qty']; ?>"
                                           min="1" max="99"
                                           aria-label="Quantity for <?php echo htmlspecialchars($item['product_title']); ?>">
                                </td>
                                <td class="col-subtotal cart-subtotal">
                                    GHS <?php echo number_format($subtotal, 2); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="cart-actions-bar">
                        <button type="submit" class="btn-store btn-store-outline cart-remove-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                            </svg>
                            Remove Selected
                        </button>
                        <a href="<?php echo $base; ?>index.php" class="btn-store btn-store-ghost">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="19" y1="12" x2="5" y2="12"/>
                                <polyline points="12 19 5 12 12 5"/>
                            </svg>
                            Continue Shopping
                        </a>
                    </div>
                </form>

                <!--
                    Update form — separate from the remove form so the two
                    operations never conflict. The qty inputs above write
                    their values here via JS before this form submits.
                -->
                <form method="POST"
                      action="<?php echo $base; ?>actions/update_qty_action.php"
                      id="updateForm"
                      style="display:none">
                    <input type="hidden" name="csrf_token"
                           value="<?php echo htmlspecialchars(csrf_token()); ?>">
                    <?php foreach ($items as $item): ?>
                        <input type="hidden" name="update[]"
                               value="<?php echo (int)$item['p_id']; ?>">
                        <input type="hidden" name="qty[]"
                               class="update-qty-field"
                               data-product-id="<?php echo (int)$item['p_id']; ?>"
                               value="<?php echo (int)$item['qty']; ?>">
                    <?php endforeach; ?>
                </form>

                <div style="margin-top:.75rem">
                    <button type="button"
                            class="btn-store btn-store-primary"
                            onclick="submitUpdateForm()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="1 4 1 10 7 10"/>
                            <path d="M3.51 15a9 9 0 1 0 .49-4.5"/>
                        </svg>
                        Update Cart
                    </button>
                </div>

            </div><!-- /.cart-table-wrap -->

            <!-- Order summary -->
            <div class="cart-summary-card">
                <h2 class="cart-summary-title">Order Summary</h2>

                <div class="cart-summary-row">
                    <span>Items (<?php echo count($items); ?>)</span>
                    <span>GHS <?php echo number_format($total, 2); ?></span>
                </div>
                <div class="cart-summary-row">
                    <span>Delivery</span>
                    <span class="cart-delivery-note">Calculated at checkout</span>
                </div>
                <div class="cart-summary-divider"></div>
                <div class="cart-summary-total">
                    <span>Total</span>
                    <span>GHS <?php echo number_format($total, 2); ?></span>
                </div>

                <!--
                    Checkout button links to views/checkout.php which
                    will be built in Task 13 (Payment Integration).
                -->
                <a href="<?php echo $base; ?>views/checkout.php"
                   class="btn-store btn-store-primary cart-checkout-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                        <line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                    Proceed to Checkout
                </a>

                <a href="<?php echo $base; ?>index.php"
                   class="btn-store btn-store-ghost"
                   style="width:100%;justify-content:center;margin-top:.6rem">
                    Continue Shopping
                </a>
            </div>

        </div><!-- /.cart-layout -->

    <?php endif; ?>

</main>

<?php include __DIR__ . '/layout/footer.php'; ?>

<script>
// When the user changes a qty input, sync the new value into
// the hidden update form fields before submission.
function syncQtyToUpdateForm() {
    document.querySelectorAll('.cart-qty-input').forEach(function(input) {
        const pid     = input.dataset.productId;
        const target  = document.querySelector('.update-qty-field[data-product-id="' + pid + '"]');
        if (target) target.value = input.value;
    });
}

function submitUpdateForm() {
    syncQtyToUpdateForm();
    document.getElementById('updateForm').submit();
}

// Live subtotal update when qty changes
document.querySelectorAll('.cart-qty-input').forEach(function(input) {
    input.addEventListener('input', function() {
        const row      = this.closest('.cart-row');
        const priceEl  = row.querySelector('.cart-price');
        const subEl    = row.querySelector('.cart-subtotal');
        if (!priceEl || !subEl) return;

        // Extract numeric price from "GHS 250.00"
        const price    = parseFloat(priceEl.textContent.replace('GHS', '').trim()) || 0;
        const qty      = parseInt(this.value, 10) || 1;
        const subtotal = (price * qty).toFixed(2);

        subEl.textContent = 'GHS ' + subtotal;
    });
});
</script>
