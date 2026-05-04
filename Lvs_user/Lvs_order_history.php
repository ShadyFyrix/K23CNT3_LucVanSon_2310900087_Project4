<?php
/**
 * Lvs_user/Lvs_order_history.php — Lịch sử đơn hàng
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 * Backend: GET /api/users/{user_id}/orders
 */
$pageTitle = 'Đơn hàng của tôi — UmaCT Shop';
$activeNav = '';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../utils/Lvs_format_helper.php';
require_once __DIR__ . '/../models/Lvs_order_model.php';

Lvs_requireLogin();
$Lvs_user   = Lvs_getCurrentUser();
$Lvs_orders = Lvs_getOrdersByUser($Lvs_user['user_id']);

require_once __DIR__ . '/../Lvs_pages/includes/Lvs_header.php';
?>
<div class="container section"><div class="user-layout">
    <!-- Sidebar inline -->
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

    <div class="user-main-card">
        <div class="user-card-title">📦 Đơn hàng của tôi (<?= count($Lvs_orders) ?>)</div>
        <?= Lvs_renderFlash() ?>

        <?php if (empty($Lvs_orders)): ?>
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <div class="empty-title">Bạn chưa có đơn hàng nào</div>
                <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php" class="btn-hero-primary" style="display:inline-flex;margin-top:20px">🛍 Mua sắm ngay</a>
            </div>
        <?php else: ?>
            <div style="display:flex;flex-direction:column;gap:14px">
                <?php foreach ($Lvs_orders as $Lvs_order): ?>
                <div style="background:var(--bg-surface);border:1px solid var(--border);border-radius:12px;padding:18px 20px" id="Lvs_order-<?= $Lvs_order['id'] ?>">
                    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:10px">
                        <div>
                            <span style="font-family:'Space Grotesk',sans-serif;font-weight:700">#UMA<?= str_pad($Lvs_order['id'], 6, '0', STR_PAD_LEFT) ?></span>
                            <span style="font-size:.75rem;color:var(--text-dim);margin-left:10px"><?= Lvs_formatDate($Lvs_order['created_at']) ?></span>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px">
                            <?= Lvs_orderStatusBadge($Lvs_order['status']) ?>
                            <?php if (in_array($Lvs_order['status'], ['PENDING', 'CONFIRMED'])): ?>
                                <button onclick="Lvs_cancelOrder(<?= $Lvs_order['id'] ?>, this)"
                                        style="font-size:.75rem;padding:4px 12px;border-radius:8px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:#f87171;cursor:pointer">
                                    Hủy đơn
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.875rem;color:var(--text-muted)">
                        <span>📍 <?= htmlspecialchars($Lvs_order['shipping_address'] ?? '—') ?></span>
                        <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:var(--accent);font-size:1rem"><?= Lvs_formatPrice($Lvs_order['total_price']) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div></div>

<script>
function Lvs_cancelOrder(Lvs_orderId, Lvs_btn) {
    if (!confirm('Bạn có chắc muốn hủy đơn hàng #' + Lvs_orderId + '?')) return;
    Lvs_btn.disabled = true; Lvs_btn.textContent = '⏳';
    fetch('<?= BASE_URL ?>/Lvs_api_actions/Lvs_order_cancel.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({order_id: Lvs_orderId})
    }).then(r=>r.json()).then(d=>{
        if(d.status==='success') location.reload();
        else { alert(d.detail || 'Không thể hủy đơn.'); Lvs_btn.disabled=false; Lvs_btn.textContent='Hủy đơn'; }
    });
}
</script>
<?php require_once __DIR__ . '/../Lvs_pages/includes/Lvs_footer.php'; ?>
