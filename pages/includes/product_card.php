<?php
/**
 * pages/includes/product_card.php
 * Reusable product card — include trong vòng lặp với biến $p
 * $p phải chứa: id, name, price, discount_price, stock_quantity, category_name, image_url
 */
require_once __DIR__ . '/../../utils/format_helper.php';

$isOnSale  = !empty($p['discount_price']) && $p['discount_price'] > 0;
$finalPrice= $isOnSale ? $p['discount_price'] : $p['price'];
$discPct   = $isOnSale ? round((1 - $p['discount_price'] / $p['price']) * 100) : 0;
$stock     = (int)($p['stock_quantity'] ?? 0);
$imgUrl    = !empty($p['image_url']) ? htmlspecialchars($p['image_url']) : BASE_URL . '/assets/images/no-image.png';
$isLoggedIn= isLoggedIn();
?>
<div class="product-card" data-product-id="<?= $p['id'] ?>">
    <!-- Image -->
    <div class="product-img-wrap">
        <a href="<?= BASE_URL ?>/pages/product_detail.php?id=<?= $p['id'] ?>">
            <img src="<?= $imgUrl ?>"
                 alt="<?= htmlspecialchars($p['name']) ?>"
                 loading="lazy"
                 onerror="this.src='<?= BASE_URL ?>/assets/images/no-image.png'">
        </a>

        <!-- Badges -->
        <div class="product-badges">
            <?php if ($isOnSale): ?>
                <span class="product-badge product-badge--sale">-<?= $discPct ?>%</span>
            <?php endif; ?>
            <?php if ($stock <= 5 && $stock > 0): ?>
                <span class="product-badge product-badge--stock">Còn <?= $stock ?></span>
            <?php elseif ($stock === 0): ?>
                <span class="product-badge product-badge--stock" style="background:#ef4444;color:#fff">Hết hàng</span>
            <?php endif; ?>
        </div>

        <!-- Quick Actions -->
        <div class="product-actions">
            <?php if ($isLoggedIn): ?>
            <button class="product-action-btn fav-btn"
                    data-id="<?= $p['id'] ?>"
                    title="Thêm vào yêu thích"
                    onclick="toggleFavorite(<?= $p['id'] ?>, this)">❤️</button>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/pages/product_detail.php?id=<?= $p['id'] ?>"
               class="product-action-btn" title="Xem chi tiết">👁</a>
        </div>
    </div>

    <!-- Body -->
    <div class="product-body">
        <div class="product-category"><?= htmlspecialchars($p['category_name'] ?? 'Sản phẩm') ?></div>
        <a href="<?= BASE_URL ?>/pages/product_detail.php?id=<?= $p['id'] ?>">
            <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
        </a>

        <!-- Fake stars (thay bằng API reviews sau) -->
        <div class="product-rating">
            <span class="product-stars">★★★★★</span>
            <span>(<?= rand(5, 48) ?>)</span>
        </div>

        <!-- Price -->
        <div class="product-price-row">
            <span class="product-price"><?= formatPrice($finalPrice) ?></span>
            <?php if ($isOnSale): ?>
                <span class="product-price-old"><?= formatPrice($p['price']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Add to cart -->
        <?php if ($stock > 0): ?>
            <?php if ($isLoggedIn): ?>
                <button class="btn-add-cart"
                        onclick="addToCart(<?= $p['id'] ?>, this)"
                        data-id="<?= $p['id'] ?>">
                    🛒 Thêm vào giỏ
                </button>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login.php" class="btn-add-cart">
                    🔐 Đăng nhập để mua
                </a>
            <?php endif; ?>
        <?php else: ?>
            <button class="btn-add-cart" disabled style="opacity:.4; cursor:not-allowed;">
                Hết hàng
            </button>
        <?php endif; ?>
    </div>
</div>
