<?php
/**
 * Lvs_pages/Lvs_cart.php — Giỏ hàng
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
$pageTitle = 'Giỏ hàng — UmaCT Shop';
$activeNav = 'shop';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../utils/Lvs_format_helper.php';
require_once __DIR__ . '/../models/Lvs_cart_model.php';

Lvs_requireLogin();
$Lvs_user  = Lvs_getCurrentUser();
$Lvs_items = Lvs_getCart($Lvs_user['user_id']);
$Lvs_total = Lvs_calcCartTotal($Lvs_items);

require_once __DIR__ . '/includes/Lvs_header.php';
?>
<div style="background:var(--bg-surface);border-bottom:1px solid var(--border);padding:24px 0">
    <div class="container">
        <h1 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:800">
            🛒 Giỏ hàng <span style="color:var(--text-muted);font-weight:400;font-size:1rem">(<?= count($Lvs_items) ?> sản phẩm)</span>
        </h1>
    </div>
</div>

<div class="container section">
<?php if (empty($Lvs_items)): ?>
    <div class="empty-state">
        <div class="empty-icon">🛒</div>
        <div class="empty-title">Giỏ hàng của bạn đang trống</div>
        <div class="empty-desc">Hãy khám phá cửa hàng và thêm những sản phẩm yêu thích</div>
        <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php" class="btn-hero-primary" style="display:inline-flex;margin-top:20px">🛍 Mua sắm ngay</a>
    </div>
<?php else: ?>
    <div class="cart-layout">
        <div>
            <div class="cart-table-wrap">
                <div class="cart-header">
                    <span>Sản phẩm</span><span>Đơn giá</span><span>Số lượng</span><span>Thành tiền</span><span></span>
                </div>
                <?php foreach ($Lvs_items as $Lvs_item):
                    // Ảnh: backend trả main_image
                    $Lvs_img = !empty($Lvs_item['main_image']) ? $Lvs_item['main_image'] : (!empty($Lvs_item['image_url']) ? $Lvs_item['image_url'] : BASE_URL.'/assets/images/no-image.png');
                    $Lvs_subtotal = ($Lvs_item['price'] ?? 0) * ($Lvs_item['quantity'] ?? 1);
                ?>
                <div class="cart-row" id="Lvs_row-<?= $Lvs_item['cart_id'] ?>">
                    <div class="cart-product">
                        <div class="cart-img">
                            <img src="<?= htmlspecialchars($Lvs_img) ?>" alt="<?= htmlspecialchars($Lvs_item['name']) ?>"
                                 onerror="this.src='<?= BASE_URL ?>/assets/images/no-image.png'">
                        </div>
                        <div>
                            <div class="cart-product-name"><?= htmlspecialchars($Lvs_item['name']) ?></div>
                            <div class="cart-product-cat">#UMA<?= str_pad($Lvs_item['id'], 4, '0', STR_PAD_LEFT) ?></div>
                        </div>
                    </div>
                    <div class="cart-price"><?= Lvs_formatPrice($Lvs_item['price'] ?? 0) ?></div>
                    <div style="display:flex;align-items:center;gap:0">
                        <button class="qty-btn" onclick="Lvs_updateQty(<?= $Lvs_item['cart_id'] ?>, <?= $Lvs_item['id'] ?>, -1)">−</button>
                        <input type="number" class="qty-input" id="Lvs_qty-<?= $Lvs_item['cart_id'] ?>"
                               value="<?= $Lvs_item['quantity'] ?>" min="1" style="width:48px"
                               onchange="Lvs_updateQtyDirect(<?= $Lvs_item['cart_id'] ?>, <?= $Lvs_item['id'] ?>, this.value)">
                        <button class="qty-btn" onclick="Lvs_updateQty(<?= $Lvs_item['cart_id'] ?>, <?= $Lvs_item['id'] ?>, 1)">+</button>
                    </div>
                    <div class="cart-subtotal" id="Lvs_sub-<?= $Lvs_item['cart_id'] ?>"><?= Lvs_formatPrice($Lvs_subtotal) ?></div>
                    <button class="cart-remove" onclick="Lvs_removeItem(<?= $Lvs_item['cart_id'] ?>, <?= $Lvs_item['id'] ?>)" title="Xóa">✕</button>
                </div>
                <?php endforeach; ?>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:16px;flex-wrap:wrap;gap:12px">
                <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php" class="btn-hero-secondary" style="font-size:.85rem">← Tiếp tục mua sắm</a>
                <button onclick="Lvs_clearCart()" class="btn-hero-secondary" style="font-size:.85rem;background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.2);color:#f87171">🗑 Xóa toàn bộ</button>
            </div>
        </div>

        <div class="cart-summary">
            <div class="summary-title">📋 Tóm tắt đơn hàng</div>
            <div class="summary-row">
                <span class="summary-label">Tạm tính (<?= count($Lvs_items) ?> SP)</span>
                <span id="Lvs_cartSubtotal"><?= Lvs_formatPrice($Lvs_total) ?></span>
            </div>
            <div class="summary-row" id="Lvs_discountRow" style="display:none">
                <span class="summary-label" style="color:#4ade80">🎟 Giảm giá</span>
                <span id="Lvs_discountVal" style="color:#4ade80">-0 ₫</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Phí vận chuyển</span>
                <span id="Lvs_shippingVal"><?= $Lvs_total >= 500000 ? '<span style="color:#4ade80">Miễn phí</span>' : Lvs_formatPrice(30000) ?></span>
            </div>
            <div class="voucher-input-row">
                <input type="text" class="voucher-input" id="Lvs_voucherCode" placeholder="Nhập mã giảm giá...">
                <button class="btn-apply-voucher" onclick="Lvs_applyVoucher()">Áp dụng</button>
            </div>
            <div id="Lvs_voucherMsg" style="font-size:.78rem;margin-top:-4px;margin-bottom:8px"></div>
            <div class="summary-row" style="border-bottom:none;margin-top:8px">
                <span style="font-weight:700">Tổng cộng</span>
                <span class="summary-total" id="Lvs_cartTotal"><?= Lvs_formatPrice($Lvs_total) ?></span>
            </div>
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_checkout.php" class="btn-checkout" id="Lvs_btnCheckout">💳 Tiến hành thanh toán</a>
            <div style="margin-top:14px;display:flex;gap:8px;justify-content:center;flex-wrap:wrap">
                <span style="font-size:.72rem;color:var(--text-dim)">🔒 Thanh toán bảo mật</span>
                <span style="font-size:.72rem;color:var(--text-dim)">|</span>
                <span style="font-size:.72rem;color:var(--text-dim)">🚚 Giao hàng toàn quốc</span>
            </div>
        </div>
    </div>
<?php endif; ?>
</div>

<script>
const Lvs_BASE = '<?= BASE_URL ?>';
let Lvs_discount = 0;

function Lvs_updateQty(Lvs_cartId, Lvs_productId, Lvs_delta) {
    const Lvs_input = document.getElementById('Lvs_qty-' + Lvs_cartId);
    let Lvs_val = parseInt(Lvs_input.value) + Lvs_delta;
    if (Lvs_val < 1) { Lvs_removeItem(Lvs_cartId, Lvs_productId); return; }
    Lvs_input.value = Lvs_val;
    Lvs_sendUpdateQty(Lvs_cartId, Lvs_productId, Lvs_val);
}
function Lvs_updateQtyDirect(Lvs_cartId, Lvs_productId, Lvs_val) {
    Lvs_val = parseInt(Lvs_val);
    if (Lvs_val < 1) { Lvs_removeItem(Lvs_cartId, Lvs_productId); return; }
    Lvs_sendUpdateQty(Lvs_cartId, Lvs_productId, Lvs_val);
}
function Lvs_sendUpdateQty(Lvs_cartId, Lvs_productId, Lvs_qty) {
    fetch(Lvs_BASE + '/Lvs_api_actions/Lvs_cart_update.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({cart_id: Lvs_cartId, product_id: Lvs_productId, quantity: Lvs_qty})
    }).then(r=>r.json()).then(d=>{ if(d.status==='success') location.reload(); });
}
function Lvs_removeItem(Lvs_cartId, Lvs_productId) {
    fetch(Lvs_BASE + '/Lvs_api_actions/Lvs_cart_remove.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({cart_id: Lvs_cartId, product_id: Lvs_productId})
    }).then(r=>r.json()).then(d=>{
        if(d.status==='success') { document.getElementById('Lvs_row-'+Lvs_cartId)?.remove(); location.reload(); }
    });
}
function Lvs_clearCart() {
    if (!confirm('Xóa toàn bộ giỏ hàng?')) return;
    fetch(Lvs_BASE + '/Lvs_api_actions/Lvs_cart_clear.php', {method:'POST'})
    .then(r=>r.json()).then(d=>{ if(d.status==='success') location.reload(); });
}
function Lvs_applyVoucher() {
    const Lvs_code = document.getElementById('Lvs_voucherCode').value.trim();
    const Lvs_msg  = document.getElementById('Lvs_voucherMsg');
    if (!Lvs_code) return;
    fetch(Lvs_BASE + '/Lvs_api_actions/Lvs_voucher_check.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({code: Lvs_code})
    }).then(r=>r.json()).then(d=>{
        if(d.status==='success'){
            Lvs_discount = d.discount;
            Lvs_msg.style.color='#4ade80';
            Lvs_msg.textContent='✓ Áp dụng thành công! Giảm ' + new Intl.NumberFormat('vi-VN').format(d.discount) + ' ₫';
            document.getElementById('Lvs_discountRow').style.display='flex';
            document.getElementById('Lvs_discountVal').textContent='-'+new Intl.NumberFormat('vi-VN').format(d.discount)+' ₫';
        } else {
            Lvs_msg.style.color='#f87171';
            Lvs_msg.textContent='✕ '+(d.message||'Mã không hợp lệ');
        }
    });
}
</script>
<?php require_once __DIR__ . '/includes/Lvs_footer.php'; ?>
