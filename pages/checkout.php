<?php
/**
 * pages/checkout.php — Trang thanh toán
 */
$pageTitle = 'Thanh toán — UmaCT Shop';
$activeNav = 'shop';

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../utils/format_helper.php';
require_once __DIR__ . '/../models/cart_model.php';
require_once __DIR__ . '/../models/order_model.php';
require_once __DIR__ . '/../models/voucher_model.php';

requireLogin();
$currentUser = getCurrentUser();
$cartItems   = getCart($currentUser['user_id']);

if (empty($cartItems)) {
    setFlash('warning', 'Giỏ hàng của bạn đang trống.');
    header('Location: ' . BASE_URL . '/pages/cart.php');
    exit;
}

$subtotal = calcCartTotal($cartItems);
$shipping = $subtotal >= 500000 ? 0 : 30000;
$discount = 0;
$voucherId = null;
$error = '';

// Xử lý đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $address = trim($_POST['shipping_address'] ?? '');
    $payment = (int)($_POST['payment_method'] ?? 1);
    $voucherCode = trim($_POST['voucher_code'] ?? '');

    if (empty($address)) {
        $error = 'Vui lòng nhập địa chỉ giao hàng.';
    } else {
        // Kiểm tra voucher (nếu có)
        if ($voucherCode) {
            $vRes = checkVoucher($voucherCode, $subtotal);
            if ($vRes && !empty($vRes['is_valid'])) {
                $discount  = $vRes['discount_amount'];
                $voucherId = $vRes['voucher_id'];
            }
        }

        $orderData = [
            'user_id'          => $currentUser['user_id'],
            'voucher_code'     => $voucherCode ?: null,
            'shipping_address' => $address,
            'payment_method'   => $payment,
            'items'            => array_map(fn($i) => [
                'product_id' => $i['product_id'],
                'quantity'   => $i['quantity'],
            ], $cartItems),
        ];

        $res = createOrder($orderData);
        if (ApiClient::isSuccess($res)) {
            clearCart($currentUser['user_id']);
            $orderId = $res['data']['order_id'] ?? '';
            setFlash('success', '🎉 Đặt hàng thành công! Mã đơn: #' . $orderId);
            header('Location: ' . BASE_URL . '/user/order_history.php');
            exit;
        } else {
            $error = ApiClient::getError($res);
        }
    }
}

$finalTotal = $subtotal + $shipping - $discount;
require_once __DIR__ . '/includes/header.php';
?>

<div style="background:var(--bg-surface); border-bottom:1px solid var(--border); padding:24px 0">
    <div class="container">
        <!-- Progress Steps -->
        <div style="display:flex; align-items:center; gap:0; justify-content:center; flex-wrap:wrap; gap:8px">
            <?php
            $steps = ['🛒 Giỏ hàng', '📋 Thông tin', '✅ Xác nhận'];
            foreach ($steps as $i => $step):
                $isActive = $i === 1;
                $isDone   = $i === 0;
            ?>
            <div style="display:flex; align-items:center; gap:8px">
                <span style="padding:6px 16px; border-radius:99px; font-size:.8rem; font-weight:600;
                    background:<?= $isDone ? 'rgba(34,197,94,.15)' : ($isActive ? 'var(--accent)' : 'var(--bg-card)') ?>;
                    color:<?= $isDone ? '#4ade80' : ($isActive ? '#fff' : 'var(--text-dim)') ?>;
                    border:1px solid <?= $isDone ? 'rgba(34,197,94,.3)' : ($isActive ? 'var(--accent)' : 'var(--border)') ?>">
                    <?= $step ?>
                </span>
                <?php if ($i < count($steps) - 1): ?>
                    <span style="color:var(--text-dim)">→</span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="container section">

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" id="checkoutForm">
        <div style="display:grid; grid-template-columns:1fr 380px; gap:28px; align-items:flex-start">

            <!-- Left: Customer Info -->
            <div>
                <!-- Delivery Info -->
                <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); padding:28px; margin-bottom:20px">
                    <h2 style="font-family:'Space Grotesk',sans-serif; font-size:1.05rem; font-weight:700; margin-bottom:20px">
                        📍 Thông tin giao hàng
                    </h2>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px">
                        <div>
                            <label style="font-size:.82rem; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px">Họ và tên</label>
                            <input type="text" readonly
                                   value="<?= htmlspecialchars($currentUser['full_name']) ?>"
                                   style="width:100%; background:var(--bg-glass); border:1px solid var(--border); border-radius:9px; padding:10px 14px; color:var(--text); font-size:.875rem; opacity:.7">
                        </div>
                        <div>
                            <label style="font-size:.82rem; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px">Tên đăng nhập</label>
                            <input type="text" readonly
                                   value="<?= htmlspecialchars($currentUser['username']) ?>"
                                   style="width:100%; background:var(--bg-glass); border:1px solid var(--border); border-radius:9px; padding:10px 14px; color:var(--text); font-size:.875rem; opacity:.7">
                        </div>
                    </div>

                    <div>
                        <label for="shipping_address" style="font-size:.82rem; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px">
                            Địa chỉ giao hàng <span style="color:var(--red)">*</span>
                        </label>
                        <textarea name="shipping_address" id="shipping_address" rows="3" required
                                  placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố..."
                                  style="width:100%; background:var(--bg-glass); border:1px solid var(--border); border-radius:10px; padding:12px 14px; color:var(--text); font-size:.875rem; outline:none; resize:vertical; font-family:inherit; transition:border-color .15s"
                                  onfocus="this.style.borderColor='var(--accent)'"
                                  onblur="this.style.borderColor='var(--border)'"><?= htmlspecialchars($_POST['shipping_address'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Payment Method -->
                <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); padding:28px; margin-bottom:20px">
                    <h2 style="font-family:'Space Grotesk',sans-serif; font-size:1.05rem; font-weight:700; margin-bottom:20px">
                        💳 Phương thức thanh toán
                    </h2>

                    <?php
                    $payments = [
                        1 => ['icon' => '💵', 'name' => 'COD — Thanh toán khi nhận hàng', 'desc' => 'Trả tiền mặt khi nhận được hàng'],
                        2 => ['icon' => '💜', 'name' => 'MoMo',                            'desc' => 'Thanh toán qua ví MoMo'],
                        3 => ['icon' => '🔵', 'name' => 'PayOS',                           'desc' => 'Thanh toán qua PayOS — hỗ trợ ATM, QR'],
                        4 => ['icon' => '🔴', 'name' => 'VNPay',                           'desc' => 'Thanh toán qua VNPay'],
                    ];
                    foreach ($payments as $val => $pm):
                    ?>
                    <label style="display:flex; align-items:center; gap:14px; padding:14px 16px; border-radius:10px; border:1.5px solid var(--border); margin-bottom:10px; cursor:pointer; transition:all .15s"
                           id="pm-label-<?= $val ?>"
                           onclick="selectPayment(<?= $val ?>)">
                        <input type="radio" name="payment_method" value="<?= $val ?>"
                               <?= ($val === 1) ? 'checked' : '' ?>
                               style="accent-color:var(--accent); width:16px; height:16px"
                               onchange="selectPayment(<?= $val ?>)">
                        <span style="font-size:1.4rem"><?= $pm['icon'] ?></span>
                        <div style="flex:1">
                            <div style="font-size:.875rem; font-weight:600"><?= $pm['name'] ?></div>
                            <div style="font-size:.75rem; color:var(--text-muted)"><?= $pm['desc'] ?></div>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>

                <!-- Order Items Preview -->
                <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); padding:24px">
                    <h2 style="font-family:'Space Grotesk',sans-serif; font-size:1.05rem; font-weight:700; margin-bottom:16px">
                        📦 Sản phẩm đặt hàng (<?= count($cartItems) ?>)
                    </h2>
                    <?php foreach ($cartItems as $item): ?>
                    <div style="display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid var(--border)">
                        <div style="width:48px; height:48px; border-radius:8px; overflow:hidden; background:var(--bg-surface); flex-shrink:0; border:1px solid var(--border)">
                            <img src="<?= !empty($item['image_url']) ? htmlspecialchars($item['image_url']) : BASE_URL . '/assets/images/no-image.png' ?>"
                                 alt="" style="width:100%; height:100%; object-fit:cover">
                        </div>
                        <div style="flex:1">
                            <div style="font-size:.875rem; font-weight:600"><?= htmlspecialchars($item['product_name']) ?></div>
                            <div style="font-size:.75rem; color:var(--text-muted)">x<?= $item['quantity'] ?></div>
                        </div>
                        <div style="font-family:'Space Grotesk',sans-serif; font-weight:700; color:var(--accent)">
                            <?= formatPrice($item['subtotal']) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="cart-summary" style="position:sticky; top:calc(var(--nav-h) + 16px)">
                <div class="summary-title">📋 Tóm tắt đơn hàng</div>

                <div class="summary-row">
                    <span class="summary-label">Tạm tính</span>
                    <span><?= formatPrice($subtotal) ?></span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Phí vận chuyển</span>
                    <span><?= $shipping === 0 ? '<span style="color:#4ade80">Miễn phí</span>' : formatPrice($shipping) ?></span>
                </div>
                <?php if ($discount > 0): ?>
                <div class="summary-row">
                    <span class="summary-label" style="color:#4ade80">Giảm giá</span>
                    <span style="color:#4ade80">-<?= formatPrice($discount) ?></span>
                </div>
                <?php endif; ?>

                <!-- Voucher Input -->
                <div class="voucher-input-row" style="margin-top:12px">
                    <input type="text" name="voucher_code" class="voucher-input"
                           id="voucherInput"
                           placeholder="Mã giảm giá..."
                           value="<?= htmlspecialchars($_POST['voucher_code'] ?? '') ?>">
                    <button type="button" class="btn-apply-voucher" onclick="checkVoucherUI()">Kiểm tra</button>
                </div>
                <div id="voucherMsg" style="font-size:.75rem; min-height:16px; margin-bottom:4px"></div>

                <div class="summary-row" style="border:none; padding-top:12px; border-top:1px solid var(--border); margin-top:8px">
                    <span style="font-weight:800; font-size:1rem">Tổng cộng</span>
                    <span class="summary-total"><?= formatPrice($finalTotal) ?></span>
                </div>

                <button type="submit" name="place_order" class="btn-checkout" id="btnPlaceOrder">
                    ✅ Xác nhận đặt hàng
                </button>

                <div style="margin-top:14px; text-align:center">
                    <div style="font-size:.72rem; color:var(--text-dim); line-height:1.8">
                        🔒 Thông tin được mã hóa & bảo mật<br>
                        Bằng cách đặt hàng, bạn đồng ý với <a href="#" style="color:var(--accent)">điều khoản dịch vụ</a>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function selectPayment(val) {
    document.querySelectorAll('[id^="pm-label-"]').forEach(el => {
        el.style.borderColor = 'var(--border)';
        el.style.background = 'transparent';
    });
    const el = document.getElementById('pm-label-' + val);
    if (el) {
        el.style.borderColor = 'var(--accent)';
        el.style.background = 'rgba(139,92,246,.07)';
    }
    document.querySelector(`input[name="payment_method"][value="${val}"]`).checked = true;
}
selectPayment(1); // default

function checkVoucherUI() {
    const code = document.getElementById('voucherInput').value.trim();
    const msg  = document.getElementById('voucherMsg');
    if (!code) return;
    msg.textContent = '⏳ Đang kiểm tra...';
    msg.style.color = 'var(--text-muted)';
    fetch('<?= BASE_URL ?>/api_actions/voucher_check.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ code: code, order_value: <?= $subtotal ?> })
    })
    .then(r => r.json())
    .then(d => {
        if (d.status === 'success') {
            msg.textContent = '✓ Hợp lệ! Giảm ' + new Intl.NumberFormat('vi-VN').format(d.discount) + ' ₫';
            msg.style.color = '#4ade80';
        } else {
            msg.textContent = '✕ ' + (d.message || 'Mã không hợp lệ');
            msg.style.color = '#f87171';
        }
    });
}

document.getElementById('checkoutForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnPlaceOrder');
    btn.textContent = '⏳ Đang xử lý...';
    btn.disabled = true;
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
