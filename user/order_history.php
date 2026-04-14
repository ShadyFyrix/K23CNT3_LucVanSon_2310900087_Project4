<?php
/** user/order_history.php — Lịch sử đơn hàng */
$pageTitle = 'Đơn hàng của tôi — UmaCT Shop';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../utils/format_helper.php';
require_once __DIR__ . '/../models/order_model.php';

requireLogin();
$user   = getCurrentUser();
$orders = getOrdersByUser($user['user_id']);

require_once __DIR__ . '/../pages/includes/header.php';
?>
<div class="container section">
    <div class="user-layout">
        <?php include __DIR__ . '/includes/user_sidebar.php'; ?>
        <div class="user-main-card">
            <div class="user-card-title">📦 Đơn hàng của tôi</div>
            <?= renderFlash() ?>

            <?php if (empty($orders)): ?>
                <div class="empty-state" style="padding:40px">
                    <div class="empty-icon">📦</div>
                    <div class="empty-title">Chưa có đơn hàng nào</div>
                    <div class="empty-desc">Hãy khám phá cửa hàng và đặt đơn đầu tiên của bạn!</div>
                    <a href="<?= BASE_URL ?>/pages/shop.php" class="btn-hero-primary" style="display:inline-flex; margin-top:16px">🛍 Mua sắm ngay</a>
                </div>
            <?php else: ?>
                <!-- Order tabs by status -->
                <div class="category-list" style="margin-bottom:20px" id="orderTabs">
                    <?php
                    $statuses = ['Tất cả' => '', 'Chờ xử lý' => 'PENDING', 'Đang giao' => 'SHIPPING', 'Hoàn thành' => 'COMPLETED', 'Đã hủy' => 'CANCELLED'];
                    $filterStatus = $_GET['status'] ?? '';
                    foreach ($statuses as $label => $val):
                    ?>
                        <a href="?status=<?= $val ?>" class="cat-pill <?= $filterStatus === $val ? 'active' : '' ?>">
                            <?= $label ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <?php
                $filtered = $filterStatus ? array_filter($orders, fn($o) => $o['status'] === $filterStatus) : $orders;
                ?>

                <?php if (empty($filtered)): ?>
                    <p style="color:var(--text-muted); text-align:center; padding:32px; font-size:.875rem">Không có đơn hàng nào.</p>
                <?php else: ?>
                    <?php foreach ($filtered as $o): ?>
                    <div style="background:var(--bg-glass); border:1px solid var(--border); border-radius:var(--radius-lg); padding:18px 20px; margin-bottom:12px; transition:border-color .15s"
                         onmouseover="this.style.borderColor='var(--border-hover)'" onmouseout="this.style.borderColor='var(--border)'">
                        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px">
                            <div>
                                <div style="font-family:'Space Grotesk',sans-serif; font-weight:700; color:var(--accent); font-size:.95rem">
                                    Đơn #<?= $o['id'] ?>
                                </div>
                                <div style="font-size:.78rem; color:var(--text-dim); margin-top:2px">
                                    📅 <?= formatDate($o['created_at']) ?>
                                </div>
                            </div>
                            <div style="display:flex; align-items:center; gap:14px">
                                <?= orderStatusBadge($o['status']) ?>
                                <span style="font-family:'Space Grotesk',sans-serif; font-weight:700; color:var(--text)">
                                    <?= formatPrice($o['total_price']) ?>
                                </span>
                                <span style="font-size:.78rem; color:var(--text-dim)"><?= paymentMethodName($o['payment_method']) ?></span>
                            </div>
                        </div>
                        <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--border); display:flex; gap:10px">
                            <a href="<?= BASE_URL ?>/user/order_detail.php?id=<?= $o['id'] ?>"
                               class="btn btn-secondary btn-sm">👁 Xem chi tiết</a>
                            <?php if ($o['status'] === 'PENDING'): ?>
                                <form method="POST" action="<?= BASE_URL ?>/api_actions/order_cancel.php" style="margin:0">
                                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                    <button type="submit" class="btn btn-sm"
                                            style="background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.2); color:#f87171"
                                            onclick="return confirm('Hủy đơn #<?= $o['id'] ?>?')">
                                        ✕ Hủy đơn
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../pages/includes/footer.php'; ?>
