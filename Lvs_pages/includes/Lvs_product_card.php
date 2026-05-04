<?php
/**
 * Lvs_pages/includes/Lvs_product_card.php
 * Định danh Lvs_ — Product card component
 * Tác giả phần: Lục Văn Sơn (2310900087)
 * Dùng trong vòng lặp: biến $p chứa thông tin sản phẩm
 */

// Normalize field name: đồng nghiệp dùng main_image, ta dùng image_url
if (empty($p['image_url']) && !empty($p['main_image'])) {
    $p['image_url'] = $p['main_image'];
}

$Lvs_isOnSale   = !empty($p['discount_price']) && $p['discount_price'] > 0;
$Lvs_finalPrice = $Lvs_isOnSale ? $p['discount_price'] : $p['price'];
$Lvs_discPct    = $Lvs_isOnSale ? round((1 - $p['discount_price'] / $p['price']) * 100) : 0;
$Lvs_stock      = (int)($p['stock_quantity'] ?? 0);
$Lvs_imgUrl     = !empty($p['image_url']) ? htmlspecialchars($p['image_url']) : BASE_URL . '/assets/images/no-image.png';
?>
<div class="product-card" data-product-id="<?= $p['id'] ?>">
    <!-- Image -->
    <div class="product-img-wrap">
        <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_product_detail.php?id=<?= $p['id'] ?>">
            <img src="<?= $Lvs_imgUrl ?>"
                 alt="<?= htmlspecialchars($p['name']) ?>"
                 loading="lazy"
                 onerror="this.src='<?= BASE_URL ?>/assets/images/no-image.png'">
        </a>

        <!-- Badges -->
        <div class="product-badges">
            <?php if ($Lvs_isOnSale): ?>
                <span class="product-badge product-badge--sale">-<?= $Lvs_discPct ?>%</span>
            <?php endif; ?>
            <?php if ($Lvs_stock <= 5 && $Lvs_stock > 0): ?>
                <span class="product-badge product-badge--stock">Còn <?= $Lvs_stock ?></span>
            <?php elseif ($Lvs_stock === 0): ?>
                <span class="product-badge product-badge--stock" style="background:#ef4444;color:#fff">Hết hàng</span>
            <?php endif; ?>
        </div>

        <!-- Quick Actions -->
        <div class="product-actions">
            <?php if (Lvs_isLoggedIn()): ?>
            <button class="product-action-btn fav-btn"
                    data-id="<?= $p['id'] ?>"
                    title="Thêm vào yêu thích"
                    onclick="Lvs_toggleFavorite(<?= $p['id'] ?>, this)">❤️</button>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_product_detail.php?id=<?= $p['id'] ?>"
               class="product-action-btn" title="Xem chi tiết">👁</a>
        </div>
    </div>

    <!-- Body -->
    <div class="product-body">
        <div class="product-category"><?= htmlspecialchars($p['category_name'] ?? 'Sản phẩm') ?></div>
        <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_product_detail.php?id=<?= $p['id'] ?>">
            <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
        </a>
        <div class="product-rating">
            <span class="product-stars">★★★★★</span>
        </div>
        <div class="product-price-row">
            <span class="product-price"><?= Lvs_formatPrice($Lvs_finalPrice) ?></span>
            <?php if ($Lvs_isOnSale): ?>
                <span class="product-price-old"><?= Lvs_formatPrice($p['price']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Add to cart -->
        <?php if ($Lvs_stock > 0): ?>
            <?php if (Lvs_isLoggedIn()): ?>
                <button class="btn-add-cart"
                        onclick="Lvs_addToCart(<?= $p['id'] ?>, this)">
                    🛒 Thêm vào giỏ
                </button>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/Lvs_login.php" class="btn-add-cart">
                    🔐 Đăng nhập để mua
                </a>
            <?php endif; ?>
        <?php else: ?>
            <button class="btn-add-cart" disabled style="opacity:.4; cursor:not-allowed;">Hết hàng</button>
        <?php endif; ?>
    </div>
</div>
