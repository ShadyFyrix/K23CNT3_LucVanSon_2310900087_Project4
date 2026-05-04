<?php
/**
 * Lvs_pages/Lvs_product_detail.php — Chi tiết sản phẩm
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Lvs_product_model.php';
require_once __DIR__ . '/../models/Lvs_review_model.php';
require_once __DIR__ . '/../models/Lvs_favorite_model.php';
require_once __DIR__ . '/../models/Lvs_cart_model.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../utils/Lvs_format_helper.php';
require_once __DIR__ . '/../utils/api_client.php'; // ApiClient::isSuccess

$Lvs_id = (int)($_GET['id'] ?? 0);
if (!$Lvs_id) { header('Location: ' . BASE_URL . '/Lvs_pages/Lvs_shop.php'); exit; }

$Lvs_product = Lvs_getProductById($Lvs_id);
if (!$Lvs_product) {
    http_response_code(404);
    die('<h2 style="text-align:center;padding:80px;color:#94a3b8">Không tìm thấy sản phẩm.</h2>');
}

$Lvs_reviews     = Lvs_getReviewsByProduct($Lvs_id);
$Lvs_avgRating   = Lvs_calcAverageRating($Lvs_reviews);
$Lvs_ratingCount = count($Lvs_reviews);
$Lvs_user        = Lvs_getCurrentUser();
$Lvs_isFav       = Lvs_isLoggedIn() ? Lvs_isFavorited($Lvs_user['user_id'], $Lvs_id) : false;

$Lvs_isOnSale   = !empty($Lvs_product['discount_price']) && $Lvs_product['discount_price'] > 0;
$Lvs_finalPrice = $Lvs_isOnSale ? $Lvs_product['discount_price'] : $Lvs_product['price'];
$Lvs_discPct    = $Lvs_isOnSale ? round((1 - $Lvs_product['discount_price'] / $Lvs_product['price']) * 100) : 0;

// Xử lý gửi đánh giá
$Lvs_reviewError = $Lvs_reviewSuccess = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Lvs_submit_review'])) {
    Lvs_requireLogin();
    $Lvs_rating  = (int)($_POST['rating'] ?? 0);
    $Lvs_comment = trim($_POST['comment'] ?? '');
    if ($Lvs_rating < 1 || $Lvs_rating > 5) {
        $Lvs_reviewError = 'Vui lòng chọn số sao từ 1 đến 5.';
    } elseif (empty($Lvs_comment)) {
        $Lvs_reviewError = 'Vui lòng nhập nội dung đánh giá.';
    } else {
        $Lvs_res = Lvs_addReview($Lvs_user['user_id'], $Lvs_id, $Lvs_rating, $Lvs_comment);
        if (ApiClient::isSuccess($Lvs_res)) {
            $Lvs_reviewSuccess = 'Cảm ơn bạn đã đánh giá!';
            $Lvs_reviews     = Lvs_getReviewsByProduct($Lvs_id);
            $Lvs_avgRating   = Lvs_calcAverageRating($Lvs_reviews);
        } else {
            $Lvs_reviewError = ApiClient::getError($Lvs_res);
        }
    }
}

$pageTitle = htmlspecialchars($Lvs_product['name']) . ' — UmaCT Shop';
$activeNav = 'shop';
require_once __DIR__ . '/includes/Lvs_header.php';

// Normalize image
$Lvs_imgUrl = !empty($Lvs_product['image_url']) ? $Lvs_product['image_url'] : (!empty($Lvs_product['main_image']) ? $Lvs_product['main_image'] : BASE_URL.'/assets/images/no-image.png');
?>
<div class="container section">

    <!-- Breadcrumb -->
    <nav style="font-size:.78rem;color:var(--text-dim);margin-bottom:32px">
        <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_home.php" style="color:var(--text-muted)">Trang chủ</a>
        <span style="margin:0 6px">›</span>
        <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php" style="color:var(--text-muted)">Cửa hàng</a>
        <span style="margin:0 6px">›</span>
        <span><?= htmlspecialchars($Lvs_product['name']) ?></span>
    </nav>

    <!-- PRODUCT GRID -->
    <div class="product-detail-grid">
        <!-- Gallery -->
        <div class="detail-gallery">
            <div class="detail-main-img" id="Lvs_mainImgWrap">
                <img src="<?= htmlspecialchars($Lvs_imgUrl) ?>"
                     alt="<?= htmlspecialchars($Lvs_product['name']) ?>"
                     id="Lvs_mainImg" style="width:100%;height:100%;object-fit:cover"
                     onerror="this.src='<?= BASE_URL ?>/assets/images/no-image.png'">
            </div>
            <div class="detail-thumbs" id="Lvs_thumbsRow">
                <div class="detail-thumb active">
                    <img src="<?= htmlspecialchars($Lvs_imgUrl) ?>" alt=""
                         onclick="Lvs_switchImg(this, '<?= htmlspecialchars($Lvs_imgUrl) ?>')">
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="detail-info">
            <div class="product-category"><?= htmlspecialchars($Lvs_product['category_name'] ?? '') ?></div>
            <h1 class="detail-title"><?= htmlspecialchars($Lvs_product['name']) ?></h1>

            <div class="detail-rating-row">
                <span style="color:var(--gold);letter-spacing:2px;font-size:1.1rem">
                    <?php for($Lvs_i=1;$Lvs_i<=5;$Lvs_i++) echo $Lvs_i<=$Lvs_avgRating?'★':'☆'; ?>
                </span>
                <span style="font-size:.875rem;color:var(--text-muted)">
                    <?= number_format($Lvs_avgRating, 1) ?> (<?= $Lvs_ratingCount ?> đánh giá)
                </span>
                <?php if($Lvs_product['stock_quantity'] > 0): ?>
                    <span style="font-size:.78rem;background:rgba(34,197,94,.12);color:#4ade80;border:1px solid rgba(34,197,94,.2);padding:2px 10px;border-radius:99px">✓ Còn hàng</span>
                <?php else: ?>
                    <span style="font-size:.78rem;background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.2);padding:2px 10px;border-radius:99px">✕ Hết hàng</span>
                <?php endif; ?>
            </div>

            <div class="detail-price-row">
                <span class="detail-price"><?= Lvs_formatPrice($Lvs_finalPrice) ?></span>
                <?php if($Lvs_isOnSale): ?>
                    <span class="detail-price-old"><?= Lvs_formatPrice($Lvs_product['price']) ?></span>
                    <span class="detail-disc">-<?= $Lvs_discPct ?>%</span>
                <?php endif; ?>
            </div>

            <div class="detail-meta">
                <?php if(!empty($Lvs_product['supplier_name'])): ?>
                <div class="detail-meta-row"><span class="detail-meta-label">Nhà cung cấp</span><span class="detail-meta-val"><?= htmlspecialchars($Lvs_product['supplier_name']) ?></span></div>
                <?php endif; ?>
                <div class="detail-meta-row"><span class="detail-meta-label">Mã SP</span><span class="detail-meta-val" style="color:var(--text-dim)">#UMA<?= str_pad($Lvs_product['id'], 4, '0', STR_PAD_LEFT) ?></span></div>
            </div>

            <?php if($Lvs_product['stock_quantity'] > 0): ?>
                <?php if(Lvs_isLoggedIn()): ?>
                <div class="qty-picker" style="margin-bottom:20px">
                    <button type="button" class="qty-btn" onclick="Lvs_changeQty(-1)">−</button>
                    <input type="number" class="qty-input" id="Lvs_qtyInput" value="1" min="1" max="<?= $Lvs_product['stock_quantity'] ?>">
                    <button type="button" class="qty-btn" onclick="Lvs_changeQty(1)">+</button>
                    <span style="margin-left:12px;font-size:.78rem;color:var(--text-dim)">/ <?= $Lvs_product['stock_quantity'] ?> cái</span>
                </div>
                <div class="detail-actions">
                    <button class="btn-cart" id="Lvs_btnAddCart" onclick="Lvs_addToCartDetail(<?= $Lvs_product['id'] ?>)">🛒 Thêm vào giỏ hàng</button>
                    <button class="btn-wish <?= $Lvs_isFav ? 'active' : '' ?>" id="Lvs_btnFav"
                            onclick="Lvs_toggleFavoriteDetail(<?= $Lvs_product['id'] ?>, this)" title="Yêu thích">
                        <?= $Lvs_isFav ? '❤️' : '🤍' ?>
                    </button>
                </div>
                <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/Lvs_login.php" class="btn-cart" style="display:inline-flex;width:auto">🔐 Đăng nhập để mua hàng</a>
                <?php endif; ?>
            <?php else: ?>
                <button class="btn-cart" disabled style="opacity:.4;cursor:not-allowed">Hết hàng</button>
            <?php endif; ?>

            <?php if(!empty($Lvs_product['description'])): ?>
            <div style="margin-top:28px;padding-top:24px;border-top:1px solid var(--border)">
                <h3 style="font-size:.9rem;font-weight:700;margin-bottom:10px;color:var(--text-muted)">📋 Mô tả sản phẩm</h3>
                <div style="font-size:.875rem;color:var(--text-muted);line-height:1.8"><?= nl2br(htmlspecialchars($Lvs_product['description'])) ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- REVIEWS -->
    <div style="margin-top:60px;padding-top:40px;border-top:1px solid var(--border)">
        <h2 style="font-family:'Space Grotesk',sans-serif;font-size:1.3rem;font-weight:700;margin-bottom:28px">
            ⭐ Đánh giá sản phẩm <span style="color:var(--text-muted);font-size:1rem;font-weight:400">(<?= $Lvs_ratingCount ?>)</span>
        </h2>
        <?php if($Lvs_ratingCount > 0): ?>
        <div class="reviews-summary">
            <div class="avg-score">
                <div class="avg-num"><?= number_format($Lvs_avgRating, 1) ?></div>
                <div class="avg-stars"><?php for($Lvs_i=1;$Lvs_i<=5;$Lvs_i++) echo $Lvs_i<=$Lvs_avgRating?'★':'☆'; ?></div>
                <div class="avg-count"><?= $Lvs_ratingCount ?> đánh giá</div>
            </div>
            <div class="rating-bars">
                <?php for($Lvs_s=5;$Lvs_s>=1;$Lvs_s--):
                    $Lvs_cnt = count(array_filter($Lvs_reviews, fn($r) => $r['rating'] == $Lvs_s));
                    $Lvs_pct = $Lvs_ratingCount > 0 ? ($Lvs_cnt/$Lvs_ratingCount*100) : 0;
                ?>
                <div class="rating-bar-row">
                    <span class="rating-bar-label"><?= $Lvs_s ?></span>
                    <div class="rating-bar-track"><div class="rating-bar-fill" style="width:<?= $Lvs_pct ?>%"></div></div>
                    <span class="rating-bar-count"><?= $Lvs_cnt ?></span>
                </div>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if(!empty($Lvs_reviews)): ?>
        <div style="margin-bottom:32px">
            <?php foreach($Lvs_reviews as $Lvs_rv): ?>
            <div class="review-card">
                <div class="review-header">
                    <div class="review-user">
                        <div class="review-avatar"><?= strtoupper(substr($Lvs_rv['full_name'] ?? $Lvs_rv['username'] ?? 'U', 0, 1)) ?></div>
                        <div>
                            <div class="review-name"><?= htmlspecialchars($Lvs_rv['full_name'] ?? $Lvs_rv['username'] ?? 'Ẩn danh') ?></div>
                            <div class="review-date"><?= Lvs_formatDate($Lvs_rv['created_at']) ?></div>
                        </div>
                    </div>
                    <div class="review-stars"><?php for($Lvs_i=1;$Lvs_i<=5;$Lvs_i++) echo $Lvs_i<=$Lvs_rv['rating']?'★':'☆'; ?></div>
                </div>
                <p class="review-text"><?= htmlspecialchars($Lvs_rv['comment']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state" style="padding:40px"><div class="empty-icon">💬</div><div class="empty-title">Chưa có đánh giá</div></div>
        <?php endif; ?>

        <!-- Write Review -->
        <?php if(Lvs_isLoggedIn()): ?>
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px">
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:18px">✍️ Viết đánh giá của bạn</h3>
            <?php if($Lvs_reviewError): ?><div class="alert alert-error"><?= htmlspecialchars($Lvs_reviewError) ?></div><?php endif; ?>
            <?php if($Lvs_reviewSuccess): ?><div class="alert alert-success"><?= htmlspecialchars($Lvs_reviewSuccess) ?></div><?php endif; ?>
            <form method="POST">
                <div style="margin-bottom:16px">
                    <label style="font-size:.85rem;font-weight:600;margin-bottom:8px;display:block">Chọn số sao</label>
                    <div id="Lvs_starRating" style="display:flex;gap:6px;font-size:1.8rem;cursor:pointer;color:var(--text-dim)">
                        <?php for($Lvs_i=1;$Lvs_i<=5;$Lvs_i++): ?>
                            <span onclick="Lvs_setRating(<?= $Lvs_i ?>)" data-val="<?= $Lvs_i ?>" style="transition:color .1s">☆</span>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="rating" id="Lvs_ratingInput" value="0">
                </div>
                <div style="margin-bottom:16px">
                    <textarea name="comment" rows="4"
                              style="width:100%;background:var(--bg-glass);border:1px solid var(--border);border-radius:10px;padding:12px;color:var(--text);font-size:.875rem;resize:vertical;outline:none;font-family:inherit"
                              placeholder="Chia sẻ cảm nhận của bạn..." required></textarea>
                </div>
                <button type="submit" name="Lvs_submit_review" class="btn-hero-primary" style="border:none">📤 Gửi đánh giá</button>
            </form>
        </div>
        <?php else: ?>
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;text-align:center">
            <p style="color:var(--text-muted);margin-bottom:14px">Đăng nhập để viết đánh giá</p>
            <a href="<?= BASE_URL ?>/auth/Lvs_login.php" class="btn-hero-primary" style="display:inline-flex">🔐 Đăng nhập</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function Lvs_switchImg(Lvs_thumb, Lvs_url) {
    document.getElementById('Lvs_mainImg').src = Lvs_url;
    document.querySelectorAll('.detail-thumb').forEach(t => t.classList.remove('active'));
    Lvs_thumb.parentElement.classList.add('active');
}
function Lvs_changeQty(Lvs_delta) {
    const Lvs_inp = document.getElementById('Lvs_qtyInput');
    const Lvs_max = parseInt(Lvs_inp.max);
    let Lvs_val = parseInt(Lvs_inp.value) + Lvs_delta;
    if (Lvs_val < 1) Lvs_val = 1;
    if (Lvs_val > Lvs_max) Lvs_val = Lvs_max;
    Lvs_inp.value = Lvs_val;
}
function Lvs_setRating(Lvs_val) {
    document.getElementById('Lvs_ratingInput').value = Lvs_val;
    document.querySelectorAll('#Lvs_starRating span').forEach((s, i) => {
        s.textContent = i < Lvs_val ? '★' : '☆';
        s.style.color = i < Lvs_val ? '#f59e0b' : 'var(--text-dim)';
    });
}
function Lvs_addToCartDetail(Lvs_pid) {
    const Lvs_qty = parseInt(document.getElementById('Lvs_qtyInput')?.value || 1);
    const Lvs_btn = document.getElementById('Lvs_btnAddCart');
    Lvs_btn.innerHTML = '⏳ Đang thêm...'; Lvs_btn.disabled = true;
    fetch('<?= BASE_URL ?>/Lvs_api_actions/Lvs_cart_add.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({product_id: Lvs_pid, quantity: Lvs_qty})
    }).then(r=>r.json()).then(d=>{
        if(d.status==='success'){
            Lvs_btn.innerHTML='✅ Đã thêm vào giỏ!';
            setTimeout(()=>{Lvs_btn.innerHTML='🛒 Thêm vào giỏ hàng';Lvs_btn.disabled=false;},2000);
        } else { alert(d.message||'Lỗi'); Lvs_btn.innerHTML='🛒 Thêm vào giỏ hàng'; Lvs_btn.disabled=false; }
    });
}
function Lvs_toggleFavoriteDetail(Lvs_pid, Lvs_btn) {
    fetch('<?= BASE_URL ?>/Lvs_api_actions/Lvs_favorite_toggle.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({product_id: Lvs_pid})
    }).then(r=>r.json()).then(d=>{
        if(d.status==='success'){
            const Lvs_fav = d.action === 'added';
            Lvs_btn.innerHTML = Lvs_fav ? '❤️' : '🤍';
            Lvs_btn.classList.toggle('active', Lvs_fav);
        }
    });
}
</script>
<?php require_once __DIR__ . '/includes/Lvs_footer.php'; ?>
