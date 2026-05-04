<?php
/**
 * Lvs_user/Lvs_favorites.php — Danh sách yêu thích
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 * Backend: GET /api/users/{user_id}/favorites
 */
$pageTitle = 'Yêu thích — UmaCT Shop';
$activeNav = '';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../utils/Lvs_format_helper.php';
require_once __DIR__ . '/../models/Lvs_favorite_model.php';

Lvs_requireLogin();
$Lvs_user      = Lvs_getCurrentUser();
$Lvs_favorites = Lvs_getFavorites($Lvs_user['user_id']);

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
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_order_history.php" class="user-nav-link">📦 Đơn hàng</a>
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_favorites.php" class="user-nav-link active">❤️ Yêu thích</a>
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_change_password.php" class="user-nav-link">🔐 Đổi mật khẩu</a>
            <a href="<?= BASE_URL ?>/auth/Lvs_logout.php" class="user-nav-link" style="color:#f87171">🚪 Đăng xuất</a>
        </nav>
    </aside>

    <div class="user-main-card">
        <div class="user-card-title">❤️ Danh sách yêu thích (<?= count($Lvs_favorites) ?>)</div>
        <?php if (empty($Lvs_favorites)): ?>
            <div class="empty-state">
                <div class="empty-icon">❤️</div>
                <div class="empty-title">Chưa có sản phẩm yêu thích</div>
                <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php" class="btn-hero-primary" style="display:inline-flex;margin-top:20px">🛍 Khám phá ngay</a>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($Lvs_favorites as $p):
                    // Normalize: backend trả main_image
                    if (empty($p['image_url']) && !empty($p['main_image'])) $p['image_url'] = $p['main_image'];
                    include __DIR__ . '/../Lvs_pages/includes/Lvs_product_card.php';
                endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div></div>
<?php require_once __DIR__ . '/../Lvs_pages/includes/Lvs_footer.php'; ?>
