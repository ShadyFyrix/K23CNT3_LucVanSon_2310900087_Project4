<?php
/**
 * Lvs_user/Lvs_order_detail.php — Chi tiết 1 đơn hàng của user
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
$pageTitle = 'Chi tiết đơn hàng';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../utils/Lvs_format_helper.php';
require_once __DIR__ . '/../models/Lvs_order_model.php';

Lvs_requireLogin();
$Lvs_user = Lvs_getCurrentUser();
$Lvs_id   = (int)($_GET['id'] ?? 0);
$Lvs_detail = $Lvs_id ? Lvs_getOrderDetail($Lvs_id) : null;

if (!$Lvs_detail) {
    Lvs_setFlash('error', 'Không tìm thấy đơn hàng.');
    header('Location: ' . BASE_URL . '/Lvs_user/Lvs_order_history.php'); exit;
}

// Kiểm tra ownership
$Lvs_info  = $Lvs_detail['order_info'] ?? $Lvs_detail;
$Lvs_items = $Lvs_detail['items'] ?? [];
if (($Lvs_info['user_id'] ?? 0) != $Lvs_user['user_id']) {
    Lvs_setFlash('error', 'Bạn không có quyền xem đơn hàng này.');
    header('Location: ' . BASE_URL . '/Lvs_user/Lvs_order_history.php'); exit;
}
$pageTitle = "Đơn hàng #$Lvs_id — UmaCT Shop";

require_once __DIR__ . '/../Lvs_pages/includes/Lvs_header.php';
?>
<div class="container section"><div class="user-layout">
    <aside class="user-sidebar">
        <div class="user-info">
            <div style="width:64px;height:64px;border-radius:50%;background:var(--gradient-btn);display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:700;color:#fff;margin:0 auto 12px">
                <?= strtoupper(substr($Lvs_user['full_name'] ?? 'U', 0, 1)) ?>
            </div>
            <div style="font-weight:700;text-align:center"><?= htmlspecialchars($Lvs_user['full_name']) ?></div>
        </div>
        <nav class="user-nav">
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_profile.php" class="user-nav-link">👤 Hồ sơ</a>
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_order_history.php" class="user-nav-link active">📦 Đơn hàng</a>
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_favorites.php" class="user-nav-link">❤️ Yêu thích</a>
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_change_password.php" class="user-nav-link">🔐 Đổi mật khẩu</a>
            <a href="<?= BASE_URL ?>/auth/Lvs_logout.php" class="user-nav-link" style="color:#f87171">🚪 Đăng xuất</a>
        </nav>
    </aside>

    <div>
        <a href="<?= BASE_URL ?>/Lvs_user/Lvs_order_history.php"
           style="display:inline-flex;align-items:center;gap:6px;color:var(--text-muted);font-size:.875rem;margin-bottom:20px;transition:color .15s"
           onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-muted)'">
           ← Quay lại đơn hàng
        </a>

        <!-- Order Header -->
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;margin-bottom:18px">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:14px">
                <div>
                    <div style="font-family:'Space Grotesk',sans-serif;font-size:1.2rem;font-weight:800;color:var(--accent)">Đơn hàng #<?= $Lvs_id ?></div>
                    <div style="font-size:.8rem;color:var(--text-dim);margin-top:4px">Đặt lúc <?= Lvs_formatDate($Lvs_info['created_at'] ?? '') ?></div>
                </div>
                <div style="display:flex;align-items:center;gap:12px">
                    <?= Lvs_orderStatusBadge($Lvs_info['status'] ?? 'PENDING') ?>
                    <?php if (in_array($Lvs_info['status'] ?? '', ['PENDING', 'CONFIRMED'])): ?>
                    <button onclick="Lvs_cancelOrderDetail(<?= $Lvs_id ?>)"
                            style="padding:5px 14px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:8px;color:#f87171;font-size:.8rem;cursor:pointer">✕ Hủy đơn</button>
                    <?php endif; ?>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-top:20px;padding-top:20px;border-top:1px solid var(--border)">
                <?php
                $Lvs_meta = [
                    '📍 Địa chỉ giao' => $Lvs_info['shipping_address'] ?? '—',
                    '💰 Tổng tiền'    => Lvs_formatPrice($Lvs_info['total_price'] ?? 0),
                ];
                foreach ($Lvs_meta as $Lvs_label => $Lvs_val):
                ?>
                <div style="background:var(--bg-glass);border-radius:10px;padding:14px">
                    <div style="font-size:.72rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:.06em;margin-bottom:5px"><?= $Lvs_label ?></div>
                    <div style="font-size:.875rem;font-weight:600;color:var(--text)"><?= htmlspecialchars((string)$Lvs_val) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Order Items -->
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border);font-family:'Space Grotesk',sans-serif;font-weight:700">
                📦 Sản phẩm (<?= count($Lvs_items) ?>)
            </div>
            <?php foreach ($Lvs_items as $Lvs_item):
                $Lvs_img = !empty($Lvs_item['image_url']) ? $Lvs_item['image_url'] : BASE_URL.'/assets/images/no-image.png';
                $Lvs_sub = ($Lvs_item['price_at_purchase'] ?? 0) * ($Lvs_item['quantity'] ?? 1);
            ?>
            <div style="display:flex;align-items:center;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border)">
                <div style="width:56px;height:56px;border-radius:10px;overflow:hidden;background:var(--bg-surface);flex-shrink:0;border:1px solid var(--border)">
                    <img src="<?= htmlspecialchars($Lvs_img) ?>" alt="" style="width:100%;height:100%;object-fit:cover"
                         onerror="this.src='<?= BASE_URL ?>/assets/images/no-image.png'">
                </div>
                <div style="flex:1">
                    <div style="font-size:.9rem;font-weight:600"><?= htmlspecialchars($Lvs_item['product_name'] ?? 'Sản phẩm') ?></div>
                    <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px"><?= Lvs_formatPrice($Lvs_item['price_at_purchase'] ?? 0) ?> × <?= $Lvs_item['quantity'] ?></div>
                </div>
                <div style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:var(--accent)"><?= Lvs_formatPrice($Lvs_sub) ?></div>
            </div>
            <?php endforeach; ?>
            <div style="display:flex;justify-content:flex-end;padding:16px 20px;gap:14px;align-items:center">
                <span style="color:var(--text-muted);font-size:.875rem">Tổng cộng:</span>
                <span style="font-family:'Space Grotesk',sans-serif;font-size:1.2rem;font-weight:800;color:var(--accent)"><?= Lvs_formatPrice($Lvs_info['total_price'] ?? 0) ?></span>
            </div>
        </div>
    </div>
</div></div>

<script>
function Lvs_cancelOrderDetail(Lvs_id) {
    if (!confirm('Hủy đơn hàng #' + Lvs_id + '?')) return;
    fetch('<?= BASE_URL ?>/Lvs_api_actions/Lvs_order_cancel.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({order_id: Lvs_id})
    }).then(r=>r.json()).then(d=>{
        if(d.status==='success') location.reload();
        else alert(d.detail || 'Không thể hủy đơn.');
    });
}
</script>
<?php require_once __DIR__ . '/../Lvs_pages/includes/Lvs_footer.php'; ?>
