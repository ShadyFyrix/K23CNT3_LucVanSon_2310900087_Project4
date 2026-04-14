<?php
/**
 * pages/product_detail.php — Chi tiết sản phẩm
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/product_model.php';
require_once __DIR__ . '/../models/review_model.php';
require_once __DIR__ . '/../models/favorite_model.php';
require_once __DIR__ . '/../models/cart_model.php';
require_once __DIR__ . '/../models/category_model.php';
require_once __DIR__ . '/../utils/auth_helper.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: ' . BASE_URL . '/pages/shop.php'); exit; }

$product = getProductById($id);
if (!$product) {
    http_response_code(404);
    die('<h2 style="text-align:center;padding:80px;color:#94a3b8">Không tìm thấy sản phẩm.</h2>');
}

$reviews   = getReviewsByProduct($id);
$avgRating = calcAverageRating($reviews);
$ratingCount = count($reviews);
$isFav     = isLoggedIn() ? isFavorited(getCurrentUser()['user_id'], $id) : false;
$currentUser = getCurrentUser();

$isOnSale  = !empty($product['discount_price']) && $product['discount_price'] > 0;
$finalPrice= $isOnSale ? $product['discount_price'] : $product['price'];
$discPct   = $isOnSale ? round((1 - $product['discount_price'] / $product['price']) * 100) : 0;

// Xử lý gửi đánh giá
$reviewError = $reviewSuccess = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    requireLogin();
    $rating  = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    if ($rating < 1 || $rating > 5) {
        $reviewError = 'Vui lòng chọn số sao từ 1 đến 5.';
    } elseif (empty($comment)) {
        $reviewError = 'Vui lòng nhập nội dung đánh giá.';
    } else {
        $res = addReview($currentUser['user_id'], $id, $rating, $comment);
        if (ApiClient::isSuccess($res)) {
            $reviewSuccess = 'Cảm ơn bạn đã đánh giá!';
            $reviews = getReviewsByProduct($id);
            $avgRating = calcAverageRating($reviews);
        } else {
            $reviewError = ApiClient::getError($res);
        }
    }
}

$pageTitle = htmlspecialchars($product['name']) . ' — UmaCT Shop';
$activeNav = 'shop';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container section">

    <!-- Breadcrumb -->
    <nav style="font-size:.78rem; color:var(--text-dim); margin-bottom:32px">
        <a href="<?= BASE_URL ?>/pages/home.php" style="color:var(--text-muted)">Trang chủ</a>
        <span style="margin:0 6px">›</span>
        <a href="<?= BASE_URL ?>/pages/shop.php" style="color:var(--text-muted)">Cửa hàng</a>
        <span style="margin:0 6px">›</span>
        <span><?= htmlspecialchars($product['name']) ?></span>
    </nav>

    <!-- PRODUCT GRID -->
    <div class="product-detail-grid">

        <!-- Gallery -->
        <div class="detail-gallery">
            <div class="detail-main-img" id="mainImgWrap">
                <img src="<?= !empty($product['image_url']) ? htmlspecialchars($product['image_url']) : BASE_URL . '/assets/images/no-image.png' ?>"
                     alt="<?= htmlspecialchars($product['name']) ?>"
                     id="mainImg"
                     style="width:100%;height:100%;object-fit:cover">
            </div>
            <!-- Thumbs (nếu có product_images) -->
            <div class="detail-thumbs" id="thumbsRow">
                <div class="detail-thumb active">
                    <img src="<?= !empty($product['image_url']) ? htmlspecialchars($product['image_url']) : BASE_URL . '/assets/images/no-image.png' ?>"
                         alt="" onclick="switchImg(this, '<?= !empty($product['image_url']) ? htmlspecialchars($product['image_url']) : '' ?>')">
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="detail-info">
            <div class="product-category"><?= htmlspecialchars($product['category_name'] ?? '') ?></div>
            <h1 class="detail-title"><?= htmlspecialchars($product['name']) ?></h1>

            <!-- Rating row -->
            <div class="detail-rating-row">
                <span style="color:var(--gold); letter-spacing:2px; font-size:1.1rem">
                    <?php for($i=1;$i<=5;$i++) echo $i<=$avgRating?'★':'☆'; ?>
                </span>
                <span style="font-size:.875rem; color:var(--text-muted)">
                    <?= number_format($avgRating, 1) ?> (<?= $ratingCount ?> đánh giá)
                </span>
                <?php if($product['stock_quantity'] > 0): ?>
                    <span style="font-size:.78rem; background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.2); padding:2px 10px; border-radius:99px">✓ Còn hàng</span>
                <?php else: ?>
                    <span style="font-size:.78rem; background:rgba(239,68,68,.12); color:#f87171; border:1px solid rgba(239,68,68,.2); padding:2px 10px; border-radius:99px">✕ Hết hàng</span>
                <?php endif; ?>
            </div>

            <!-- Price -->
            <div class="detail-price-row">
                <span class="detail-price"><?= formatPrice($finalPrice) ?></span>
                <?php if($isOnSale): ?>
                    <span class="detail-price-old"><?= formatPrice($product['price']) ?></span>
                    <span class="detail-disc">-<?= $discPct ?>%</span>
                <?php endif; ?>
            </div>

            <!-- Meta info -->
            <div class="detail-meta">
                <?php if(!empty($product['supplier_name'])): ?>
                <div class="detail-meta-row">
                    <span class="detail-meta-label">Nhà cung cấp</span>
                    <span class="detail-meta-val"><?= htmlspecialchars($product['supplier_name']) ?></span>
                </div>
                <?php endif; ?>
                <div class="detail-meta-row">
                    <span class="detail-meta-label">Tình trạng</span>
                    <span class="detail-meta-val"><?= $product['stock_quantity'] > 0 ? 'Còn ' . $product['stock_quantity'] . ' sản phẩm' : 'Hết hàng' ?></span>
                </div>
                <div class="detail-meta-row">
                    <span class="detail-meta-label">Mã sản phẩm</span>
                    <span class="detail-meta-val" style="color:var(--text-dim)">#UMA<?= str_pad($product['id'], 4, '0', STR_PAD_LEFT) ?></span>
                </div>
            </div>

            <!-- Quantity + Actions -->
            <?php if($product['stock_quantity'] > 0): ?>
                <?php if(isLoggedIn()): ?>
                <div class="qty-picker" style="margin-bottom:20px">
                    <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                    <input type="number" class="qty-input" id="qtyInput" value="1" min="1" max="<?= $product['stock_quantity'] ?>">
                    <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                    <span style="margin-left:12px; font-size:.78rem; color:var(--text-dim)">/ <?= $product['stock_quantity'] ?> cái</span>
                </div>
                <div class="detail-actions">
                    <button class="btn-cart" id="btnAddCart" onclick="addToCartDetail(<?= $product['id'] ?>)">
                        🛒 Thêm vào giỏ hàng
                    </button>
                    <button class="btn-wish <?= $isFav ? 'active' : '' ?>"
                            id="btnFav"
                            onclick="toggleFavoriteDetail(<?= $product['id'] ?>, this)"
                            title="Yêu thích">
                        <?= $isFav ? '❤️' : '🤍' ?>
                    </button>
                </div>
                <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login.php" class="btn-cart" style="display:inline-flex; width:auto">
                    🔐 Đăng nhập để mua hàng
                </a>
                <?php endif; ?>
            <?php else: ?>
                <button class="btn-cart" disabled style="opacity:.4; cursor:not-allowed">Hết hàng</button>
            <?php endif; ?>

            <!-- Description -->
            <?php if(!empty($product['description'])): ?>
            <div style="margin-top:28px; padding-top:24px; border-top:1px solid var(--border)">
                <h3 style="font-size:.9rem; font-weight:700; margin-bottom:10px; color:var(--text-muted)">📋 Mô tả sản phẩm</h3>
                <div style="font-size:.875rem; color:var(--text-muted); line-height:1.8">
                    <?= nl2br(htmlspecialchars($product['description'])) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

    </div><!-- /.product-detail-grid -->

    <!-- ===== REVIEWS SECTION ===== -->
    <div style="margin-top:60px; padding-top:40px; border-top:1px solid var(--border)">
        <h2 style="font-family:'Space Grotesk',sans-serif; font-size:1.3rem; font-weight:700; margin-bottom:28px">
            ⭐ Đánh giá sản phẩm <span style="color:var(--text-muted); font-size:1rem; font-weight:400">(<?= $ratingCount ?>)</span>
        </h2>

        <!-- Summary -->
        <?php if($ratingCount > 0): ?>
        <div class="reviews-summary">
            <div class="avg-score">
                <div class="avg-num"><?= number_format($avgRating, 1) ?></div>
                <div class="avg-stars"><?php for($i=1;$i<=5;$i++) echo $i<=$avgRating?'★':'☆'; ?></div>
                <div class="avg-count"><?= $ratingCount ?> đánh giá</div>
            </div>
            <div class="rating-bars">
                <?php for($s=5;$s>=1;$s--):
                    $cnt = count(array_filter($reviews, fn($r) => $r['rating'] == $s));
                    $pct = $ratingCount > 0 ? ($cnt / $ratingCount * 100) : 0;
                ?>
                <div class="rating-bar-row">
                    <span class="rating-bar-label"><?= $s ?></span>
                    <div class="rating-bar-track"><div class="rating-bar-fill" style="width:<?= $pct ?>%"></div></div>
                    <span class="rating-bar-count"><?= $cnt ?></span>
                </div>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Review List -->
        <?php if(!empty($reviews)): ?>
            <div style="margin-bottom:32px">
                <?php foreach($reviews as $rv): ?>
                <div class="review-card">
                    <div class="review-header">
                        <div class="review-user">
                            <div class="review-avatar">
                                <?php if(!empty($rv['avatar_url'])): ?>
                                    <img src="<?= htmlspecialchars($rv['avatar_url']) ?>" alt="">
                                <?php else: ?>
                                    <?= strtoupper(substr($rv['full_name'] ?? $rv['username'] ?? 'U', 0, 1)) ?>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="review-name"><?= htmlspecialchars($rv['full_name'] ?? $rv['username'] ?? 'Ẩn danh') ?></div>
                                <div class="review-date"><?= formatDate($rv['created_at']) ?></div>
                            </div>
                        </div>
                        <div class="review-stars">
                            <?php for($i=1;$i<=5;$i++) echo $i<=$rv['rating']?'★':'☆'; ?>
                        </div>
                    </div>
                    <p class="review-text"><?= htmlspecialchars($rv['comment']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state" style="padding:40px">
                <div class="empty-icon">💬</div>
                <div class="empty-title">Chưa có đánh giá nào</div>
                <div class="empty-desc">Hãy là người đầu tiên đánh giá sản phẩm này!</div>
            </div>
        <?php endif; ?>

        <!-- Write Review Form -->
        <?php if(isLoggedIn()): ?>
        <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); padding:24px">
            <h3 style="font-size:1rem; font-weight:700; margin-bottom:18px">✍️ Viết đánh giá của bạn</h3>

            <?php if($reviewError): ?>
                <div class="alert alert-error"><?= htmlspecialchars($reviewError) ?></div>
            <?php endif; ?>
            <?php if($reviewSuccess): ?>
                <div class="alert alert-success"><?= htmlspecialchars($reviewSuccess) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div style="margin-bottom:16px">
                    <label style="font-size:.85rem; font-weight:600; margin-bottom:8px; display:block">Chọn số sao</label>
                    <div class="star-rating" id="starRating" style="display:flex; gap:6px; font-size:1.8rem; cursor:pointer; color:var(--text-dim)">
                        <?php for($i=1;$i<=5;$i++): ?>
                            <span onclick="setRating(<?= $i ?>)" data-val="<?= $i ?>" style="transition:color .1s">☆</span>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="0">
                </div>
                <div style="margin-bottom:16px">
                    <label style="font-size:.85rem; font-weight:600; margin-bottom:8px; display:block">Nội dung đánh giá</label>
                    <textarea name="comment" rows="4"
                              style="width:100%; background:var(--bg-glass); border:1px solid var(--border); border-radius:10px; padding:12px; color:var(--text); font-size:.875rem; resize:vertical; outline:none; font-family:inherit"
                              placeholder="Chia sẻ cảm nhận của bạn về sản phẩm này..."
                              required></textarea>
                </div>
                <button type="submit" name="submit_review" class="btn-hero-primary" style="border:none">
                    📤 Gửi đánh giá
                </button>
            </form>
        </div>
        <?php else: ?>
        <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); padding:24px; text-align:center">
            <p style="color:var(--text-muted); margin-bottom:14px">Đăng nhập để viết đánh giá cho sản phẩm này</p>
            <a href="<?= BASE_URL ?>/auth/login.php" class="btn-hero-primary" style="display:inline-flex">
                🔐 Đăng nhập ngay
            </a>
        </div>
        <?php endif; ?>
    </div>

</div><!-- /.container -->

<script>
// Gallery switch
function switchImg(thumb, url) {
    document.getElementById('mainImg').src = url;
    document.querySelectorAll('.detail-thumb').forEach(t => t.classList.remove('active'));
    thumb.parentElement.classList.add('active');
}

// Quantity
function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    const max   = parseInt(input.max);
    let val = parseInt(input.value) + delta;
    if (val < 1)   val = 1;
    if (val > max) val = max;
    input.value = val;
}

// Star rating
function setRating(val) {
    document.getElementById('ratingInput').value = val;
    document.querySelectorAll('#starRating span').forEach((s, i) => {
        s.textContent = i < val ? '★' : '☆';
        s.style.color = i < val ? '#f59e0b' : 'var(--text-dim)';
    });
}

// Add to cart from detail
function addToCartDetail(productId) {
    const qty = parseInt(document.getElementById('qtyInput')?.value || 1);
    const btn = document.getElementById('btnAddCart');
    btn.innerHTML = '⏳ Đang thêm...';
    btn.disabled = true;

    fetch('<?= BASE_URL ?>/api_actions/cart_add.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, quantity: qty })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            btn.innerHTML = '✅ Đã thêm vào giỏ!';
            setTimeout(() => { btn.innerHTML = '🛒 Thêm vào giỏ hàng'; btn.disabled = false; }, 2000);
        } else {
            alert(data.message || 'Có lỗi xảy ra!');
            btn.innerHTML = '🛒 Thêm vào giỏ hàng';
            btn.disabled = false;
        }
    });
}

// Toggle favorite
function toggleFavoriteDetail(productId, btn) {
    fetch('<?= BASE_URL ?>/api_actions/favorite_toggle.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            const isNowFav = data.action === 'added';
            btn.innerHTML = isNowFav ? '❤️' : '🤍';
            btn.classList.toggle('active', isNowFav);
        }
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
