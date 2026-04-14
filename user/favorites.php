<?php
/** user/favorites.php — Danh sách yêu thích */
$pageTitle = 'Yêu thích — UmaCT Shop';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../utils/format_helper.php';
require_once __DIR__ . '/../models/favorite_model.php';

requireLogin();
$user = getCurrentUser();
$favorites = getFavorites($user['user_id']);

require_once __DIR__ . '/../pages/includes/header.php';
?>
<div class="container section">
    <div class="user-layout">
        <?php include __DIR__ . '/includes/user_sidebar.php'; ?>
        <div class="user-main-card">
            <div class="user-card-title">❤️ Danh sách yêu thích (<?= count($favorites) ?>)</div>

            <?php if (empty($favorites)): ?>
                <div class="empty-state" style="padding:40px">
                    <div class="empty-icon">🤍</div>
                    <div class="empty-title">Chưa có sản phẩm yêu thích</div>
                    <div class="empty-desc">Nhấn ❤️ trên bất kỳ sản phẩm nào để lưu vào đây</div>
                    <a href="<?= BASE_URL ?>/pages/shop.php" class="btn-hero-primary" style="display:inline-flex; margin-top:16px">🛍 Khám phá sản phẩm</a>
                </div>
            <?php else: ?>
                <div class="products-grid" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr))">
                    <?php foreach ($favorites as $fav): ?>
                    <div style="background:var(--bg-glass); border:1px solid var(--border); border-radius:var(--radius-lg); overflow:hidden; position:relative; transition:transform .2s, border-color .2s"
                         onmouseover="this.style.transform='translateY(-4px)'; this.style.borderColor='var(--border-hover)'"
                         onmouseout="this.style.transform=''; this.style.borderColor='var(--border)'">
                        <a href="<?= BASE_URL ?>/pages/product_detail.php?id=<?= $fav['product_id'] ?>">
                            <div style="aspect-ratio:1; background:var(--bg-card); overflow:hidden">
                                <img src="<?= !empty($fav['image_url']) ? htmlspecialchars($fav['image_url']) : BASE_URL . '/assets/images/no-image.png' ?>"
                                     alt="<?= htmlspecialchars($fav['product_name']) ?>"
                                     style="width:100%; height:100%; object-fit:cover; transition:transform .4s"
                                     onmouseover="this.style.transform='scale(1.06)'"
                                     onmouseout="this.style.transform=''">
                            </div>
                        </a>
                        <div style="padding:12px">
                            <a href="<?= BASE_URL ?>/pages/product_detail.php?id=<?= $fav['product_id'] ?>"
                               style="font-size:.875rem; font-weight:600; color:var(--text); display:block; margin-bottom:6px; line-height:1.35"
                               onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text)'">
                                <?= htmlspecialchars(truncate($fav['product_name'], 50)) ?>
                            </a>
                            <div style="font-family:'Space Grotesk',sans-serif; font-weight:700; color:var(--accent); margin-bottom:10px">
                                <?= formatPrice($fav['price'] ?? 0) ?>
                            </div>
                            <button onclick="removeFav(<?= $fav['favorite_id'] ?>, this)"
                                    style="width:100%; padding:7px; background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.2); border-radius:8px; color:#f87171; font-size:.8rem; font-weight:600; cursor:pointer; transition:all .15s"
                                    onmouseover="this.style.background='rgba(239,68,68,.2)'"
                                    onmouseout="this.style.background='rgba(239,68,68,.1)'">
                                🗑 Xóa khỏi yêu thích
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
function removeFav(id, btn) {
    btn.textContent = '⏳';
    btn.disabled = true;
    fetch('<?= BASE_URL ?>/api_actions/favorite_toggle.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ favorite_id: id })
    }).then(r => r.json()).then(() => btn.closest('[style]').remove());
}
</script>
<?php require_once __DIR__ . '/../pages/includes/footer.php'; ?>
