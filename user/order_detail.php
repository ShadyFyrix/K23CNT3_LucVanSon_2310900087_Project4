<?php
/** user/order_detail.php — Chi tiết 1 đơn hàng của user */
$pageTitle = 'Chi tiết đơn hàng';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../utils/format_helper.php';
require_once __DIR__ . '/../models/order_model.php';

requireLogin();
$id     = (int)($_GET['id'] ?? 0);
$detail = $id ? getOrderDetail($id) : null;

if (!$detail) {
    setFlash('error', 'Không tìm thấy đơn hàng.');
    header('Location: ' . BASE_URL . '/user/order_history.php');
    exit;
}
$info  = $detail['order_info'] ?? [];
$items = $detail['items'] ?? [];
$pageTitle = "Đơn hàng #{$id} — UmaCT Shop";

require_once __DIR__ . '/../pages/includes/header.php';
?>
<div class="container section">
    <div class="user-layout">
        <?php include __DIR__ . '/includes/user_sidebar.php'; ?>
        <div>
            <!-- Back -->
            <a href="<?= BASE_URL ?>/user/order_history.php"
               style="display:inline-flex; align-items:center; gap:6px; color:var(--text-muted); font-size:.875rem; margin-bottom:20px; transition:color .15s"
               onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-muted)'">
                ← Quay lại đơn hàng
            </a>

            <!-- Order Header Card -->
            <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); padding:24px; margin-bottom:18px">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:14px">
                    <div>
                        <div style="font-family:'Space Grotesk',sans-serif; font-size:1.2rem; font-weight:800; color:var(--accent)">
                            Đơn hàng #<?= $id ?>
                        </div>
                        <div style="font-size:.8rem; color:var(--text-dim); margin-top:4px">
                            Đặt lúc <?= formatDate($info['created_at'] ?? '') ?>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:12px">
                        <?= orderStatusBadge($info['status'] ?? 'PENDING') ?>
                        <?php if (($info['status'] ?? '') === 'PENDING'): ?>
                            <form method="POST" action="<?= BASE_URL ?>/api_actions/order_cancel.php" style="margin:0">
                                <input type="hidden" name="order_id" value="<?= $id ?>">
                                <button type="submit"
                                        style="padding:5px 14px; background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.25); border-radius:8px; color:#f87171; font-size:.8rem; cursor:pointer"
                                        onclick="return confirm('Hủy đơn này?')">✕ Hủy đơn</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Info Grid -->
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:16px; margin-top:20px; padding-top:20px; border-top:1px solid var(--border)">
                    <?php
                    $meta = [
                        '📍 Địa chỉ giao'   => $info['shipping_address'] ?? '—',
                        '💳 Thanh toán'      => paymentMethodName($info['payment_method'] ?? 1),
                        '🎟 Mã giảm giá'     => $info['voucher_id'] ? 'Đã áp dụng' : 'Không có',
                        '💰 Tổng tiền'       => formatPrice($info['total_price'] ?? 0),
                    ];
                    foreach ($meta as $label => $val):
                    ?>
                    <div style="background:var(--bg-glass); border-radius:10px; padding:14px">
                        <div style="font-size:.72rem; font-weight:700; color:var(--text-dim); text-transform:uppercase; letter-spacing:.06em; margin-bottom:5px"><?= $label ?></div>
                        <div style="font-size:.875rem; font-weight:600; color:var(--text)"><?= htmlspecialchars($val) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Order Items -->
            <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); overflow:hidden">
                <div style="padding:16px 20px; border-bottom:1px solid var(--border); font-family:'Space Grotesk',sans-serif; font-weight:700">
                    📦 Sản phẩm (<?= count($items) ?>)
                </div>
                <?php foreach ($items as $item): ?>
                <div style="display:flex; align-items:center; gap:14px; padding:16px 20px; border-bottom:1px solid var(--border)">
                    <div style="width:56px; height:56px; border-radius:10px; overflow:hidden; background:var(--bg-surface); flex-shrink:0; border:1px solid var(--border)">
                        <img src="<?= !empty($item['image_url']) ? htmlspecialchars($item['image_url']) : BASE_URL . '/assets/images/no-image.png' ?>"
                             alt="" style="width:100%;height:100%;object-fit:cover">
                    </div>
                    <div style="flex:1">
                        <div style="font-size:.9rem; font-weight:600"><?= htmlspecialchars($item['product_name'] ?? 'Sản phẩm') ?></div>
                        <div style="font-size:.78rem; color:var(--text-muted); margin-top:2px">
                            <?= formatPrice($item['price_at_purchase'] ?? 0) ?> × <?= $item['quantity'] ?>
                        </div>
                    </div>
                    <div style="font-family:'Space Grotesk',sans-serif; font-weight:700; color:var(--accent)">
                        <?= formatPrice(($item['price_at_purchase'] ?? 0) * ($item['quantity'] ?? 1)) ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Total row -->
                <div style="display:flex; justify-content:flex-end; padding:16px 20px; gap:14px; align-items:center">
                    <span style="color:var(--text-muted); font-size:.875rem">Tổng cộng:</span>
                    <span style="font-family:'Space Grotesk',sans-serif; font-size:1.2rem; font-weight:800; color:var(--accent)">
                        <?= formatPrice($info['total_price'] ?? 0) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../pages/includes/footer.php'; ?>
