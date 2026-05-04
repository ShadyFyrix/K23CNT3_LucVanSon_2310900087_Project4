<?php
/**
 * Lvs_pages/Lvs_checkout.php — Thanh toán
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 * Backend: POST /api/orders — {user_id, total_price, shipping_address, payment_method, items[{id,quantity,price}]}
 */
$pageTitle = 'Thanh toán — UmaCT Shop';
$activeNav = 'shop';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../utils/Lvs_format_helper.php';
require_once __DIR__ . '/../models/Lvs_cart_model.php';
require_once __DIR__ . '/../models/Lvs_order_model.php';
require_once __DIR__ . '/../models/Lvs_voucher_model.php';
require_once __DIR__ . '/../utils/api_client.php'; // isSuccess/getError

Lvs_requireLogin();
$Lvs_user      = Lvs_getCurrentUser();
$Lvs_cartItems = Lvs_getCart($Lvs_user['user_id']);

if (empty($Lvs_cartItems)) {
    Lvs_setFlash('warning', 'Giỏ hàng của bạn đang trống.');
    header('Location: ' . BASE_URL . '/Lvs_pages/Lvs_cart.php'); exit;
}

$Lvs_subtotal = Lvs_calcCartTotal($Lvs_cartItems);
$Lvs_shipping = $Lvs_subtotal >= 500000 ? 0 : 30000;
$Lvs_discount = 0;
$Lvs_error    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Lvs_do_checkout'])) {
    $Lvs_address = trim($_POST['shipping_address'] ?? '');
    $Lvs_payment = (int)($_POST['payment_method'] ?? 1);
    $Lvs_vouCode = trim($_POST['voucher_code'] ?? '');

    if (empty($Lvs_address)) {
        $Lvs_error = 'Vui lòng nhập địa chỉ giao hàng.';
    } else {
        if ($Lvs_vouCode) {
            $Lvs_vRes = Lvs_checkVoucher($Lvs_vouCode, $Lvs_subtotal);
            if ($Lvs_vRes) $Lvs_discount = $Lvs_vRes['discount_value'] ?? 0;
        }
        // Guard: cartItems phải là array hợp lệ
        if (!is_array($Lvs_cartItems) || empty($Lvs_cartItems)) {
            $Lvs_error = 'Giỏ hàng trống hoặc không thể đọc. Vui lòng thử lại.';
        } else {
            $Lvs_orderData = [
                'user_id'          => $Lvs_user['user_id'],
                'total_price'      => $Lvs_subtotal + $Lvs_shipping - $Lvs_discount,
                'shipping_address' => $Lvs_address,
                'payment_method'   => $Lvs_payment,
                'items'            => array_map(fn($i) => [
                    'id'       => $i['id'],
                    'quantity' => (int)$i['quantity'],
                    'price'    => (float)($i['price'] ?? $i['discount_price'] ?? 0),
                ], $Lvs_cartItems),
            ];
            // Debug log — xem request gửi gì
            error_log('[Checkout] OrderData: ' . json_encode($Lvs_orderData));
            $Lvs_res = Lvs_createOrder($Lvs_orderData);
            error_log('[Checkout] API response: ' . json_encode($Lvs_res));
            if (ApiClient::isSuccess($Lvs_res)) {
                Lvs_clearCart($Lvs_user['user_id']);
                $Lvs_orderId = $Lvs_res['data']['order_id'] ?? $Lvs_res['order_id'] ?? '';
                Lvs_setFlash('success', '🎉 Đặt hàng thành công! Mã đơn: #' . $Lvs_orderId);
                header('Location: ' . BASE_URL . '/Lvs_user/Lvs_order_history.php'); exit;
            } else {
                // Normalize error từ detail hoặc message
                $Lvs_error = $Lvs_res['detail'] ?? $Lvs_res['message'] ?? 'Lỗi không xác định từ server.';
            }
        }
    }
}
$Lvs_finalTotal = $Lvs_subtotal + $Lvs_shipping - $Lvs_discount;
require_once __DIR__ . '/includes/Lvs_header.php';
?>

<div style="background:var(--bg-surface);border-bottom:1px solid var(--border);padding:24px 0">
    <div class="container">
        <div style="display:flex;align-items:center;gap:0;justify-content:center;flex-wrap:wrap;gap:8px">
            <?php
            $Lvs_steps = ['🛒 Giỏ hàng', '📋 Thông tin', '✅ Xác nhận'];
            foreach ($Lvs_steps as $Lvs_i => $Lvs_step):
                $Lvs_active = $Lvs_i === 1; $Lvs_done = $Lvs_i === 0;
            ?>
            <div style="display:flex;align-items:center;gap:8px">
                <span style="padding:6px 16px;border-radius:99px;font-size:.8rem;font-weight:600;
                    background:<?= $Lvs_done ? 'rgba(34,197,94,.15)' : ($Lvs_active ? 'var(--accent)' : 'var(--bg-card)') ?>;
                    color:<?= $Lvs_done ? '#4ade80' : ($Lvs_active ? '#fff' : 'var(--text-dim)') ?>;
                    border:1px solid <?= $Lvs_done ? 'rgba(34,197,94,.3)' : ($Lvs_active ? 'var(--accent)' : 'var(--border)') ?>">
                    <?= $Lvs_step ?>
                </span>
                <?php if ($Lvs_i < count($Lvs_steps) - 1): ?><span style="color:var(--text-dim)">→</span><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="container section">
    <?php if ($Lvs_error): ?>
        <div style="position:sticky;top:70px;z-index:1000;background:#7f1d1d;border:1px solid #ef4444;color:#fca5a5;padding:14px 20px;border-radius:10px;margin-bottom:20px;font-weight:600;font-size:.9rem">
            ⚠️ <strong>Lỗi đặt hàng:</strong> <?= htmlspecialchars($Lvs_error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" id="Lvs_checkoutForm">
        <div style="display:grid;grid-template-columns:1fr 380px;gap:28px;align-items:flex-start">

            <!-- LEFT: Info -->
            <div>
                <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;margin-bottom:20px">
                    <h2 style="font-family:'Space Grotesk',sans-serif;font-size:1.05rem;font-weight:700;margin-bottom:20px">📍 Thông tin giao hàng</h2>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
                        <div>
                            <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px">Họ và tên</label>
                            <input type="text" readonly value="<?= htmlspecialchars($Lvs_user['full_name']) ?>" style="width:100%;background:var(--bg-glass);border:1px solid var(--border);border-radius:9px;padding:10px 14px;color:var(--text);font-size:.875rem;opacity:.7">
                        </div>
                        <div>
                            <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px">Tên đăng nhập</label>
                            <input type="text" readonly value="<?= htmlspecialchars($Lvs_user['username']) ?>" style="width:100%;background:var(--bg-glass);border:1px solid var(--border);border-radius:9px;padding:10px 14px;color:var(--text);font-size:.875rem;opacity:.7">
                        </div>
                    </div>
                    <div>
                        <label for="Lvs_address" style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px">Địa chỉ giao hàng <span style="color:var(--red)">*</span></label>
                        <textarea name="shipping_address" id="Lvs_address" rows="3" required
                                  placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố..."
                                  style="width:100%;background:var(--bg-glass);border:1px solid var(--border);border-radius:10px;padding:12px 14px;color:var(--text);font-size:.875rem;outline:none;resize:vertical;font-family:inherit;transition:border-color .15s"
                                  onfocus="this.style.borderColor='var(--accent)'"
                                  onblur="this.style.borderColor='var(--border)'"><?= htmlspecialchars($_POST['shipping_address'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Payment -->
                <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;margin-bottom:20px">
                    <h2 style="font-family:'Space Grotesk',sans-serif;font-size:1.05rem;font-weight:700;margin-bottom:20px">💳 Phương thức thanh toán</h2>
                    <?php
                    $Lvs_payments = [
                        1 => ['icon' => '💵', 'name' => 'COD — Thanh toán khi nhận hàng', 'desc' => 'Trả tiền mặt khi nhận được hàng'],
                        2 => ['icon' => '💜', 'name' => 'MoMo',  'desc' => 'Thanh toán qua ví MoMo'],
                        3 => ['icon' => '🔵', 'name' => 'PayOS', 'desc' => 'Thanh toán qua PayOS — hỗ trợ ATM, QR'],
                        4 => ['icon' => '🔴', 'name' => 'VNPay', 'desc' => 'Thanh toán qua VNPay'],
                    ];
                    foreach ($Lvs_payments as $Lvs_val => $Lvs_pm):
                    ?>
                    <label style="display:flex;align-items:center;gap:14px;padding:14px 16px;border-radius:10px;border:1.5px solid var(--border);margin-bottom:10px;cursor:pointer;transition:all .15s"
                           id="Lvs_pm-<?= $Lvs_val ?>" onclick="Lvs_selectPayment(<?= $Lvs_val ?>)">
                        <input type="radio" name="payment_method" value="<?= $Lvs_val ?>" <?= ($Lvs_val === 1) ? 'checked' : '' ?>
                               style="accent-color:var(--accent);width:16px;height:16px" onchange="Lvs_selectPayment(<?= $Lvs_val ?>)">
                        <span style="font-size:1.4rem"><?= $Lvs_pm['icon'] ?></span>
                        <div style="flex:1">
                            <div style="font-size:.875rem;font-weight:600"><?= $Lvs_pm['name'] ?></div>
                            <div style="font-size:.75rem;color:var(--text-muted)"><?= $Lvs_pm['desc'] ?></div>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>

                <!-- Order Preview -->
                <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px">
                    <h2 style="font-family:'Space Grotesk',sans-serif;font-size:1.05rem;font-weight:700;margin-bottom:16px">📦 Sản phẩm đặt hàng (<?= count($Lvs_cartItems) ?>)</h2>
                    <?php foreach ($Lvs_cartItems as $Lvs_item):
                        $Lvs_img = !empty($Lvs_item['main_image']) ? $Lvs_item['main_image'] : BASE_URL.'/assets/images/no-image.png';
                        $Lvs_sub = ($Lvs_item['price'] ?? 0) * ($Lvs_item['quantity'] ?? 1);
                    ?>
                    <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border)">
                        <div style="width:48px;height:48px;border-radius:8px;overflow:hidden;background:var(--bg-surface);flex-shrink:0;border:1px solid var(--border)">
                            <img src="<?= htmlspecialchars($Lvs_img) ?>" alt="" style="width:100%;height:100%;object-fit:cover"
                                 onerror="this.src='<?= BASE_URL ?>/assets/images/no-image.png'">
                        </div>
                        <div style="flex:1">
                            <div style="font-size:.875rem;font-weight:600"><?= htmlspecialchars($Lvs_item['name']) ?></div>
                            <div style="font-size:.75rem;color:var(--text-muted)">x<?= $Lvs_item['quantity'] ?></div>
                        </div>
                        <div style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:var(--accent)"><?= Lvs_formatPrice($Lvs_sub) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- RIGHT: Summary -->
            <div class="cart-summary" style="position:sticky;top:calc(var(--nav-h) + 16px)">
                <div class="summary-title">📋 Tóm tắt đơn hàng</div>
                <div class="summary-row"><span class="summary-label">Tạm tính</span><span><?= Lvs_formatPrice($Lvs_subtotal) ?></span></div>
                <div class="summary-row"><span class="summary-label">Phí vận chuyển</span><span><?= $Lvs_shipping === 0 ? '<span style="color:#4ade80">Miễn phí</span>' : Lvs_formatPrice($Lvs_shipping) ?></span></div>
                <?php if ($Lvs_discount > 0): ?>
                <div class="summary-row"><span class="summary-label" style="color:#4ade80">Giảm giá</span><span style="color:#4ade80">-<?= Lvs_formatPrice($Lvs_discount) ?></span></div>
                <?php endif; ?>

                <div class="voucher-input-row" style="margin-top:12px">
                    <input type="text" name="voucher_code" class="voucher-input" id="Lvs_voucherInput"
                           placeholder="Mã giảm giá..." value="<?= htmlspecialchars($_POST['voucher_code'] ?? '') ?>">
                    <button type="button" class="btn-apply-voucher" onclick="Lvs_checkVoucherUI()">Kiểm tra</button>
                </div>
                <div id="Lvs_voucherMsg" style="font-size:.75rem;min-height:16px;margin-bottom:4px"></div>

                <div class="summary-row" style="border:none;padding-top:12px;border-top:1px solid var(--border);margin-top:8px">
                    <span style="font-weight:800;font-size:1rem">Tổng cộng</span>
                    <span class="summary-total"><?= Lvs_formatPrice($Lvs_finalTotal) ?></span>
                </div>
                <input type="hidden" name="Lvs_do_checkout" value="1">
                <button type="submit" name="Lvs_place_order" class="btn-checkout" id="Lvs_btnPlaceOrder">✅ Xác nhận đặt hàng</button>
                <div style="margin-top:14px;text-align:center;font-size:.72rem;color:var(--text-dim);line-height:1.8">
                    🔒 Thông tin được mã hóa &amp; bảo mật
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function Lvs_selectPayment(Lvs_val) {
    document.querySelectorAll('[id^="Lvs_pm-"]').forEach(el => { el.style.borderColor='var(--border)'; el.style.background='transparent'; });
    const Lvs_el = document.getElementById('Lvs_pm-' + Lvs_val);
    if (Lvs_el) { Lvs_el.style.borderColor='var(--accent)'; Lvs_el.style.background='rgba(139,92,246,.07)'; }
    document.querySelector(`input[name="payment_method"][value="${Lvs_val}"]`).checked = true;
}
Lvs_selectPayment(1);

function Lvs_checkVoucherUI() {
    const Lvs_code = document.getElementById('Lvs_voucherInput').value.trim();
    const Lvs_msg  = document.getElementById('Lvs_voucherMsg');
    if (!Lvs_code) return;
    Lvs_msg.textContent = '⏳ Đang kiểm tra...'; Lvs_msg.style.color = 'var(--text-muted)';
    fetch('<?= BASE_URL ?>/Lvs_api_actions/Lvs_voucher_check.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({code: Lvs_code})
    }).then(r=>r.json()).then(d=>{
        if(d.status==='success'){ Lvs_msg.textContent='✓ Giảm '+new Intl.NumberFormat('vi-VN').format(d.discount)+' ₫'; Lvs_msg.style.color='#4ade80'; }
        else { Lvs_msg.textContent='✕ '+(d.message||'Mã không hợp lệ'); Lvs_msg.style.color='#f87171'; }
    });
}

document.getElementById('Lvs_checkoutForm').addEventListener('submit', function(e) {
    const Lvs_btn = document.getElementById('Lvs_btnPlaceOrder');
    const Lvs_addr = document.getElementById('Lvs_address').value.trim();
    if (!Lvs_addr) {
        e.preventDefault();
        alert('⚠️ Vui lòng nhập địa chỉ giao hàng!');
        document.getElementById('Lvs_address').focus();
        return;
    }
    Lvs_btn.textContent = '⏳ Đang xử lý...'; Lvs_btn.disabled = true;
    // Re-enable nếu PHP trả về (không redirect = có lỗi)
    setTimeout(() => { Lvs_btn.textContent = '✅ Xác nhận đặt hàng'; Lvs_btn.disabled = false; }, 8000);
});
</script>
<?php require_once __DIR__ . '/includes/Lvs_footer.php'; ?>
