<?php
/**
 * pages/cart.php — Giỏ hàng
 */
$pageTitle = 'Giỏ hàng — UmaCT Shop';
$activeNav = 'shop';

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../utils/format_helper.php';
require_once __DIR__ . '/../models/cart_model.php';

requireLogin();
$currentUser = getCurrentUser();
$cartItems   = getCart($currentUser['user_id']);
$total       = calcCartTotal($cartItems);

require_once __DIR__ . '/includes/header.php';
?>

<div style="background:var(--bg-surface); border-bottom:1px solid var(--border); padding:24px 0">
    <div class="container">
        <h1 style="font-family:'Space Grotesk',sans-serif; font-size:1.5rem; font-weight:800">
            🛒 Giỏ hàng <span style="color:var(--text-muted); font-weight:400; font-size:1rem">(<?= count($cartItems) ?> sản phẩm)</span>
        </h1>
    </div>
</div>

<div class="container section">
    <?php if(empty($cartItems)): ?>
        <div class="empty-state">
            <div class="empty-icon">🛒</div>
            <div class="empty-title">Giỏ hàng của bạn đang trống</div>
            <div class="empty-desc">Hãy khám phá cửa hàng và thêm những sản phẩm yêu thích</div>
            <a href="<?= BASE_URL ?>/pages/shop.php" class="btn-hero-primary" style="display:inline-flex; margin-top:20px">
                🛍 Mua sắm ngay
            </a>
        </div>
    <?php else: ?>
    <div class="cart-layout">

        <!-- Cart Items -->
        <div>
            <div class="cart-table-wrap">
                <div class="cart-header">
                    <span>Sản phẩm</span>
                    <span>Đơn giá</span>
                    <span>Số lượng</span>
                    <span>Thành tiền</span>
                    <span></span>
                </div>

                <?php foreach($cartItems as $item): ?>
                <div class="cart-row" id="row-<?= $item['cart_id'] ?>">
                    <!-- Product Info -->
                    <div class="cart-product">
                        <div class="cart-img">
                            <img src="<?= !empty($item['image_url']) ? htmlspecialchars($item['image_url']) : BASE_URL . '/assets/images/no-image.png' ?>"
                                 alt="<?= htmlspecialchars($item['product_name']) ?>"
                                 onerror="this.src='<?= BASE_URL ?>/assets/images/no-image.png'">
                        </div>
                        <div>
                            <div class="cart-product-name"><?= htmlspecialchars($item['product_name']) ?></div>
                            <div class="cart-product-cat">#UMA<?= str_pad($item['product_id'], 4, '0', STR_PAD_LEFT) ?></div>
                        </div>
                    </div>

                    <!-- Đơn giá -->
                    <div class="cart-price"><?= formatPrice($item['price']) ?></div>

                    <!-- Số lượng -->
                    <div style="display:flex; align-items:center; gap:0">
                        <button class="qty-btn" onclick="updateQty(<?= $item['cart_id'] ?>, -1)">−</button>
                        <input type="number" class="qty-input"
                               id="qty-<?= $item['cart_id'] ?>"
                               value="<?= $item['quantity'] ?>" min="1"
                               onchange="updateQtyDirect(<?= $item['cart_id'] ?>, this.value)"
                               style="width:48px">
                        <button class="qty-btn" onclick="updateQty(<?= $item['cart_id'] ?>, 1)">+</button>
                    </div>

                    <!-- Thành tiền -->
                    <div class="cart-subtotal" id="sub-<?= $item['cart_id'] ?>"><?= formatPrice($item['subtotal']) ?></div>

                    <!-- Remove -->
                    <button class="cart-remove" onclick="removeItem(<?= $item['cart_id'] ?>)" title="Xóa">✕</button>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Bottom actions -->
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; flex-wrap:wrap; gap:12px">
                <a href="<?= BASE_URL ?>/pages/shop.php" class="btn-hero-secondary" style="font-size:.85rem">
                    ← Tiếp tục mua sắm
                </a>
                <button onclick="clearCart()" class="btn-hero-secondary" style="font-size:.85rem; background:rgba(239,68,68,.1); border-color:rgba(239,68,68,.2); color:#f87171">
                    🗑 Xóa toàn bộ
                </button>
            </div>
        </div>

        <!-- Cart Summary -->
        <div class="cart-summary">
            <div class="summary-title">📋 Tóm tắt đơn hàng</div>
            <div class="summary-row">
                <span class="summary-label">Tạm tính (<?= count($cartItems) ?> SP)</span>
                <span id="cartSubtotal"><?= formatPrice($total) ?></span>
            </div>
            <div class="summary-row" id="discountRow" style="display:none">
                <span class="summary-label" style="color:#4ade80">🎟 Giảm giá</span>
                <span id="discountVal" style="color:#4ade80">-0 ₫</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Phí vận chuyển</span>
                <span id="shippingVal"><?= $total >= 500000 ? '<span style="color:#4ade80">Miễn phí</span>' : formatPrice(30000) ?></span>
            </div>

            <!-- Voucher -->
            <div class="voucher-input-row">
                <input type="text" class="voucher-input" id="voucherCode" placeholder="Nhập mã giảm giá...">
                <button class="btn-apply-voucher" onclick="applyVoucher()">Áp dụng</button>
            </div>
            <div id="voucherMsg" style="font-size:.78rem; margin-top:-4px; margin-bottom:8px"></div>

            <div class="summary-row" style="border-bottom:none; margin-top:8px">
                <span style="font-weight:700">Tổng cộng</span>
                <span class="summary-total" id="cartTotal"><?= formatPrice($total) ?></span>
            </div>

            <a href="<?= BASE_URL ?>/pages/checkout.php" class="btn-checkout" id="btnCheckout">
                💳 Tiến hành thanh toán
            </a>

            <div style="margin-top:14px; display:flex; gap:8px; justify-content:center; flex-wrap:wrap">
                <span style="font-size:.72rem; color:var(--text-dim)">🔒 Thanh toán bảo mật</span>
                <span style="font-size:.72rem; color:var(--text-dim)">|</span>
                <span style="font-size:.72rem; color:var(--text-dim)">🚚 Giao hàng toàn quốc</span>
            </div>
        </div>

    </div>
    <?php endif; ?>
</div>

<script>
const BASE = '<?= BASE_URL ?>';
let currentDiscount = 0;
let currentVoucherId = null;

function updateQty(cartId, delta) {
    const input = document.getElementById('qty-' + cartId);
    let val = parseInt(input.value) + delta;
    if (val < 1) { removeItem(cartId); return; }
    input.value = val;
    sendUpdateQty(cartId, val);
}
function updateQtyDirect(cartId, val) {
    val = parseInt(val);
    if (val < 1) { removeItem(cartId); return; }
    sendUpdateQty(cartId, val);
}
function sendUpdateQty(cartId, qty) {
    fetch(BASE + '/api_actions/cart_update.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ cart_id: cartId, quantity: qty })
    })
    .then(r => r.json())
    .then(d => { if (d.status === 'success') refreshTotal(); });
}
function removeItem(cartId) {
    fetch(BASE + '/api_actions/cart_remove.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ cart_id: cartId })
    })
    .then(r => r.json())
    .then(d => { if (d.status === 'success') document.getElementById('row-'+cartId)?.remove(); refreshTotal(); });
}
function clearCart() {
    if (!confirm('Xóa toàn bộ giỏ hàng?')) return;
    fetch(BASE + '/api_actions/cart_clear.php', { method: 'POST' })
    .then(r => r.json())
    .then(d => { if (d.status === 'success') location.reload(); });
}
function applyVoucher() {
    const code = document.getElementById('voucherCode').value.trim();
    const msgEl = document.getElementById('voucherMsg');
    if (!code) return;
    fetch(BASE + '/api_actions/voucher_check.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ code: code, order_value: getSubtotal() })
    })
    .then(r => r.json())
    .then(d => {
        if (d.status === 'success') {
            currentDiscount = d.discount;
            currentVoucherId = d.voucher_id;
            msgEl.style.color = '#4ade80';
            msgEl.textContent = '✓ Áp dụng thành công! Giảm ' + formatVND(d.discount);
            document.getElementById('discountRow').style.display = 'flex';
            document.getElementById('discountVal').textContent = '-' + formatVND(d.discount);
            refreshTotal();
        } else {
            msgEl.style.color = '#f87171';
            msgEl.textContent = '✕ ' + (d.message || 'Mã không hợp lệ');
        }
    });
}
function getSubtotal() {
    let total = 0;
    document.querySelectorAll('.cart-row').forEach(row => {
        const id   = row.id.replace('row-','');
        const qty  = parseInt(document.getElementById('qty-'+id)?.value || 0);
        // Grab price from data or recalc — simplified: parse displayed subtotal
    });
    return total;
}
function refreshTotal() { location.reload(); }
function formatVND(n) { return new Intl.NumberFormat('vi-VN').format(n) + ' ₫'; }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
